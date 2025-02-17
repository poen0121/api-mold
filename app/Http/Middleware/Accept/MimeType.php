<?php

namespace App\Http\Middleware\Accept;

use App\Exceptions\System\AcceptExceptionCode as ExceptionCode;
use Closure;

class MimeType
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* Get the request content type */
        $mimeType = $request->header('content-type');
        if (false !== $pos = strpos($mimeType, ';')) {
            $mimeType = substr($mimeType, 0, $pos);
        }
        /* Accept content types for method */
        $method = $request->method();
        switch ($method) {
            case 'POST':
                $types = [
                    'application/x-www-form-urlencoded',
                    'multipart/form-data'
                ];
            break;
            case 'PUT':
                $types = [
                    'application/x-www-form-urlencoded'
                ];
                if (extension_loaded('apfd')) {
                    $types[] = 'multipart/form-data';
                }
            break;
            case 'PATCH':
                $types = [
                    'application/x-www-form-urlencoded'
                ];
                if (extension_loaded('apfd')) {
                    $types[] = 'multipart/form-data';
                }
            break;
            default:
                $types = null;
            break;
        }
        /* Accept content type */
        if (isset($types) && ! in_array($mimeType, $types, true)) {
            throw new ExceptionCode(ExceptionCode::UNSUPPORTED_MEDIA_TYPE);
        }

        return $next($request);
    }
}