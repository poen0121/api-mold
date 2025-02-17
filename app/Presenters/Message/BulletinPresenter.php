<?php

namespace App\Presenters\Message;

use App\Transformers\Message\BulletinTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BulletinPresenter.
 *
 * @package namespace App\Presenters\Message;
 */
class BulletinPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new BulletinTransformer();
    }
}
