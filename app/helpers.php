<?php

if (! function_exists('apiResponse')) {

    function apiResponse(string $message, $data = new stdClass(), array $errors = [], int $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'errors' => $errors,
            'status' => $status,
        ], $status);
    }
}
