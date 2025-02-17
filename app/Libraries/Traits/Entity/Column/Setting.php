<?php

namespace App\Libraries\Traits\Entity\Column;

trait Setting
{
    /**
     * Set setting json.
     *
     * @param array $value
     * @return void
     */
    public function setSettingAttribute(array $value)
    {
        $this->attributes['setting'] = json_encode($this->replaceSetting($value));
    }

    /**
     * Get setting.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getSettingAttribute($value): ?array
    {
        return ($this->exists || isset($value) ? $this->allowedSetting(isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Replace option to format setting code.
     *
     * @param array $setting
     * @return array
     */
    private function replaceSetting(array $setting = []): array
    {
        /* Format source options */
        $setting = array_intersect_key($setting, $this->getSettingOptions());
        /* Return source options */
        return array_merge(($this->setting ?: []), $setting);
    }

    /**
     * Format the setting code with the allowed options.
     *
     * @param array $setting
     * @return array
     */
    private function allowedSetting(array $setting = []): array
    {
        /* Format source options */
        $setting = array_intersect_key($setting, $this->getSettingOptions());
        /* Return source options */
        return array_merge($this->getSettingOptions(), $setting);
    }

    /**
     * Get options list.
     *
     * @return array
     */
    public function getSettingOptions(): array
    {
        /* Set options list
         * return [
         *    option key => default value,
         * ]
         */
        return [
            // 'mode' => 0
        ];
    }
}
