<?php

namespace App\Libraries\Traits\Info\Format;

trait Response
{
    /**
     * Response the success message.
     *
     * @param array $data
     * @return array
     */
    public static function success(array $data = []): array
    {
        /* Set the response format label */
        return [
            'success' => true,
            'data' => $data
        ];
    }

    /**
     * Response the failure message.
     *
     * @param array $data
     * @return array
     */
    public static function failure(array $data = []): array
    {
        /* Set the response format label */
        return [
            'success' => false,
            'data' => $data
        ];
    }
}