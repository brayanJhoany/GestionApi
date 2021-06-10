<?php

namespace App\Traits;

/**
 * trait that returns the api response types
 */
trait ApiResponser
{
    /**
     * Get successful response in the api
     *
     * @return json response with information succesfull
     * @param json $data: json with information to show
     * @param int $code: code http
     */
    protected function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }


    /**
     * Get successful response in the api (message)
     *
     * @return json response with message succesfull
     * @param json $data: json with information to show
     * @param int $code: code http
     */
    protected function successMessageResponse($message, $code)
    {
        return response()->json(['message' => $message, 'code' => $code], $code);
    }


    /**
     * Get error internal service Workspace response in the api
     *
     * @return json response with information about an internal api error
     * @param string $internalCode: internal error code described in file /config/error
     * @param int $codeHttp: code http, normally it's 400
     */
    protected function errorResponse($codeHttp, $description = null)
    {

        return response()->json([
            'message'      => $description,
            'error'        => true,
            'httpCode'     => $codeHttp,
        ], $codeHttp);
    }


    /**
     * Get error http response in the api
     *
     * @return json response with information about an http api error
     * @param string $message: message of an error
     * @param int $codeHttp: code http
     */
    protected function errorHttp($message, $codeHttp)
    {
        return response()->json([
            'message'   => $message,
            'code'      => $codeHttp
        ], $codeHttp);
    }
}
