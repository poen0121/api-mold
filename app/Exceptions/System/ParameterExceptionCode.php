<?php

namespace App\Exceptions\System;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class ParameterExceptionCode.
 *
 * @package App\Exceptions\System
 */
class ParameterExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const REWRITE_PARAMETER_VALUE_FAIL = 1;

    const UNCAPTURED_PARAMETER = 2;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::REWRITE_PARAMETER_VALUE_FAIL => 'System parameter value rewriting failed.',
      self::UNCAPTURED_PARAMETER => 'Uncaptured system parameter \'%slug%\' .'
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\System\\ParameterExceptionCode";
    }
}
