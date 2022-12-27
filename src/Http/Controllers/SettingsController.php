<?php

namespace Bengr\Admin\Http\Controllers;

use Bengr\Admin\Http\Requests\AdminSettingsUpdateRequest;
use Bengr\Admin\Models\AdminSettings;
use Bengr\Admin\Models\AdminSettingsBilling;
use Bengr\Admin\Models\AdminSettingsLanguage;
use Bengr\Admin\Models\AdminSettingsSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Bengr\Support\response;

/**
 * @group Bengr Administration
 * @subgroup Settings
 */
class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $settings = AdminSettings::first();

        if (!$settings) {
            AdminSettings::create();
            $settings = AdminSettings::first();
        }

        return response($settings);
    }

    public function update(AdminSettingsUpdateRequest $request)
    {
        $settings = AdminSettings::first();

        DB::transaction(function () use ($request, $settings) {
            if (!$settings) {
                $settings = AdminSettings::create([
                    'phone' => $request->validated('phone', null),
                    'email' => $request->validated('email', null),
                ]);
            } else {
                $settings->update([
                    'phone' => $request->validated('phone', $settings->phone),
                    'email' => $request->validated('email', $settings->email),
                ]);
            }

            if ($request->has('socials')) {
                foreach ($request->get('socials') as $name => $url) {
                    if ($settings->socials()->where('name', $name)->count()) {
                        $settings->socials()->where('name', $name)->update([
                            'url' => $url
                        ]);
                    } else {
                        AdminSettingsSocial::create([
                            'settings_id' => $settings->id,
                            'name' => $name,
                            'url' => $url
                        ]);
                    }
                }
            }

            if ($request->has('languages')) {
                foreach ($request->get('languages') as $language) {
                    if ($settings->languages()->where('code', $language['code'])->count()) {
                        $settings->languages()->where('code', $language['code'])->update([
                            'is_default' => $language['is_default'] ?? false
                        ]);
                    } else {
                        AdminSettingsLanguage::create([
                            'settings_id' => $settings->id,
                            'code' => $language['code'],
                            'is_default' => $language['is_default']
                        ]);
                    }
                }
            }

            if ($request->has('billing')) {
                if ($settings->billing()->count()) {
                    $settings->billing()->update([
                        'name' => $request->validated('billing.name', $settings->billing->name),
                        'cin' => $request->validated('billing.cin', $settings->billing->cin),
                        'tin' => $request->validated('billing.tin', $settings->billing->tin),
                        'country' => $request->validated('billing.country', $settings->billing->country),
                        'city' => $request->validated('billing.city', $settings->billing->city),
                        'zipcode' => $request->validated('billing.zipcode', $settings->billing->zipcode),
                        'street' => $request->validated('billing.street', $settings->billing->street),
                    ]);
                } else {
                    AdminSettingsBilling::create([
                        'settings_id' => $settings->id,
                        'name' => $request->validated('billing.name', null),
                        'cin' => $request->validated('billing.cin', null),
                        'tin' => $request->validated('billing.tin', null),
                        'country' => $request->validated('billing.country', null),
                        'city' => $request->validated('billing.city', null),
                        'zipcode' => $request->validated('billing.zipcode', null),
                        'street' => $request->validated('billing.street', null),
                    ]);
                }
            }
        });

        return response(AdminSettings::first());
    }

    public function deleteSocial(Request $request, AdminSettingsSocial $social)
    {
        $social->delete();

        return response()->json([
            'message' => 'social was deleted'
        ]);
    }

    public function deleteLanguage(Request $request, AdminSettingsLanguage $language)
    {
        $language->delete();

        return response()->json([
            'message' => 'language was deleted'
        ]);
    }
}
