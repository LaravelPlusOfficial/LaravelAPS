<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown()
    {
        Storage::disk('public')->deleteDirectory('site/images');
    }

    /** @test */
    public function setting_image_can_be_uploaded()
    {
        $this->withoutExceptionHandling();

        $this->signInAdmin();

        $setting = $this->createSetting('default_site_image', null, 'image', 'seo');

        $testConfig = $this->getConfig();

        Storage::fake("public/{$testConfig['folder']}");

        $file = UploadedFile::fake()->image('name of image.jpg', $testConfig['width'], $testConfig['height']);

        $this->patch(route('admin.setting.store'), [
            $setting->key => $file
        ]);

        Storage::disk('public')->assertExists($setting->fresh()->value);
    }

    /** @test */
    public function setting_image_should_have_proper_name_format()
    {
        Carbon::setTestNow(Carbon::now());

        $time = Carbon::getTestNow()->timestamp;

        $this->signInAdmin();

        $settingKey = 'seo_default_site_image';

        $setting = $this->createSetting($settingKey, null, 'image', '');

        $testConfig = $this->getConfig();

        Storage::fake("public/{$testConfig['folder']}"); // public/sites/images

        // Name = md5( settingKey WIDTHxHEIGHT time() )

        $name = str_slug("{$settingKey} {$testConfig['width']}x{$testConfig['height']} " . $time, "-");

        $expectedName = md5( $name ) . '.jpg';

        $file = UploadedFile::fake()->image('random name.jpg', $testConfig['width'], $testConfig['height']);

        $this->patch(route('admin.setting.store'), [
            $setting->key => $file
        ]);

        Storage::disk('public')->assertExists($setting->fresh()->value);

        $this->assertEquals(
            $expectedName,
            last(explode('/', $setting->fresh()->value))
        );

    }

    protected function getConfig($options = [])
    {
        return array_merge([
            'width'  => 1200,
            'height' => 627,
            'folder' => 'site/images'
        ], $options);
    }

    protected function createSetting($key, $value = null, $type = 'input', $group = 'general')
    {
        return factory(Setting::class)->create([
            'group'      => str_slug($group, '_'),
            'label'      => title_case(str_replace('_', ' ', $key)),
            'key'        => str_slug($key, "_"),
            'value'      => $value,
            'type'       => $type,
            'options'    => null,
            'help'       => null,
            'help_label' => null,
        ]);
    }

}
