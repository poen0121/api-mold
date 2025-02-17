<?php

namespace App\Exceptions\Sms;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class OperateExceptionCode.
 *
 * @package App\Exceptions\Sms
 */
class OperateExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const UNAUTHORIZED_OPERATION = 1;

    const SMS_SEND_FAILED = 2;

    const MISS_DATA = 3;

    const UNKNOWN_RESPONSE_TRAIT = 4;

    const DATA_FORMAT_ERROR = 5;

    const FUNCTION_UNIMPLEMENTED = 6;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::UNAUTHORIZED_OPERATION => 'Unauthorized operation, unusable source model.',
      self::SMS_SEND_FAILED => 'Telecomer notification class \'%telecomer%\' SMS sending failed.',
      self::MISS_DATA => 'Telecomer notification class \'%telecomer%\' missing data.',
      self::UNKNOWN_RESPONSE_TRAIT => 'Telecomer notification class \'%telecomer%\' unknown response trait.',
      self::DATA_FORMAT_ERROR => 'Telecomer notification class \'%telecomer%\' response data format error.',
      self::FUNCTION_UNIMPLEMENTED => 'Telecomer notification class \'%telecomer%\' toSms function is not implemented.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
       return "App\\Exceptions\\Sms\\OperateExceptionCode";
    }
}