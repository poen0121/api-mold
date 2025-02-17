<?php

namespace App\Exceptions\Message;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class BulletinExceptionCode.
 *
 * @package App\Exceptions\Message
 */
class BulletinExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const CREATE_FAIL = 1;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::CREATE_FAIL => 'Failed to build notification.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
       return "App\\Exceptions\\Message\\BulletinExceptionCode";
    }
}
