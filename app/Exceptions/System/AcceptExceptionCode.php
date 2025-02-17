<?php

namespace App\Exceptions\System;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class AcceptExceptionCode.
 *
 * @package App\Exceptions\System
 */
class AcceptExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const UNSUPPORTED_MEDIA_TYPE = 1;

    const UNACCEPTABLE_RESPONSE_TYPE = 2;

    const TIMEZONE_BAD = 3;

    const BAN_TOKEN = 4;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::UNSUPPORTED_MEDIA_TYPE => 'The requested media type is not supported.',
      self::UNACCEPTABLE_RESPONSE_TYPE => 'The requested response type is not supported.',
      self::TIMEZONE_BAD => 'The requested service time zone is unknown or wrong.',
      self::BAN_TOKEN => 'The requested authorization is not allowed.'
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\System\\AcceptExceptionCode";
    }
}