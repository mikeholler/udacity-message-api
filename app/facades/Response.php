<?php namespace Messenger\Facades;

use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response as BaseResponse;

class Response extends BaseResponse{
    /**
     * Return a new JSON response from the application.
     *
     * Customized to return 204 when given empty data, per REST standard.
     *
     * @param  string|array  $data
     * @param  int    $status
     * @param  array  $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public static function json($data = array(), $status = 200, array $headers = array())
    {
        if ($data)
        {
            return BaseResponse::json($data, $status, $headers);
        }
        else
        {
            return new \Illuminate\Http\Response(null, 204);
        }

    }
}