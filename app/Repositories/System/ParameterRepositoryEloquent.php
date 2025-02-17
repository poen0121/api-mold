<?php

namespace App\Repositories\System;

use App\Libraries\Upgrades\BetterBaseRepository as BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\System\ParameterRepository;
use App\Entities\System\Parameter;
use App\Validators\System\ParameterValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\System\ParameterExceptionCode as ExceptionCode;
use Cache;
use DB;
use Validator;
use Exception;

/**
 * Class ParameterRepositoryEloquent.
 *
 * @package namespace App\Repositories\System;
 */
class ParameterRepositoryEloquent extends BaseRepository implements ParameterRepository
{
    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        // Return empty is close presenter default transformer.
        return "App\\Presenters\\System\\ParameterPresenter";
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Parameter::class;
    }

    /**
     * Specify Validator class name
     *
     * @return string
     */
    public function validator()
    {
        // Return empty is to close the validator about create and update on the repository.
        return ParameterValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Get a list of data for the existing parameters.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function index(object $request): array
    {
        /* Criteria Index */
        $this->pushCriteria(app('App\Criteria\System\Parameter\IndexCriteria'));

        $result = $this->paginate($request->input('rows', 15));
        if (isset($result['meta']['pagination']['links'])) {
            unset($result['meta']['pagination']['links']);
        }
        return $result;
    }

    /**
     * Get the data focus parameter.
     *
     * @param string $slug
     *
     * @return array
     * @throws \Exception
     */
    public function focusSlug(string $slug): array
    {
        $rules = config('sp.rules');
        if (isset($rules[$slug])) {
            $result = $this->findByField('slug', $slug);
            if (count($result['data']) > 0) {
                return $result['data'][0];
            }
        }
        throw new ModelNotFoundException('Query System Parameter: No query results for index: Unknown slug \'' . $slug . '\'.');
    }
}
