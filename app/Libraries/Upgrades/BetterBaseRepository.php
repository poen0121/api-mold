<?php

namespace App\Libraries\Upgrades;

use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityCreating;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Events\RepositoryEntityUpdating;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Repository\Eloquent\BaseRepository;

abstract class BetterBaseRepository extends BaseRepository
{
    /**
     * Save a new entity in repository
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        if (!is_null($this->validator) && count($this->validator->getRules(ValidatorInterface::RULE_CREATE)) > 0) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $this->validator->with($this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray())->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        event(new RepositoryEntityCreating($this, $attributes));

        $model = $this->model->newInstance($attributes);
        $model->save();
        $this->resetModel();

        event(new RepositoryEntityCreated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update a entity in repository by id
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        $this->applyScope();

        if (!is_null($this->validator) && count($this->validator->getRules(ValidatorInterface::RULE_UPDATE)) > 0) {
            // we should pass data that has been casts by the model
            // to make sure data type are same because validator may need to use
            // this data to compare with data that fetch from database.
            $this->validator->with($this->model->newInstance()->forceFill($attributes)->makeVisible($this->model->getHidden())->toArray())->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        $model = $this->model->findOrFail($id);

        event(new RepositoryEntityUpdating($this, $model));

        $model->fill($attributes);
        $model->save();

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }

    /**
     * Update or Create an entity in repository
     *
     * @throws ValidatorException
     *
     * @param array $attributes
     * @param array $values
     *
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->applyScope();

        if (!is_null($this->validator) && count($this->validator->getRules(ValidatorInterface::RULE_CREATE)) > 0) {
            $this->validator->with(array_merge($attributes, $values))->passesOrFail(ValidatorInterface::RULE_CREATE);
        }

        $temporarySkipPresenter = $this->skipPresenter;

        $this->skipPresenter(true);

        event(new RepositoryEntityCreating($this, $attributes));

        $model = $this->model->updateOrCreate($attributes, $values);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        event(new RepositoryEntityUpdated($this, $model));

        return $this->parserResult($model);
    }
}
