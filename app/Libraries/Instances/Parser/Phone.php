<?php

namespace App\Libraries\Instances\Parser;

use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Final Class Phone.
 *
 * @package namespace App\Libraries\Instances\Parser;
 */
final class Phone
{
    /**
     * The phone area.
     *
     * @var string
     */
    private $area;

    /**
     * The phone type.
     *
     * @var string
     */
    private $type;

    /**
     * The phone format number.
     *
     * @var string
     */
    private $formatPhone;

    /**
     * The phone national number.
     *
     * @var string
     */
    private $nationalPhone;

    /**
     * The phone area code.
     *
     * @var string
     */
    private $code;

    /**
     * Phone constructor.
     *
     * @param string $phone
     * @param array $express
     * 
     * @return void
     */
    public function __construct(string $phone, array $express = [])
    {
        try {
            /* Area express */
            $express = array_unique(array_merge(['AUTO'],  $express));
            /* Check phone format */
            $phoneParse = PhoneNumber::make($phone, $express);
            $code = $phoneParse->formatInternational();
            $code = explode(' ', $code);
            $this->code = $code[0];
            $this->formatPhone = $phoneParse->formatE164();
            $this->nationalPhone = strtr($phoneParse->formatNational(), [' ' => '']);
            $this->area = $phoneParse->getCountry();
            $this->type = $phoneParse->getType();
        } catch (\Throwable $th) {
            return;
        }
    }

    /**
     * Parse phone baseinfo.
     *
     * @param string $phone
     * @param array $express
     * 
     * @return object
     */
    public static function parse(string $phone, array $express = []): object
    {
        return new self($phone, $express);
    }

    /**
     * Get the phone parse area.
     *
     * @return string|null
     */
    public function getArea(): ?string
    {
        return $this->area;
    }

    /**
     * Get the phone parse type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get the phone parse national number.
     *
     * @return string|null
     */
    public function getNational(): ?string
    {
        return $this->nationalPhone;
    }

    /**
     * Get the phone parse format number.
     *
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->formatPhone;
    }

    /**
     * Get the phone parse code.
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
}
