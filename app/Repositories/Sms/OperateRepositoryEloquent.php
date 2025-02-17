<?php

namespace App\Repositories\Sms;

use App\Libraries\Upgrades\BetterBaseRepository as BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Sms\OperateRepository;
use App\Entities\Sms\Operate;
use App\Validators\Sms\OperateValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SystemParameter;
use Carbon;

/**
 * Class OperateRepositoryEloquent.
 *
 * @package namespace App\Repositories\Sms;
 */
class OperateRepositoryEloquent extends BaseRepository implements OperateRepository
{
    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        // Return empty is close presenter default transformer.
        return "App\\Presenters\\Sms\\OperatePresenter";
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Operate::class;
    }

    /**
     * Specify Validator class name
     *
     * @return string
     */
    public function validator()
    {
        // Return empty is to close the validator about create and update on the repository.
        return OperateValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Get a list of data for existing SMS logs.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     * @throws \Exception
     */
    public function index(object $request): array
    {
        /* Criteria Index */
        $this->pushCriteria(app('App\Criteria\Sms\Log\IndexCriteria')
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
     * Get data centered on existing SMS serial primary key id.
     *
     * @param string $serial
     * @return array
     * @throws \Exception
     */
    public function focusSerial(string $serial): array
    {
        if ($parseSerial = $this->model->parseSerial($serial)) {
            $months = SystemParameter::getValue('sms_query_limit_months');
            /* Check dete start limit */
            if ($parseSerial['date'] >= Carbon::parse(Carbon::now()->subMonth($months)->format('Y-m-01 00:00:00'))->subDay()->format('Ymd')) {
                $data = $this->model->where('month', $parseSerial['month'])->where('id', $parseSerial['id'])->first();
                /* Check serial */
                if (isset($data) && $data->serial === $serial) {
                    /* Transformer */
                    $transformer = app($this->presenter())->getTransformer();
                    /* Data */
                    return $transformer->transform($data);
                }
            }
        }
        throw new ModelNotFoundException('No query results for model [' . Operate::class . '] ' . $serial);
    }
}
