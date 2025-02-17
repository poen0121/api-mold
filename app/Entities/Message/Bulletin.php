<?php

namespace App\Entities\Message;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Libraries\Traits\Entity\Swap\TimeEquation;
use TokenAuth;
use Str;

/**
 * Class Bulletin.
 *
 * @package namespace App\Entities\Message;
 */
class Bulletin extends Model implements Transformable
{
    use TransformableTrait;
    use TimeEquation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_bulletins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'content',
        'notifiable_type',
        'released_at',
        'expired_at',
        'label',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * The other attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'released_at',
        'expired_at'
    ];

    /**
     * Eloquent bootstrap any operation.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /* Create id unique code */
            $model->{$model->getKeyName()} = Str::uuid()->toString();
        });
    }

    /**
     * The key incrementing mode.
     *
     * @var bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * The key type.
     *
     * @var string
     */
    public function getKeyType()
    {
        return 'string';
    }

/**
     * Set content json.
     *
     * @param array $value
     * @return void
     */
    public function setContentAttribute(array $value)
    {
        $this->attributes['content'] = json_encode($this->replaceContent($value));
    }

    /**
     * Get content.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getContentAttribute($value): ?array
    {
        return ($this->exists ? $this->allowedContent(isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Replace option to format content code.
     *
     * @param array $content
     * @return array
     */
    private function replaceContent(array $content = []): array
    {
        /* Format source options */
        $content = array_intersect_key($content, $this->getContentItem());
        /* Return source options */
        return array_merge(($this->content ?: []), $content);
    }

    /**
     * Format the content code with the allowed options.
     *
     * @param array $content
     * @return array
     */
    private function allowedContent(array $content = []): array
    {
        /* Format source options */
        $content = array_intersect_key($content, $this->getContentItem());
        /* Return source options */
        return array_merge($this->getContentItem(), $content);
    }

    /**
     * Get content item list.
     *
     * @return array
     */
    public function getContentItem(): array
    {
        return config('notice.bulletin_content', []);
    }

    /**
     * Get notify type code.
     *
     * @return string|null
     */
    public function getNotifyTypeAttribute(): ?string
    {
        if (isset($this->notifiable_type)) {

            /* Get notifiable type code */
            $source = TokenAuth::getAuthGuard($this->notifiable_type);

            return (isset($source) ? $source : 'Undefined');
        }
        return null;
    }

}
