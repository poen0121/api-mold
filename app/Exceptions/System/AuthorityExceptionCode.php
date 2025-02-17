<?php

namespace App\Exceptions\System;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class AuthorityExceptionCode.
 *
 * @package App\Exceptions\System
 */
class AuthorityExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const NO_PERMISSION = 1;

    const UNSPECIFIED_AUTHORITY = 2;

    const INVALID_SNAPSHOT = 3;

    const INVALID_INTERFACE_CODE = 4;

    const INOPERABLE_USER = 5;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::NO_PERMISSION => 'No allowed access.',
      self::UNSPECIFIED_AUTHORITY => 'No authority item specified.',
      self::INVALID_SNAPSHOT => 'Invalid authority snapshot id.',
      self::INVALID_INTERFACE_CODE => 'Invalid interface code in \'%code%\'.',
      self::INOPERABLE_USER => 'Operation prohibited by invalid user object.'
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\System\\AuthorityExceptionCode";
    }
}