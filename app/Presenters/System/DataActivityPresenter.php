<?php

namespace App\Presenters\System;

use App\Transformers\System\DataActivityTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class DataActivityPresenter.
 *
 * @package namespace App\Presenters\System;
 */
class DataActivityPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new DataActivityTransformer();
    }
}
