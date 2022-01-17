<?php
namespace App\Controller;

use App\Log;

class IndexController extends AbstractController
{
    public function index(): array
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        Log::error('三方错误', [$user], __CLASS__ . '.' . __FUNCTION__, 'routeXL-api');
        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
