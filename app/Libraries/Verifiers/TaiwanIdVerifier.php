<?php

namespace App\Libraries\Verifiers;

/**
 * Final Class TaiwanIdVerifier.
 *
 * @package App\Libraries\Verifiers
 */
final class TaiwanIdVerifier
{

    /**
     * The number which the first letter represents.
     *
     * @var array
     */
    protected $locations = [
        'A' =>  1, 'B' => 10, 'C' => 19, 'D' => 28, 'E' => 37, 'F' => 46, 'G' => 55,
        'H' => 64, 'I' => 39, 'J' => 73, 'K' => 82, 'L' =>  2, 'M' => 11, 'N' => 20,
        'O' => 48, 'P' => 29, 'Q' => 38, 'R' => 47, 'S' => 56, 'T' => 65, 'U' => 74,
        'V' => 83, 'W' => 21, 'X' =>  3, 'Y' => 12, 'Z' => 30
    ];

    /**
     * The weights which the every numbers represents.
     *
     * @var array
     */
    protected $weights = [8, 7, 6, 5, 4, 3, 2, 1, 1];

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
        $validator->addReplacer('taiwan_id_verifier', 'App\Libraries\Verifiers\TaiwanIdVerifier@replacer');
        /* Check ID number format. */
        if (!(preg_match('/(^[A-Z][0-9]{9})/u', $value) === 1)) {
            return false;
        }

        $valueChars = str_split($value);

        if (!in_array($valueChars[1], [1, 2])) {
            return false;
        }

        $count = $this->locations[$valueChars[0]];
        foreach ($this->weights as $i => $weight) {
            $count += $valueChars[$i + 1] * $weight;
        }

        return ($count % 10 === 0) ? true : false;
    }
}