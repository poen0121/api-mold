<?php

namespace App\Exceptions\System;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class CellExceptionCode.
 *
 * @package App\Exceptions\System
 */
class CellExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const VALIDATION = 1;

    const DATA_FORMAT_ERROR = 2;

    const FUNCTION_UNIMPLEMENTED = 3;
    
    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::VALIDATION => 'The data of the given component class \'%cell%\' is invalid.',
      self::DATA_FORMAT_ERROR => 'Component class \'%cell%\' response data format error.',
      self::FUNCTION_UNIMPLEMENTED => 'Component class \'%cell%\' handle function is not implemented.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
       return "App\\Exceptions\\System\\CellExceptionCode";
    }
}
