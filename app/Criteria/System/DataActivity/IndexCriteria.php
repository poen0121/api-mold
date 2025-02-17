<?php

namespace App\Criteria\System\DataActivity;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Libraries\Traits\Info\Attach\Muppet;
use SystemParameter;
use Carbon;

/**
 * Class IndexCriteria.
 *
 * @package namespace App\Criteria\System\DataActivity;
 */
class IndexCriteria implements CriteriaInterface
{
    use Muppet;

    /**
     * Apply criteria in query repository
     *
     * @param object              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        // Muppet filler can be used for input.

        $months = SystemParameter::getValue('activity_query_limit_months');
        /* Limit table */
        $now = Carbon::now();
        /* Preliminary client end detetime */
        $endClient = Carbon::parse($model->asClientTime($now));
        /* Client dete end limit */
        $endLimit = $endClient->format('Y-m-d');
        /* Client dete start limit */
        $startLimit = $endClient->subMonth($months)->format('Y-m-01');
        /* Date range */
        $start = $this->getFiller('start');
        $end = $this->getFiller('end');
        /* Check query range */
        $startQuery = (isset($start) ? $start : $startLimit);
        $endQuery = (isset($end) ? $end : $endLimit);
        if (isset($start) && isset($end)) {
            $startQuery = ($start > $end ? $end : $start);
            $endQuery = ($start > $end ? $start : $end);
        }
        /* Date query */
        $startQuery = ($startQuery < $startLimit ? $startLimit : $startQuery);
        $startQuery = $model->asLocalTime($startQuery . ' 00:00:00');
        $startMonth = (int) Carbon::parse($startQuery)->format('m');
        $endQuery = $model->asLocalTime($endQuery . ' 23:59:59');
        /* Month range max 8 tables */
        $monthRange = [];
        $monthRange[] = (int) $now->format("m");
        if ($monthRange[0] !== $startMonth) {
            for ($i = 0; $i <= $months; $i++) {
                $now->subMonth();
                $monthTable = (int) $now->format("m");
                $monthRange[] = $monthTable;
                if ($monthTable === $startMonth) {
                    break;
                }
            }
        }
        /* Use limit month table */
        $quest = $model->whereIn('month', $monthRange);
        /* Start date query */
        $quest = $quest->where('created_at', '>=', $startQuery);
        /* End date query */
        $quest = $quest->where('created_at', '<=', $endQuery);
        /* Type */
        $types = $repository->types();
        if ($type = $this->getFiller('type')) {
            if (isset($types[$type])) {
                $quest = $quest->where('log_name', '=', $type);
            } else {
                $quest = $quest->where('log_name', '=', '');
            }
        } else {
            if (count($types) > 0) {
                $quest = $quest->whereIn('log_name', array_keys($types));
            } else {
                $quest = $quest->where('log_name', '=', '');
            }
        }
        /* Order by created_at */
        $creationSort = $this->getFiller('creation_sort');
        if (isset($creationSort)) {
            $quest = $quest->orderBy('created_at', $creationSort);
        }
        
        return (isset($quest) ? $quest : $model);
    }
}
