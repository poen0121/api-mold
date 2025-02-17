<?php

namespace App\Exceptions\Service;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class ClientExceptionCode.
 *
 * @package App\Exceptions\Service
 */
class ClientExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const BAN_NUMBER_DISABLED = 1;

    const SERVICE_NAME_EXISTS = 2;

    const SERVICE_ID_EXISTS = 3;

    const INOPERABLE_CLIENT = 4;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::BAN_NUMBER_DISABLED => 'Ban number disabled.',
      self::SERVICE_NAME_EXISTS => 'Service name already exists.',
      self::SERVICE_ID_EXISTS => 'Service client id already exists.',
      self::INOPERABLE_CLIENT => 'Operation prohibited by invalid client service object.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\Service\\ClientExceptionCode";
    }
}
