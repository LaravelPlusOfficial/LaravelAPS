<?php

namespace App\Services\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting as SettingModel;
use App\Services\Settings\Contract\SettingContract;
use App\Services\Settings\Processing\ProcessImageSetting;

class Setting implements SettingContract
{

    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @var SettingModel
     */
    protected $model;

    /**
     * @var
     */
    protected $key;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Setting constructor.
     * @param SettingModel $model
     * @param Request      $request
     */
    public function __construct(SettingModel $model, Request $request)
    {
        $this->model = $model;

        $this->cacheKey = md5('settings');

        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->cache();
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        if ($response = $this->keyIsNotUnique()) {
            return $response;
        }

        $options = $this->request->options;

        if ($this->request->type == 'select') {
            //{"all": "All", "index, follow": "Index & Follow"}
            $options = $this->formatOptionsForSelect($options);
        }

        $this->model->create([
            "key"        => $this->key,
            "label"      => $this->request->label,
            "group"      => str_slug($this->request->group, '_'),
            "type"       => $this->request->type,
            "options"    => $options,
            "help"       => $this->request->help,
            "help_label" => $this->request->help_label,
            "disabled"    => $this->request->disable ? true : false
        ]);

        $this->clearCache();

        return redirect()->route('admin.setting.index')->with('success', 'Setting created');
    }

    /**
     * @param Request $request
     * @return SettingModel|array
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $updatedSettings = [];

        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {

            $setting = $this->model->where('key', $key)->firstOrFail();

            if ($this->isTypeOf('image', $setting->type)) {

                $setting = (new ProcessImageSetting($setting, $this->request))->update();

            } else {

                $setting = $this->set($setting->key, $value, $setting)->fresh();
            }

            $updatedSettings[$setting->key] = $setting->toArray();

        }

        return $updatedSettings;
    }

    /**
     * @param SettingModel $setting
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(SettingModel $setting)
    {
        if ($setting->delete()) {
            return redirect()->route('admin.setting.index')->with('success', 'Setting deleted');
        }

        return back()->with('error', 'Error while deleting the setting');
    }

    /**
     * @param      $key
     * @param null $default
     * @param null $value
     * @return null
     */
    public function get($key, $default = null, $value = null)
    {
        if ($key && $value) {
            return $this->set($key, $value);
        }

        $setting = $this->cache()->where('key', $key)->first();


        return $setting->value ?? $default;
    }

    /**
     * @param      $key
     * @param      $value
     * @param null $setting
     * @return mixed
     */
    public function set($key, $value, $setting = null)
    {
        $setting = $setting ?? $this->model->where('key', $key)->firstOrFail();

        $setting->update([
            'value' => $value
        ]);

        $this->clearCache();

        return $setting;
    }

    public function setNull($key)
    {
        $setting = $this->model->where('key', $key)->firstOrFail();

        $setting->value = null;

        $setting->save();

        return $setting;
    }

    /**
     * @param bool $cached
     * @return mixed
     */
    public function cache($cached = true)
    {
        if (!$cached) {
            return $this->model->get();
        }

        return Cache::remember($this->cacheKey, 60 * 24 * 30, function () {
            return $this->model->get();
        });
    }

    /**
     * Clear settings cache
     */
    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Check if key is unique to store in database
     *
     * @return $this
     */
    protected function keyIsNotUnique()
    {
        $this->key = str_slug("{$this->request->group} {$this->request->label}", "_");

        if ($this->model->where('key', $this->key)->exists()) {
            return back()->withErrors([
                'Choose different label or group'
            ]);
        }

    }

    /**
     * Check type of setting
     *
     * @param $expectedType
     * @param $settingType
     * @return bool
     */
    protected function isTypeOf($expectedType, $settingType)
    {
        return $expectedType == $settingType;
    }

    protected function formatOptionsForSelect($options)
    {
        $result = [];

        foreach (explode(',', $options) as $option) {

            $explode = explode('=>', $option);

            $value = trim($explode[1]);

            $result[$value] = trim($explode[0]);

        }

        return $result;
    }
}