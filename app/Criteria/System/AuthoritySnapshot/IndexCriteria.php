<?php

namespace App\Criteria\System\AuthoritySnapshot;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Libraries\Traits\Info\Attach\Muppet;

/**
 * Class IndexCriteria.
 *
 * @package namespace App\Criteria\System\AuthoritySnapshot;
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

        /* Date range */
        $start = $this->getFiller('start');
        $end = $this->getFiller('end');
        /* Check query range */
        $startQuery = (isset($start) ? $start : null);
        $endQuery = (isset($end) ? $end : null);
        if (isset($start) && isset($end)) {
            $startQuery = ($start > $end ? $end : $start);
            $endQuery = ($start > $end ? $start : $end);
        }
        /* Start date query */
        if (isset($startQuery)) {
            $startQuery = $model->asLocalTime($startQuery . ' 00:00:00');
            $quest = $model->where('created_at', '>=', $startQuery);
        }
        /* End date query */
        if (isset($endQuery)) {
            $endQuery = $model->asLocalTime($endQuery . ' 23:59:59');
            if (isset($quest)) {
                $quest = $quest->where('created_at', '<=', $endQuery);
            } else {
                $quest = $model->where('created_at', '<=', $endQuery);
            }
        }
        /* Order by created_at */
        $creationSort = $this->getFiller('creation_sort');
        if (isset($creationSort)) {
            if (isset($quest)) {
                $quest = $quest->orderBy('created_at', $creationSort);
            } else {
                $quest = $model->orderBy('created_at', $creationSort);
            }
        }
        
        return (isset($quest) ? $quest : $model);
    }
}
