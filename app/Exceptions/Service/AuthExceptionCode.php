<?php

namespace App\Exceptions\Service;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class AuthExceptionCode.
 *
 * @package App\Exceptions\Service
 */
class AuthExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const CLIENT_AUTH_FAIL = 1;

    const AUTH_FAIL = 2;

    const TOKEN_CREATE_FAIL = 3;

    const CLIENT_NON_EXIST = 4;

    const SERVICE_REJECTED = 5;

    const NO_PERMISSION = 6;

    const USER_AUTH_FAIL = 7;

    const TOKEN_OTHER_GUARD_AUTHORIZED = 8;

    const USER_NON_EXIST = 9;

    const SIGNATURE_CREATE_FAIL = 10;

    const SERVICE_EXPIRED = 11;

    const VERIFYCODE_CREATE_FAIL = 12;

    const OPERATION_DISABLED = 13;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
        self::NORMAL => 'Please check for this exception.',
        self::CLIENT_AUTH_FAIL => 'Verify the client credentials is incorrect.',
        self::AUTH_FAIL => 'Verify the credentials is incorrect.',
        self::TOKEN_CREATE_FAIL => 'The token authorization failed.',
        self::CLIENT_NON_EXIST => 'The client does not exist.',
        self::SERVICE_REJECTED => 'The client service has been deactivated.',
        self::NO_PERMISSION => 'The client service has been banned.',
        self::USER_AUTH_FAIL => 'Verify the user credentials is incorrect.',
        self::TOKEN_OTHER_GUARD_AUTHORIZED => 'The token has been authorized for other identity guards.',
        self::USER_NON_EXIST => 'The user does not exist.',
        self::SIGNATURE_CREATE_FAIL => 'The signature authorization failed.',
        self::SERVICE_EXPIRED => 'The client service has been expired.',
        self::VERIFYCODE_CREATE_FAIL => 'The verification code authorization failed.',
        self::OPERATION_DISABLED => 'Operation has been disabled.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\Service\\AuthExceptionCode";
    }
}
