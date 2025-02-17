<?php

namespace App\Repositories\System;

use App\Libraries\Upgrades\BetterBaseRepository as BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\System\AuthoritySnapshotRepository;
use App\Entities\System\AuthoritySnapshot;
use App\Validators\System\AuthoritySnapshotValidator;
use App\Exceptions\System\AuthoritySnapshotExceptionCode as ExceptionCode;
use DB;

/**
 * Class AuthoritySnapshotRepositoryEloquent.
 *
 * @package namespace App\Repositories\System;
 */
class AuthoritySnapshotRepositoryEloquent extends BaseRepository implements AuthoritySnapshotRepository
{
    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        // Return empty is close presenter default transformer.
        return "App\\Presenters\\System\\AuthoritySnapshotPresenter";
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AuthoritySnapshot::class;
    }

    /**
     * Specify Validator class name
     *
     * @return string
     */
    public function validator()
    {
        // Return empty is to close the validator about create and update on the repository.
        return AuthoritySnapshotValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Get a list of data for existing data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function index(object $request): array
    {
        /* Criteria Index */
        $this->pushCriteria(app('App\Criteria\System\AuthoritySnapshot\IndexCriteria')
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
    * Build a authority snapshot.
    *
    * @param string $name
    * @param array $interface
    *
    * @return array
    * @throws \Exception
    */
    public function build(string $name, array $interface): array
    {
        try {
            $source = $this->model->create([
                'name' => $name,
                'authority' => $interface
            ]);
            /* Transformer */
            $transformer = app($this->presenter())->getTransformer();
            /* Array Info */
            $source = $transformer->transform($source);

            return $source;
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), 'authority_snapshots_name_unique') !== false) {
                throw new ExceptionCode(ExceptionCode::NAME_EXISTS);
            } elseif (strpos($th->getMessage(), 'PRIMARY') !== false) {
                throw new ExceptionCode(ExceptionCode::ID_EXISTS);
            }
            throw $th;
        }
    }

    /**
     * Rename focus snapshot.
     *
     * @param int $id
     * @param string $name
     *
     * @return void
     * @throws \Exception
     */
    public function focusRename(string $id, string $name)
    {
        try {
            $this->update([
                'name' => $name
            ], $id);
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), 'authority_snapshots_name_unique') !== false) {
                throw new ExceptionCode(ExceptionCode::NAME_EXISTS);
            } else {
                throw $th;
            }
        }
    }
}
