<?php

namespace App\Presenters\System;

use App\Transformers\System\AuthoritySnapshotTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class AuthoritySnapshotPresenter.
 *
 * @package namespace App\Presenters\System;
 */
class AuthoritySnapshotPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AuthoritySnapshotTransformer();
    }
}
