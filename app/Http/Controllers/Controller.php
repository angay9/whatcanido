<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

    }

    public function respondCreated(array $data = [])
    {
        return response()->json($data, Response::HTTP_CREATED);
    }

    public function respondSuccess(array $data = [])
    {
        return response()->json($data, Response::HTTP_OK);
    }

    public function respondForbidden()
    {
        return response('', Response::HTTP_FORBIDDEN);
    }

    public function respondUnauthorized()
    {
        return response('', Response::HTTP_UNAUTHORIZED);
    }
}
