<?php

namespace App\Exceptions\Feature;

use App\Libraries\Abstracts\Base\ExceptionCode as ExceptionCodeBase;

/**
 * Class ProviderExceptionCode.
 *
 * @package App\Exceptions\Feature
 */
class ProviderExceptionCode extends ExceptionCodeBase
{
    // Custom exception constant code
    const NORMAL = 0;

    const RELEASE_NON_EXIST = 1;

    const PROVIDER_NON_EXIST = 2;

    // Custom exception debug message
    const DEBUG_MESSAGE = [
      self::NORMAL => 'Please check for this exception.',
      self::RELEASE_NON_EXIST => 'The \'%feature%\' feature does not exist in the release.',
      self::PROVIDER_NON_EXIST => 'The \'%feature%\' feature does not exist in the provider.',
    ];

    /**
     * Specify exception converter by class name
     *
     * @return string
     */
    public function getExceptionConverter(): string
    {
        return "App\\Exceptions\\Feature\\ProviderExceptionCode";
    }
}
