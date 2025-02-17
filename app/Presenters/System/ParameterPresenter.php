<?php

namespace App\Presenters\System;

use App\Transformers\System\ParameterTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ParameterPresenter.
 *
 * @package namespace App\Presenters\System;
 */
class ParameterPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ParameterTransformer();
    }
}
