<?php

namespace Bengr\Admin\GlobalActions\Builtin;

use Bengr\Admin\GlobalActions\GlobalAction;
use Bengr\Admin\Facades\Admin;

class Logout extends GlobalAction
{
    protected ?string $name = 'logout';

    protected array $middlewares = ['auth:admin'];

    public function call(array $payload = [])
    {
        $token = app(Admin::geAuthTokenModel())->where('access_token', hash('sha256', request()->bearerToken()))->whereHasMorph('tokenable', Admin::getAuthUserModel())->first();

        if ($token) {
            $token->delete();

            return response()->json([
                'message' => __('admin.auth.logged_out')
            ]);
        }

        return response()->json([
            'message' => __('admin.auth.not_logged_in')
        ]);
    }
}
