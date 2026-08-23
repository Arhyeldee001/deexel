<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public static function schema(): array
    {
        return [
            'general' => [
                'label'  => 'General',
                'fields' => [
                    'site_name'       => ['label' => 'Site name', 'type' => 'text'],
                    'tagline'         => ['label' => 'Tagline', 'type' => 'text'],
                    'nav_cta_label'   => ['label' => 'Navbar button label', 'type' => 'text', 'default' => 'Get a quote'],
                    'footer_tagline'  => ['label' => 'Footer tagline', 'type' => 'textarea'],
                    'footer_wordmark' => ['label' => 'Footer wordmark (big text)', 'type' => 'text', 'placeholder' => 'e.g. Quin Tech Nigeria — defaults to the site name'],
                ],
            ],
            'appearance' => [
                'label'  => 'Appearance',
                'fields' => [
                    'logo'           => ['label' => 'Logo', 'type' => 'image'],
                    'favicon'        => ['label' => 'Favicon', 'type' => 'image'],
                    'logo_height'    => ['label' => 'Logo height', 'type' => 'number', 'default' => 32],
                    'accent_type'    => ['label' => 'Accent style', 'type' => 'text', 'default' => 'solid'],
                    'accent_color'   => ['label' => 'Accent color', 'type' => 'color', 'default' => '#ff6a00'],
                    'accent_color_2' => ['label' => 'Accent color 2', 'type' => 'color', 'default' => '#ff9500'],
                    'accent_angle'   => ['label' => 'Gradient angle', 'type' => 'number', 'default' => 90],
                ],
            ],
            'seo' => [
                'label'  => 'SEO',
                'fields' => [
                    'meta_description'    => ['label' => 'Default meta description', 'type' => 'textarea'],
                    'meta_keywords'       => ['label' => 'Default meta keywords', 'type' => 'text'],
                    'og_image'            => ['label' => 'Default social share image', 'type' => 'image', 'hint' => 'Shown when pages are shared on social media.'],
                    'google_analytics_id' => ['label' => 'Google Analytics ID', 'type' => 'text', 'placeholder' => 'G-XXXXXXXXXX'],
                ],
            ],
            'contact' => [
                'label'  => 'Contact & social',
                'fields' => [
                    'contact_email'    => ['label' => 'Contact email', 'type' => 'text'],
                    'contact_phone'    => ['label' => 'Contact phone', 'type' => 'text'],
                    'social_linkedin'  => ['label' => 'LinkedIn URL', 'type' => 'text'],
                    'social_x'         => ['label' => 'X (Twitter) URL', 'type' => 'text'],
                    'social_instagram' => ['label' => 'Instagram URL', 'type' => 'text'],
                    'social_facebook'  => ['label' => 'Facebook URL', 'type' => 'text'],
                ],
            ],
            'maintenance' => [
                'label'  => 'Maintenance',
                'fields' => [
                    'maintenance_mode'    => ['label' => 'Maintenance mode', 'type' => 'bool', 'default' => '0'],
                    'maintenance_heading' => ['label' => 'Heading', 'type' => 'text', 'default' => 'We’ll be right back'],
                    'maintenance_message' => ['label' => 'Message', 'type' => 'textarea', 'default' => 'Our site is undergoing scheduled maintenance. Please check back shortly.'],
                ],
            ],
        ];
    }

    public function edit()
    {
        $schema = self::schema();
        $values = [];

        foreach ($schema as $group) {
            foreach ($group['fields'] as $key => $def) {
                $values[$key] = Setting::get($key, $def['default'] ?? null);
            }
        }

        return view('admin.settings.edit', [
            'schema' => $schema,
            'values' => $values,
            'pages'  => Page::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request)
    {
        foreach (self::schema() as $groupKey => $group) {
            foreach ($group['fields'] as $key => $def) {
                if ($def['type'] === 'image') {
                    if ($request->hasFile($key)) {
                        $rule = $key === 'favicon'
                            ? ['file', 'mimes:png,ico,svg,jpg,jpeg,webp', 'max:1024']
                            : ['image', 'max:4096'];
                        $request->validate([$key => $rule]);

                        $old = Setting::get($key);
                        if ($old) {
                            Storage::disk('public')->delete($old);
                        }
                        Setting::set($key, $request->file($key)->store('settings', 'public'), $groupKey, 'image');
                    } elseif ($request->boolean('remove_'.$key)) {
                        $old = Setting::get($key);
                        if ($old) {
                            Storage::disk('public')->delete($old);
                        }
                        Setting::set($key, null, $groupKey, 'image');
                    }
                    continue;
                }

                if ($def['type'] === 'bool') {
                    Setting::set($key, $request->boolean($key) ? '1' : '0', $groupKey, 'bool');
                    continue;
                }

                Setting::set($key, $request->input($key), $groupKey, $def['type']);
            }
        }

        return back()->with('ok', 'Settings saved.');
    }

    /* ---------- Per-page SEO ---------- */

    public function editPageSeo(Page $page)
    {
        return view('admin.settings.page-seo', ['page' => $page]);
    }

    public function updatePageSeo(Request $request, Page $page)
    {
        $data = $request->validate([
            'meta_title'       => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords'    => ['nullable', 'string', 'max:255'],
            'og_image'         => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('og_image')) {
            if ($page->og_image) {
                Storage::disk('public')->delete($page->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        } elseif ($request->boolean('remove_og_image') && $page->og_image) {
            Storage::disk('public')->delete($page->og_image);
            $data['og_image'] = null;
        } else {
            unset($data['og_image']);
        }

        $page->update($data);

        return redirect()->route('admin.settings.edit')->with('ok', 'SEO for “'.$page->title.'” saved.');
    }
}
