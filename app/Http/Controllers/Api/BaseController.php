<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function responseOk($body, $status = 200, $headers = [], $options = 0)
    {
        $data = [
            'status' => true,
            'code' => $status,
            'body' => $body,
        ];

        return response()->json($data, $status, $headers, $options);
    }

    protected function responseError($body, $status = 404, $headers = [], $options = 0)
    {
        $data = [
            'status' => false,
            'code' => $status,
            'body' => $body,
        ];

        return response()->json($data, $status, $headers, $options);
    }
}
