<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    use Helpers;

    protected $request;

    protected function errorBadRequest($message = '')
    {
        return $this->response->array($message)->setStatusCode(400);
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
