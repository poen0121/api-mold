<?php

namespace App\Libraries\Verifiers;

/**
 * Final Class InstanceofVerifier.
 *
 * @package App\Libraries\Verifiers
 */
final class InstanceofVerifier
{

    /**
     * Validator message replacer.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array $parameters
     * @return string
     */
    public function replacer($message, $attribute, $rule, $parameters): string
    {
        return strtr($message, [
            // Custom validation message replacer field, field ':attribute' is not available by default.
        ]);
    }

    /**
     * Validate value.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @param  \Illuminate\Validation\Validator  $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        /* Register custom replacer */
        $validator->addReplacer('instanceof_verifier', 'App\Libraries\Verifiers\InstanceofVerifier@replacer');
        /* Check type */
        if (is_object($value)) {
            /* Check parameters */
            if (count($parameters) > 0 && ! in_array(get_class($value), $parameters, true)) {
                return false;
            }
            return true;
        }

        return false;
    }
}