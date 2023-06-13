<?php

namespace Bengr\Admin\Http\Controllers\Auth;

use Bengr\Admin\Facades\Admin as BengrAdmin;
use Bengr\Admin\Http\Controllers\Controller;
use Bengr\Admin\Http\Requests\Auth\LoginRequest;
use Bengr\Admin\Http\Resources\TokenResource;
use Bengr\Auth\NewToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Auth
 */
class AuthController extends Controller
{
    /**
     * Login admin user
     * 
     */
    public function login(LoginRequest $request)
    {
        /** @var AdminUser $admin */
        $admin = $request->authenticate();
        $token = $admin->createToken('bengr-admin-token');

        return response()->resource(TokenResource::class, $token);
    }

    /**
     * Logout admin user
     * 
     */
    public function logout(Request $request)
    {
        $token = app(BengrAdmin::authTokenModel())->where('access_token', hash('sha256', $request->bearerToken()))->whereHasMorph('tokenable', BengrAdmin::authUserModel())->first();

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

    /**
     * Get access_token from refresh_token of admin user
     * 
     */
    public function token(Request $request)
    {
        if (!$request->has('refresh_token')) throw new NotFoundHttpException();

        $token = app(BengrAdmin::authTokenModel())->where('refresh_token', hash('sha256', $request->get('refresh_token')))->whereHasMorph('tokenable', BengrAdmin::authUserModel())->first();

        if ($token) {
            $token->access_token = hash('sha256', $plainAccessToken = Str::random(40));
            $token->save();

            $token = new NewToken($token, $plainAccessToken, $request->get('refresh_token'));

            return response()->resource(TokenResource::class, $token);
        }

        return throw new NotFoundHttpException();
    }
}
