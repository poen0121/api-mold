<?php

namespace App\Exceptions\System;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class AuthoritySnapshotExceptionCode.
 *
 * @package App\Exceptions\System
 */
class AuthoritySnapshotExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const NAME_EXISTS = 1;

    const ID_EXISTS = 2;
    
    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::NAME_EXISTS => 'The name has been registered.',
      self::ID_EXISTS => 'Snapshot id already exists.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\System\\AuthoritySnapshotExceptionCode";
    }
}