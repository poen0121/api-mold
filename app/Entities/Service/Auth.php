<?php

namespace App\Entities\Service;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Libraries\Traits\Entity\Auth\JWT;
use App\Libraries\Traits\Entity\Log\DataActivity;
use App\Libraries\Traits\Entity\Swap\TimeEquation;
use App\Libraries\Traits\Entity\Swap\Identity;
use Illuminate\Notifications\Notifiable;
use App\Exceptions\Service\AuthExceptionCode as ExceptionCode;
use Lang;
use Str;
use Carbon;

/**
 * Class Auth.
 *
 * @package namespace App\Entities\Service;
 */
class Auth extends Authenticatable implements Transformable, JWTSubject
{
    use TransformableTrait;
    use JWT;
    use DataActivity;
    use TimeEquation;
    use Identity;
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_auth',
        'client_secret',
        'key',
        'name',
        'ban',
        'status',
        'expired_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'key',
        'client_secret'
    ];

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
            $model->client_id = Str::uuid()->getHex();
            $model->key = hash_hmac('md5', $model->client_id, microtime());
            $model->client_secret = hash_hmac('md5', $model->client_id, $model->key);
            /* Set the ban number */
            $model->ban = (isset($model->ban) ? $model->ban : 1);
            /* Set the status */
            $model->status = (isset($model->status) ? $model->status : 1);
        });
    }

    /**
     * Get the transform id suffix length.
     *
     * @return int
     */
    protected function getPrimaryTidSuffixLength(): int
    {
        return 12;
    }

    /**
     * Set the client secret.
     *
     * @param string $value
     *
     * @return void
     */
    public function setClientSecretAttribute(string $value)
    {
        $this->attributes['client_secret'] = bcrypt($value);
    }

    /**
     * Get the real client secret.
     *
     * @param mixed $value
     *
     * @return string|null
     */
    public function getRealClientSecretAttribute($value): ?string
    {
        if (isset($this->client_id , $this->key)) {
            return hash_hmac('md5', $this->client_id, $this->key);
        }
        return null;
    }

    /**
    * Get the app id
    *
    * @return string|null
    */
    public function getAppIdAttribute(): ?string
    {
        return ($this->exists ? $this->tid : null);
    }

    /**
     * Get the name of the login identifier for the user.
     *
     * @return string
     */
    public static function getLoginIdentifierName(): string
    {
        return 'client_id';
    }

    /**
     * Get the auth password for the user.
     *
     * @return string
     */
    public function getAuthPassword(): string
    {
        return $this->client_secret;
    }

    /**
     * Verify if the other status of the user is abnormal.
     *
     * @return void
     * @throws \Exception
     */
    public function verifyHoldStatusOnFail()
    {
        if ($this->exists) {
            if (! $this->status) {
                throw new ExceptionCode(ExceptionCode::SERVICE_REJECTED);
            } elseif ($this->expired_at && $this->asLocalTime($this->expired_at) < Carbon::now()) {
                throw new ExceptionCode(ExceptionCode::SERVICE_EXPIRED);
            }
            // You can use the $this->isCaptureRoute($specify = []) function to do some masking
            // You can set $this->getDefaultCaptureRoute() to affect $this->isCaptureRoute() function
            // Verify user other status throw exception
        }
    }
    
    /**
     * Get ban description
     *
     * @return string|null
     */
    public function getDescriptionAttribute(): ?string
    {
        if (isset($this->ban)) {
            $object = config('ban.release.' . $this->ban . '.description');
            return Lang::dict('ban', 'release.' . $object, 'Undefined');
        }
        return null;
    }
}
