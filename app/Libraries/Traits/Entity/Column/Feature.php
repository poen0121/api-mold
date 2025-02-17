<?php

namespace App\Libraries\Traits\Entity\Column;

use App\Libraries\Instances\Feature\Provider;
use App\Exceptions\Feature\ProviderExceptionCode as ExceptionCode;

trait Feature
{
    /**
     * The feature deploy list.
     *
     * @var array
     */
    private static $featureDeploies = [];

    /**
     * The feature handle list.
     *
     * @var array
     */
    private static $featureHandles = [];

    /**
     * Set feature json.
     *
     * @param array $value
     * @return void
     */
    public function setFeatureAttribute(array $value)
    {
        $this->attributes['feature'] = json_encode($value);
    }

    /**
     * Get feature.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getFeatureAttribute($value): ?array
    {
        return ($this->exists || isset($value) ? (isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Trigger feature deploy script.
     *
     * @param string $code
     * @param array $requestInput
     * @param array $internalInput
     * @param boolean $onlyReleaseSource
     * @return object
     * @throws \Exception
     */
    public static function triggerFeatureDeploy(string $code, array $requestInput = [], array $internalInput = [], bool $onlyReleaseSource = true): object
    {
        /* Get features */
        $features = ($onlyReleaseSource ? Provider::getRelease(new self) : Provider::getProvider());
        /* Append feature deploies */
        if (isset($features[$code])) {
            $class = $features[$code];
            $obj = app($class);
            /* Request release input */
            foreach ($requestInput as $key => $val) {
                $obj->setInput($key, $val, true);
            }
            /* Internal input ignore release */
            foreach ($internalInput as $key => $val) {
                $obj->setInput($key, $val);
            }
            /* Get deployment */
            $featureDeploies = [];
            $featureDeploies['code'] = $code;
            $featureDeploies['deploy'] = $obj->getDeployment();
            self::$featureDeploies[] = $featureDeploies;
        } else {
            throw new ExceptionCode(($onlyReleaseSource ? ExceptionCode::RELEASE_NON_EXIST : ExceptionCode::PROVIDER_NON_EXIST), [
                '%feature%' => $code
            ], [
                '%feature%' => $code
            ]);
        }

        return new static;
    }

    /**
     * Get the feature deploy script running result.
     *
     * @return array
     */
    public static function gatherFeatureDeploy(): array
    {
        $deploies = self::$featureDeploies;
        /* Reset */
        self::$featureDeploies = [];

        return $deploies;
    }

    /**
     * Trigger feature handle script.
     *
     * @param string $code
     * @param array $requestInput
     * @param array $internalInput
     * @param boolean $onlyReleaseSource
     * @return object
     * @throws \Exception
     */
    public static function triggerFeatureHandle(string $code, array $requestInput = [], array $internalInput = [], bool $onlyReleaseSource = false): object
    {
        /* Get features */
        $features = ($onlyReleaseSource ? Provider::getRelease(new self) : Provider::getProvider());
        /* Append feature handle */
        if (isset($features[$code])) {
            $class = $features[$code];
            $obj = app($class);
            /* Request release input */
            foreach ($requestInput as $key => $val) {
                $obj->setInput($key, $val, true);
            }
            /* Internal input ignore release */
            foreach ($internalInput as $key => $val) {
                $obj->setInput($key, $val);
            }
            /* Get handle result */
            $featureHandles = [];
            $featureHandles['code'] = $code;
            $featureHandles['handle'] = $obj->run();
            self::$featureHandles[] = $featureHandles;
        } else {
            throw new ExceptionCode(($onlyReleaseSource ? ExceptionCode::RELEASE_NON_EXIST : ExceptionCode::PROVIDER_NON_EXIST), [
                '%feature%' => $code
            ], [
                '%feature%' => $code
            ]);
        }

        return new static;
    }

    /**
     * Get the feature handle script running result.
     *
     * @return array
     */
    public static function gatherFeatureHandle(): array
    {
        $handles = self::$featureHandles;
        /* Reset */
        self::$featureHandles = [];

        return $handles;
    }
}
