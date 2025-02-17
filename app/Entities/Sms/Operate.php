<?php

namespace App\Entities\Sms;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Libraries\Traits\Entity\Swap\TimeEquation;

/**
 * Class Operate.
 *
 * @package namespace App\Entities\Sms;
 */
class Operate extends Model implements Transformable
{
    use TransformableTrait;
    use TimeEquation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sourceable_type',
        'sourceable_id',
        'via',
        'notify_phone',
        'notify_action',
        'notify_message',
        'notify_result',
        'operate',
        'month',
        'created_at',
        'updated_at'
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
    protected $casts = [];

    /**
     * The other attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The SMS notification list.
     *
     * @var array
     */
    protected static $smsNotifications;

    /**
     * The SMS sourceable list.
     *
     * @var array
     */
    protected static $smsSourceables;

    /**
     * Get the order id prefix word.
     *
     * @return string
     */
    protected function getOrderPrefixWord()
    {
        return 'S';
    }

    /**
     * Get the parse serial info array.
     *
     * @param string $order
     * @return array|null
     */
    public function parseSerial(string $order): ?array
    {
        if (preg_match('/^' . $this->getOrderPrefixWord() . '[1-9]{1}[0-9]*$/i', $order)) { 
            $order = ltrim($order, $this->getOrderPrefixWord());
            $id = (int) substr($order, 0, -8);
            $year = (int) substr($order, -8, 4);
            $month = (int) substr($order, -4, 2);
            $day = (int) substr($order, -2, 2);
            $date = date('Ymd', mktime(0, 0, 0, $month, $day, $year));
            $recheckOrder = $id . $date;
            if ($id > 0 && $order == $recheckOrder) {
                return [
                    'id' => $id,
                    'date' => $date,
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ];
            }
        }
        return null;
    }

    /**
    * Get the SMS sourceable model list.
    *
    * @return array
    */
    public function getSmsSourceables(): array
    {
        /* Cache sourceables */
        if (! isset(self::$smsSourceables)) {
            self::$smsSourceables = config('sms.sourceables');
            self::$smsSourceables = (is_array(self::$smsSourceables) ? self::$smsSourceables : []);
            self::$smsSourceables = array_unique(collect(self::$smsSourceables)->map(function ($item, $key) use (&$codes) {
                if (isset($key[0], $item['model'][0], $item['status'])) {
                    return $item['model'];
                }
                return null;
            })->reject(function ($item) {
                return !isset($item);
            })->all());
        }
        return self::$smsSourceables;
    }
    
    /**
     * Check SMS sourceable type model return source type code.
     *
     * @param mixed $abstract
     *
     * @return string|null
     */
    public function getSmsSourceableType($abstract): ?string
    {
        /* Get abstract name */
        $abstract = (is_object($abstract) ? get_class($abstract) : (is_string($abstract) ? $abstract : null));
        if (is_string($abstract)) {
            /* Get sourceable type code */
            $source = array_search($abstract, $this->getSmsSourceables());

            return (is_string($source) ? $source : null);
        }
        return null;
    }

    /**
     * Check SMS sourceable type return model name.
     *
     * @param string $type
     *
     * @return string|null
     */
    public function getSmsSourceableModel(string $type): ?string
    {
        /* Get sourceable type model */
        return (isset($this->getSmsSourceables()[$type]) ? $this->getSmsSourceables()[$type] : null);
    }

    /**
    * Check that the model allowed by the SMS sourceable type.
    *
    * @param mixed $abstract
    *
    * @return bool
    */
    public function isSmsSourceableAllowed($abstract): bool
    {
        /* Get sourceable type code */
        if ($source = $this->getSmsSourceableType($abstract)) {
            /* Get sourceable status */
            return (config('sms.sourceables.' . $source . '.status') ? true : false);
        }
        return false;
    }

    /**
     * Get SMS serial number.
     *
     * @return string|null
     */
    public function getSerialAttribute(): ?string
    {
        if (isset($this->id, $this->created_at)) {
            return (string) ($this->getOrderPrefixWord() . $this->id . $this->asLocalTime($this->created_at)->format('Ymd'));
        }
        return null;
    }

    /**
     * Set notify message json.
     *
     * @param array $value
     * @return void
     */
    public function setNotifyMessageAttribute(array $value)
    {
        $this->attributes['notify_message'] = json_encode($value);
    }

    /**
     * Get notify message.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getNotifyMessageAttribute($value): ?array
    {
        return ($this->exists ? (isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Set notify result json.
     *
     * @param array $value
     * @return void
     */
    public function setNotifyResultAttribute(array $value)
    {
        $this->attributes['notify_result'] = json_encode($value);
    }

    /**
     * Get notify result.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getNotifyResultAttribute($value): ?array
    {
        return ($this->exists ? (isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Get SMS source id.
     *
     * @return string|null
     */
    public function getSourceIdAttribute(): ?string
    {
        if (isset($this->sourceable_type, $this->sourceable_id)) {
            return (string) (in_array('App\Libraries\Traits\Entity\Swap\Identity', class_uses($this->sourceable_type)) ? app($this->sourceable_type)->asPrimaryTid($this->sourceable_id) : $this->sourceable_id);
        }
        return null;
    }

    /**
     * Get SMS source type code.
     *
     * @return string|null
     */
    public function getSourceAttribute(): ?string
    {
        if (isset($this->sourceable_type)) {
            /* Get sourceable type code */
            $source = $this->getSmsSourceableType($this->sourceable_type);

            return (isset($source) ? $source : 'Undefined');
        }
        return null;
    }

    /**
     * Get SMS via telecomer code.
     *
     * @return string|null
     */
    public function getTelecomerAttribute(): ?string
    {
        if (isset($this->via)) {
            if (! isset(self::$smsNotifications)) {
                self::$smsNotifications = config('sms.notifications');
                self::$smsNotifications = (is_array(self::$smsNotifications) ? self::$smsNotifications : []);
            }
            $telecomer = array_search($this->via, self::$smsNotifications);
            /* Get via telecomer type code */
            return (is_string($telecomer) ? $telecomer : 'Undefined');
        }
        return null;
    }
}
