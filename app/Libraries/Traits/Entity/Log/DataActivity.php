<?php

namespace App\Libraries\Traits\Entity\Log;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\ActivityLogStatus;

trait DataActivity
{
    use LogsActivity;

    /**
     * The log name.
     *
     * @var string
     */
    protected static $logName;

    /**
     * The log description.
     *
     * @var string
     */
    protected static $logDescription;

    /**
     * The log properties.
     *
     * @var array
     */
    protected static $logProperties;
    
    /**
     * The log only dirty.
     *
     * @var string
     */
    protected static $logOnlyDirty = true;

    /**
     * The log attributes.
     *
     * @var array
     */
    protected static $logAttributes = [
        '*'
    ];
    
    /**
     * Get the custom default log name.
     *
     * @return string
     */
    protected function getCustomDefaultLogName()
    {
        return 'model';
    }

    /**
     * Boot logs activity.
     * 
     * @return void
     */
    protected static function bootLogsActivity()
    {
        static::eventsToBeRecorded()->each(function ($eventName) {
            return static::$eventName(function (Model $model) use ($eventName) {
                if (! $model->shouldLogEvent($eventName)) {
                    return;
                }

                $logName = $model->getLogNameToUse($eventName);

                if (! in_array($logName, config('activitylog.available_log_name', [])) || in_array($logName, config('activitylog.ignore_log_name', []))) {
                    return;
                }

                $description = $model->getDescriptionForEvent($eventName);

                if ($description == '') {
                    return;
                }

                $attrs = $model->attributeValuesToBeLogged($eventName);

                if ($model->isLogEmpty($attrs) && ! $model->shouldSubmitEmptyLogs()) {
                    return;
                }

                $logger = app(ActivityLogger::class)
                    ->useLog($logName)
                    ->performedOn($model)
                    ->withProperties($attrs);

                if (method_exists($model, 'tapActivity')) {
                    $logger->tap([$model, 'tapActivity'], $eventName);
                }

                $logger->log($description);
            });
        });
    }

    /**
     * Check that the properties is empty.
     *
     * @param array $attrs
     * @return bool
     */
    public function isLogEmpty($attrs): bool
    {
        return empty($attrs ?? []);
    }

    /**
     * Attribute properties values to beLogged.
     *
     * @param string $processingEvent
     * @return array
     */
    public function attributeValuesToBeLogged(string $processingEvent): array
    {
        if (! count($this->attributesToBeLogged())) {
            return [];
        }
        if (! isset(static::$logProperties)) {
            $properties['attributes'] = static::logChanges(
                $processingEvent == 'retrieved'
                ? $this
                : (
                    $this->exists
                        ? $this->fresh() ?? $this
                        : $this
                )
            );

            if (static::eventsToBeRecorded()->contains('updated') && $processingEvent == 'updated') {
                $nullProperties = array_fill_keys(array_keys($properties['attributes']), null);

                $properties['old'] = array_merge($nullProperties, $this->oldAttributes);

                $this->oldAttributes = [];
            }

            if ($this->shouldLogOnlyDirty() && isset($properties['old'])) {
                $properties['attributes'] = array_udiff_assoc(
                    $properties['attributes'],
                    $properties['old'],
                    function ($new, $old) {
                    if ($old === null || $new === null) {
                        return $new === $old ? 0 : 1;
                    }

                    return $new <=> $old;
                }
                );
                $properties['old'] = collect($properties['old'])
                ->only(array_keys($properties['attributes']))
                ->all();
            }
        } else {
            $properties = static::$logProperties;
            static::$logProperties = null;
        }

        return $properties;
    }
    
    /**
     * Set log description for event.
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        $description = (isset(static::$logDescription[0]) ? static::$logDescription : $eventName);
        static::$logDescription = null;
        return $description;
    }
    
    /**
     * Get the log name for event.
     *
     * @param string $eventName
     * @return string
     */
    public function getLogNameToUse(string $eventName = ''): string
    {
        /* Push name */
        if (isset(static::$logName[0])) {
            $logName = static::$logName;
            static::$logName = null;
        }
        /* Custom default name */
        if (! isset($logName)) {
            $logName = (string) $this->getCustomDefaultLogName();
            $logName = (isset($logName[0]) ? $logName : null);
        }
        /* Default name */
        if (! isset($logName)) {
            $logName = config('activitylog.default_log_name', 'default');
        }

        return $logName;
    }

    /**
     * Push the log name.
     *
     * @param string $logName
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pushLogName(string $logName): object
    {
        static::$logName = $logName;

        return $this;
    }

    /**
     * Push the log description.
     *
     * @param string $logDescription
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pushLog(string $logDescription): object
    {
        static::$logDescription = $logDescription;

        return $this;
    }

    /**
     * Push the log properties.
     *
     * @param array $logProperties
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function pushProperties(array $logProperties): object
    {
        static::$logProperties = $logProperties;

        return $this;
    }
}
