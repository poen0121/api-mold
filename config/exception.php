<?php
return [

    /*
     |--------------------------------------------------------------------------
     | Exception Converter Thrown Code Swap Display Error Code
     |--------------------------------------------------------------------------
     |
     | Set a list of exception classes error code.
     |
     | Example :
     | Exception Class => [
     |    Exception thrown error code => Exception display error code,
     | ],
     */

    Illuminate\Auth\Access\AuthorizationException::class => [
        0 => 10010000,
    ],
    Illuminate\Auth\AuthenticationException::class => [
        0 => 10020000,
    ],
    Illuminate\Contracts\Encryption\DecryptException::class => [
        0 => 10030000,
    ],
    Illuminate\Database\Eloquent\ModelNotFoundException::class => [
        0 => 10040000,
    ],
    Illuminate\Database\QueryException::class => [
        0 => 10050000,
    ],
    Illuminate\Validation\ValidationException::class => [
        0 => 10060000,
    ],
    Libphonenumber\NumberParseException::class => [
        0 => 10070000,
        1 => 10070001,
        2 => 10070002,
        3 => 10070003,
        4 => 10070004,
    ],
    Propaganistas\LaravelPhone\Exceptions\CountryCodeException::class => [
        0 => 10070005,
    ],
    Propaganistas\LaravelPhone\Exceptions\InvalidParameterException::class => [
        0 => 10070006,
    ],
    Propaganistas\LaravelPhone\Exceptions\NumberFormatException::class => [
        0 => 10070007,
    ],
    Propaganistas\LaravelPhone\Exceptions\NumberParseException::class => [
        0 => 10070008,
    ],
    Prettus\Validator\Exceptions\ValidatorException::class => [
        0 => 10080000,
    ],
    Symfony\Component\Debug\Exception\FatalThrowableErrorException::class => [
        0 => 10090000,
    ],
    Symfony\Component\HttpKernel\Exception\HttpException::class => [
        0 => 10100000,
    ],
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => [
        0 => 10110000,
    ],
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class => [
        0 => 10120000,
    ],
    Tymon\JWTAuth\Exceptions\InvalidClaimException::class => [
        0 => 10130000,
    ],
    Tymon\JWTAuth\Exceptions\JWTException::class => [
        0 => 10140000,
    ],
    Tymon\JWTAuth\Exceptions\PayloadException::class => [
        0 => 10150000,
    ],
    Tymon\JWTAuth\Exceptions\TokenBlacklistedException::class => [
        0 => 10160000,
    ],
    Tymon\JWTAuth\Exceptions\TokenExpiredException::class => [
        0 => 10170000,
    ],
    Tymon\JWTAuth\Exceptions\TokenInvalidException::class => [
        0 => 10180000,
    ],
    Tymon\JWTAuth\Exceptions\UserNotDefinedException::class => [
        0 => 10190000,
    ],
    App\Exceptions\System\AcceptExceptionCode::class => [
        0 => 20010000,
        1 => 20010001,
        2 => 20010002,
        3 => 20010003,
        4 => 20010004,
    ],
    App\Exceptions\Service\AuthExceptionCode::class => [
        0 => 20020000,
        1 => 20020001,
        2 => 20020002,
        3 => 20020003,
        4 => 20020004,
        5 => 20020005,
        6 => 20020006,
        7 => 20020007,
        8 => 20020008,
        9 => 20020009,
        10 => 20020010,
        11 => 20020011,
        12 => 20020012,
        13 => 20020013,
    ],
    App\Exceptions\Service\ClientExceptionCode::class => [
        0 => 30010000,
        1 => 30010001,
        2 => 30010002,
        3 => 30010003,
        4 => 30010004,
    ],
    App\Exceptions\System\DataActivityExceptionCode::class => [
        0 => 30020000,
    ],
    App\Exceptions\System\LanguageExceptionCode::class => [
        0 => 30030000,
    ],
    App\Exceptions\System\ParameterExceptionCode::class => [
        0 => 30040000,
        1 => 30040001,
        2 => 30040002,
    ],
    App\Exceptions\Feature\ProviderExceptionCode::class => [
        0 => 30050000,
        1 => 30050001,
        2 => 30050002,
    ],
    App\Exceptions\System\InterfaceExceptionCode::class => [
        0 => 30060000,
    ],
    App\Exceptions\System\AuthorityExceptionCode::class => [
        0 => 30070000,
        1 => 30070001,
        2 => 30070002,
        3 => 30070003,
        4 => 30070004,
        5 => 30070005,
    ],
    App\Exceptions\Message\NoticeExceptionCode::class => [
        0 => 30080000,
        1 => 30080001,
        2 => 30080002,
        3 => 30080003,
    ],
    App\Exceptions\System\AuthoritySnapshotExceptionCode::class => [
        0 => 30090000,
        1 => 30090001,
        2 => 30090002,
    ],
    App\Exceptions\Sms\OperateExceptionCode::class => [
        0 => 30100000,
        1 => 30100001,
        2 => 30100002,
        3 => 30100003,
        4 => 30100004,
        5 => 30100005,
        6 => 30100006,
    ],
    App\Exceptions\Message\BulletinExceptionCode::class => [
        0 => 30110000,
        1 => 30110001,
    ],
    App\Exceptions\System\CellExceptionCode::class => [
        0 => 30120000,
        1 => 30120001,
        2 => 30120002,
        3 => 30120003,
    ],
    //:end-generating:
];
