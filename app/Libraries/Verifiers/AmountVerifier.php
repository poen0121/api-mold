<?php

namespace App\Libraries\Verifiers;

/**
 * Final Class AmountVerifier.
 *
 * @package App\Libraries\Verifiers
 */
final class AmountVerifier
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
            ':min' => $parameters[0],
            ':max' => $parameters[1]
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
     * @throws \Exception
     */
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        /* Register custom replacer */
        $validator->addReplacer('amount_verifier', 'App\Libraries\Verifiers\AmountVerifier@replacer');
        /* Check parameters */
        if (! isset($parameters[0]) || ! isset($parameters[1])) {
            throw new \Exception('Validator Amount: No range has been specified.');
        }
        if (count($parameters) > 2) {
            throw new \Exception('Validator Amount: Include invalid parameter definitions.');
        }
        /* Check parameters format */
        if (! preg_match('/^(0|[1-9]{1}[0-9]*){1}(\.[0-9]+)?$/', $parameters[0], $min) || ! preg_match('/^(0|[1-9]{1}[0-9]*){1}(\.[0-9]+)?$/', $parameters[1], $max)) {
            throw new \Exception('Validator Amount: Incorrect format of specified range interval value.');
        }
        /* Get decimal */
        $minDecimal = (isset($min[2]) ? strlen($min[2]) : 0);
        $maxDecimal = (isset($max[2]) ? strlen($max[2]) : 0);
        $decimal = ($minDecimal > $maxDecimal ? $minDecimal : $maxDecimal);
        $decimal = ($decimal > 0 ? ($decimal - 1) : $decimal);
        /* Check value format */
        if (preg_match(($decimal > 0 ? '/^(0|[1-9]{1}[0-9]*){1}(\.[0-9]{1,' . $decimal . '})?$/' : '/^(0|[1-9]{1}[0-9]*)+$/'), $value)) {
            /* Check value range */
            return ((bccomp($value, $min[0], $decimal) > -1 && bccomp($value, $max[0], $decimal) < 1) || (bccomp($value, $min[0], $decimal) < 1 && bccomp($value, $max[0], $decimal) > -1) ? true : false);
        }
        return false;
    }
}