<?php

namespace App\Services\Settings\Contract;

use App\Models\Setting;
use Illuminate\Http\Request;

interface SettingContract
{

    public function get($name);

    public function all();

    public function create(Request $request);

    public function update(Request $request);

    public function delete(Setting $setting);

    public function set($name, $value);

    public function cache();

    public function clearCache();

}