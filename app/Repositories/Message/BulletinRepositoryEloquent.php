<?php

namespace App\Repositories\Message;

use App\Libraries\Upgrades\BetterBaseRepository as BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Message\BulletinRepository;
use App\Entities\Message\Bulletin;
use App\Validators\Message\BulletinValidator;

/**
 * Class BulletinRepositoryEloquent.
 *
 * @package namespace App\Repositories\Message;
 */
class BulletinRepositoryEloquent extends BaseRepository implements BulletinRepository
{
    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        // Return empty is close presenter default transformer.
        return "App\\Presenters\\Message\\BulletinPresenter";
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Bulletin::class;
    }

    /**
     * Specify Validator class name
     *
     * @return string
     */
    public function validator()
    {
        // Return empty is to close the validator about create and update on the repository.
        return BulletinValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Get a list of data for existing bulletins.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function index(object $request): array
    {
        /* Criteria Index */
        $this->pushCriteria(app('App\Criteria\Message\Bulletin\IndexCriteria')
        ->putFiller('creation_sort', $request->input('creation_sort'))
        ->putFiller('start', $request->input('start'))
        ->putFiller('end', $request->input('end')));
       
        $result = $this->paginate($request->input('rows', 15));
        if (isset($result['meta']['pagination']['links'])) {
            unset($result['meta']['pagination']['links']);
        }
        return $result;
    }

    /**
     * Get data centered on existing bulletin's primary key id.
     *
     * @param string $id
     *
     * @return array
     * @throws \Exception
     */
    public function focusBulletin(string $id): array
    {
        $result = $this->find($id);

        return $result['data'];
    }

    /**
     * Disable focus bulletin.
     *
     * @param string $id
     *
     * @return void
     * @throws \Exception
     */
    public function focusDisable(string $id)
    {
        $this->update([
            'status' => 0
        ], $id);
    }

    /**
     * Enable focus bulletin.
     *
     * @param string $id
     *
     * @return void
     * @throws \Exception
     */
    public function focusEnable(string $id)
    {
        $this->update([
            'status' => 1
        ], $id);
    }
}
