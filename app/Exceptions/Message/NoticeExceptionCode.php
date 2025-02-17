<?php

namespace App\Exceptions\Message;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class NoticeExceptionCode.
 *
 * @package App\Exceptions\Message
 */
class NoticeExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const DISALLOWED_SEND_OBJECT = 1;

    const INVALID_SENDER = 2;

    const INVALID_NOTIFICATION_ID = 3;
    
    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::DISALLOWED_SEND_OBJECT => 'Disallowed send object.',
      self::INVALID_SENDER => 'Invalid sender.',
      self::INVALID_NOTIFICATION_ID => 'Invalid notification id in \'%id%\'.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\Message\\NoticeExceptionCode";
    }
}
