<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Settings\Contract\SettingContract;

class SettingsController extends Controller
{
    /**
     * @var SettingContract
     */
    protected $settings;

    public function __construct(SettingContract $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('manage', Setting::class);

        $settingsCollection = Setting::get()->sortBy('group')->groupBy('group');

        return view('admin.settings.index', compact('settingsCollection'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('manage', Setting::class);

        $groups = Setting::groups();

        return view('admin.settings.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {

        $this->authorize('manage', Setting::class);

        $request->validate([
            "group"      => 'required',
            "label"      => 'required',
            "type"       => 'required',
            "options"    => 'nullable|string',
            "help"       => 'nullable|string',
            "help_label" => 'nullable|string',
            "disable"    => 'nullable|boolean'
        ]);

        //dd($request->all());

        return $this->settings->create($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request)
    {
        $this->authorize('manage', Setting::class);

        $this->settings->update($request);

        $this->settings->clearCache();

        return redirect()->route('admin.setting.index')->with('success', 'Setting updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $this->authorize('manage', Setting::class);

        $request->validate([
            'key' => 'required|string'
        ]);

        $setting = Setting::where('key', $request->key)->firstOrFail();

        return $this->settings->delete($setting);
    }
}
