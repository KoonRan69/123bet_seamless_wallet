<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function response($code = 200, $data = [], $message = '', $errors = [], $status = true)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}