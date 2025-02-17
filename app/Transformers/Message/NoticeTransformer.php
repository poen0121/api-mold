<?php

namespace App\Transformers\Message;

use League\Fractal\TransformerAbstract;
use App\Libraries\Instances\Swap\Matrix;
use App\Libraries\Instances\Swap\TimeDisplay;
use Lang;

/**
 * Class NoticeTransformer.
 *
 * @package namespace App\Transformers\Message;
 */
class NoticeTransformer extends TransformerAbstract
{
    /**
     * Transform the Notice entity.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function transform($model)
    {
        $translate = in_array($model->data['type'], config('notice.translatable', []));
        $data = collect($model->data)->map(function ($item, $key) use ($translate) {
            if ($translate && $key == 'subject') {
                return Lang::dict('notice', 'subject.' . $item, $item);
            } elseif ($key == 'content') {
                if ($translate) {
                    $dicts = Lang::dict('notice', $key, []);
                    Matrix::indexReplace($item, $dicts);
                }
                return Matrix::null2empty($item);
            } else {
                return (isset($item) ? $item : '');
            }
        })->all();
        $data['type_name'] = Lang::dict('notice', 'type.' . $data['type'], $data['type']);
        /* Source */
        return [
            'id' => $model->id,
            'notice' => $data,
            'read_at' => (isset($model->read_at) ? TimeDisplay::asClientTime($model->read_at)->toDateTimeString() : ''),
            'created_at' => TimeDisplay::asClientTime($model->created_at)->toDateTimeString()
        ];
    }
}
