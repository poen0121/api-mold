<?php

namespace App\Presenters\Service;

use App\Transformers\Service\AuthTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AuthPresenter.
 *
 * @package namespace App\Presenters\Service;
 */
class AuthPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AuthTransformer();
    }
}
