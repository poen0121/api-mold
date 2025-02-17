<?php

namespace App\Libraries\Traits\Entity\Column;

use App\Libraries\Instances\System\InterfaceScope;

trait Authority
{
    /**
     * Set authority json.
     *
     * @param array $value
     * @return void
     */
    public function setAuthorityAttribute(array $value)
    {
        $this->attributes['authority'] = json_encode($this->managedAuthority($value));
    }

    /**
     * Get authority.
     *
     * @param mixed $value
     * @return array|null
     */
    public function getAuthorityAttribute($value): ?array
    {
        return ($this->exists || isset($value) ? $this->allowedAuthority(isset($value) ? json_decode($value, true) : []) : null);
    }

    /**
     * Format authority codes by managed api interface code.
     *
     * @param array $authority
     * @return array
     */
    private function managedAuthority(array $authority = []): array
    {
        if (! in_array('*', $authority)) {
            if (count($authority) > 0) {
                /* Managed source */
                $codes = array_keys(InterfaceScope::managed());
                /* Format source codes */
                $codes = array_intersect($codes, $authority);
                /* Return format source codes */
                return array_values($codes);
            }

            return [];
        }
        return ['*'];
    }

    /**
     * Format authority codes by allowed api interface code.
     *
     * @param array $authority
     * @return array
     */
    private function allowedAuthority(array $authority = []): array
    {
        if (! in_array('*', $authority)) {
            $codes = [];
            if (count($authority) > 0) {
                /* Format managed codes */
                $codes = $this->managedAuthority($authority);
            }
            /* Push reserved source codes */
            $codes = array_merge($codes, array_keys(InterfaceScope::reserved()));
            /* Return format source codes */
            return array_values($codes);
        }
        return array_keys(InterfaceScope::all());
    }
}
