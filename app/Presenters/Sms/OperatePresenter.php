<?php

namespace App\Presenters\Sms;

use App\Transformers\Sms\OperateTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OperatePresenter.
 *
 * @package namespace App\Presenters\Sms;
 */
class OperatePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OperateTransformer();
    }
}
