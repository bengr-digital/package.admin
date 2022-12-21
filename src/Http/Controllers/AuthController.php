<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Requests\LoginRequest;
use Bengr\Auth\NewToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Bengr\Support\response;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $admin = $request->authenticate();
        $token = $admin->createToken('bengr-admin-token');

        return response()->json([
            'token' => [
                'name' => $token->name,
                'access_token' => $token->access_token,
                'refresh_token' => $token->refresh_token,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $token = app(BengrAdmin::authTokenModel())->where('access_token', hash('sha256', $request->bearerToken()))->whereHasMorph('tokenable', BengrAdmin::authUserModel())->first();

        if ($token) {
            $token->delete();

            return response()->json([
                'message' => 'logged in'
            ]);
        }

        return response()->json([
            'message' => 'you are not event logged in'
        ]);
    }

    public function me(Request $request)
    {
        return response($request->user('admin'));
    }

    public function token(Request $request)
    {
        if (!$request->has('refresh_token')) throw new NotFoundHttpException();

        $token = app(BengrAdmin::authTokenModel())->where('refresh_token', hash('sha256', $request->get('refresh_token')))->whereHasMorph('tokenable', BengrAdmin::authUserModel())->first();

        if ($token) {
            $token->access_token = hash('sha256', $plainAccessToken = Str::random(40));
            $token->save();

            $token = new NewToken($token, $plainAccessToken, $request->get('refresh_token'));

            return response()->json([
                'token' => [
                    'name' => $token->name,
                    'access_token' => $token->access_token,
                    'refresh_token' => $token->refresh_token,
                ]
            ]);
        }

        throw new NotFoundHttpException();
    }
}
