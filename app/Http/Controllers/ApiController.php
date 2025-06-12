<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    public $search = null;

    use AuthorizesRequests, ValidatesRequests;
    public function buildResponse($success = true, $messages = "", $data = null, $status_code = 200, $additional_data = null)
    {
        return response()->json([
            'status_code' => $status_code,
            'success' => $success,
            'message' => $messages,
            'data' => $data,
            'additional' => $additional_data
        ], $status_code);
    }


}
