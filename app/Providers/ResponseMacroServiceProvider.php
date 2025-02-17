<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use App\Exceptions\Service\AuthExceptionCode;
use Illuminate\Http\Request;
use Throwable;
use Lang;
use Str;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * function success
         *
         * Get the response json for success.
         *
         * @param array $data
         * @param int $status
         * @param array $headers
         * @param int $options
         *
         * @return \Illuminate\Http\JsonResponse
         */
        Response::macro('success', function (array $data = [], $status = 200, array $headers = [], $options = 0) {
            $subclass = get_called_class();
            /* Transform data */
            if (class_exists($subclass) && method_exists($subclass, 'transform')) {
                app($subclass)->transform($data);
            }
            /* Set the response format data label */
            if (!isset($data['data']) && count($data) > 0) {
                $data = [
                    'data' => $data
                ];
            }
            /* Remove the data format success label */
            if (array_key_exists('success', $data)) {
                unset($data['success']);
            }
            /* Attach data */
            $data = array_merge([
                'success' => true
            ], $data);
            /* Get response refresh authorization token */
            $token = (defined('REFRESH_TOKEN_BY_SUCCESS') ? REFRESH_TOKEN_BY_SUCCESS : null);
            if (isset($token)) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            /* Response success */
            return new JsonResponse($data, $status, $headers, $options);
        });

        /**
         * function error
         *
         * Get the response json for exception.
         *
         * @param \Illuminate\Http\Request $request
         * @param \Throwable $exception
         *
         * @return \Illuminate\Http\JsonResponse|Illuminate\Support\Facades\Response
         */
        Response::macro('error', function (Request $request, Throwable $exception) {
            /* Source information */
            $objectParents = class_parents($exception); // Exception parent list
            $objectParents = array_values($objectParents);
            $object = get_class($exception); // Exception object name
            /* Exception description */
            $objectMethods = get_class_methods($object);
            if (in_array('errors', $objectMethods)) {
                $description = $exception->errors();
            } elseif (in_array('getMessageBag', $objectMethods)) {
                if ($messageBag = $exception->getMessageBag()) {
                    $description = $messageBag->getMessages();
                }
            }
            /* Converter class name by ExceptionCode object */
            if (in_array('getExceptionConverter', $objectMethods)) {
                $objectConverter = $exception->getExceptionConverter();
                $object = (isset($objectConverter[0]) ? $objectConverter : $object);
            }
            /* Base exception status code */
            $status = (method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500); // Http status code
            $code = $exception->getCode(); // Error code
            /* About language path */
            $retrieveObjectPage = 'exception.' . strtr($object, ['\\' => '.']) . '.converter';
            $retrieveBasePage = 'exception.converter';
            $retrieveBaseTag = 'customize.' . $status . '.' . $code;
            /* Retrieve status code */
            /* Retrieve row customizations from object language files */
            $resetStatus = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.status', null);
            /* Retrieve row defaults from object language files */
            $resetStatus = ($resetStatus ?? Lang::dict($retrieveObjectPage, 'default.status', null));
            /* Retrieve row customizations from base language files */
            $resetStatus = ($resetStatus ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.status', null));
            /* Retrieve row defaults from base language files */
            $resetStatus = ($resetStatus ?? Lang::dict($retrieveBasePage, 'default.status', null));
            /* Verify status code format */
            $resetStatus = (preg_match('/^[1-5]{1}[0-9]{1}[0-9]{1}$/', $resetStatus) ? $resetStatus : null);
            /* Return reset status code */
            $resetStatus = (int) (isset($resetStatus) ? $resetStatus : 500); // Reset http status code

            /* Retrieve error code */
            /* Retrieve row customizations from object language files */
            $resetCode = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.code', null);
            /* Retrieve row defaults from object language files */
            $resetCode = ($resetCode ?? Lang::dict($retrieveObjectPage, 'default.code', null));
            /* Swap error code by reset code */
            $codeError = config('exception');
            /* Return the reset code for the conversion */
            $resetCode = (isset($resetCode) && isset($codeError[$object][$resetCode]) ? $codeError[$object][$resetCode] : $resetCode); // Swap code error
            /* Retrieve row customizations from base language files */
            $resetCode = ($resetCode ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.code', null));
            /* Retrieve row defaults from base language files */
            $resetCode = ($resetCode ?? Lang::dict($retrieveBasePage, 'default.code', null));
            /* Swap type */
            $resetCode = (string) $resetCode;
            /* Verify error code format */
            $resetCode = (isset($resetCode[0]) ? $resetCode : null);
            /* Return reset error code */
            $resetCode = (isset($resetCode) ? (is_numeric($resetCode) ? (int) $resetCode : $resetCode) : 0); // Reset error code

            /* Retrieve message */
            /* Retrieve row customizations from object language files */
            $resetMessage = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.message', null);
            /* Retrieve row defaults from object language files */
            $resetMessage = ($resetMessage ?? Lang::dict($retrieveObjectPage, 'default.message', null));
            /* Retrieve row customizations from base language files */
            $resetMessage = ($resetMessage ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.message', null));
            /* Retrieve row defaults from base language files */
            $resetMessage = ($resetMessage ?? Lang::dict($retrieveBasePage, 'default.message', null));
            /* Replace converter message tags by ExceptionCode object */
            if (isset($resetMessage) && in_array('getReplaceConverterMessage', $objectMethods)) {
                $resetMessage = $exception->getReplaceConverterMessage((string) $resetMessage);
            }
            /* Swap type */
            $resetMessage = (string) $resetMessage;
            /* Return reset message */
            $resetMessage = (isset($resetMessage[0]) ? $resetMessage : 'Unknown message.'); // Reset message
            /* Response error info data */
            $info = [
                'success' => false,
                'error' => [
                    'status' => $resetStatus,
                    'code' => $resetCode,
                    'message' => $resetMessage,
                    'description' => (isset($description) ? $description : [])
                ]
            ];
            /* Debug mode auxiliary source information */
            if (config('app.debug') && in_array($request->ip(), config('app.debug_whitelisted', []))) {
                $source = true;
                /* Source base */
                $info['source']['type'] = [
                    'self' => $object,
                    'parents' => $objectParents
                ];
                $info['source']['status'] = (int) $status;
                $info['source']['code'] = (is_numeric($code) ? (int) $code : $code);
                $info['source']['message'] = $exception->getMessage();
                $info['source']['trace'] = $exception->getTrace();
            }
            /* Ignore invalid UTF-8 characters */
            $info = json_decode(json_encode($info, JSON_INVALID_UTF8_IGNORE), true);
            /* Append response headers */
            $headers = [];
            /* Get response refresh authorization token */
            $token = (defined('REFRESH_TOKEN_BY_EXCEPTION') ? REFRESH_TOKEN_BY_EXCEPTION : null);
            if (isset($token) && (! ($exception instanceof AuthExceptionCode) || (($exception instanceof AuthExceptionCode) && $code != AuthExceptionCode::AUTH_FAIL))) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            /* Response */
            if ($request->is(['api', 'api/*'])) {
                return new JsonResponse($info, $resetStatus, $headers);
            }
            // Default HTML
            $title = Lang::dict($retrieveBasePage, 'customize.' . $resetStatus . '.0.message', null);
            $title = ($title ?? Lang::dict($retrieveBasePage, 'default.message', null));
            $source = (isset($source) ? $info : null);
            $contents = view('error',  [
                'title' => Str::title(Str::replaceLast('.', '', $title)),
                'code' => $resetStatus,
                'message' => $resetMessage,
                'source' => $source
            ]);
            $response = Response::make($contents, $resetStatus);
            $headers['Content-Type'] = 'text/html';
            $response->withHeaders($headers);
            return $response;
        });
    }
}
