<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Facades\Admin;
use Bengr\Admin\Http\Requests\AdminUserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Auth
 */
class AdminUserController extends Controller
{
    /**
     * Get logged in admin user
     */
    public function me(Request $request)
    {
        $user = $request->user(Admin::getGuardName());

        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar_url' => $user->getFirstMediaUrl('avatar')
        ]);
    }

    /**
     * Get logged in admin user
     */
    public function update(AdminUserUpdateRequest $request)
    {
        $user = $request->user(Admin::getGuardName());

        $user->update([
            'first_name' => $request->validated('first_name', $user->first_name),
            'last_name' => $request->validated('last_name', $user->last_name),
            'username' => $request->validated('username', $user->username),
            'email' => $request->validated('email', $user->email),
        ]);

        if ($request->validated('new_password')) {
            $user->update([
                'password' => Hash::make($request->validated('new_password'))
            ]);
        }

        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        if ($request->has('image')) {
            $request->user(Admin::getGuardName())->addMediaFromRequest('image')->usingFileName('avatar.jpg')->toMediaCollection('avatar');
        }

        return response()->json([
            'mesasge' => 'avatar uploaded'
        ]);
    }

    public function deleteAvatar(Request $request)
    {
        $request->user(Admin::getGuardName())->getFirstMedia('avatar')->delete();

        return response()->json([
            'mesasge' => 'avatar deleted'
        ]);
    }
}
