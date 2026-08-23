<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\Section;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        /* ---------------- Settings ---------------- */
        $settings = [
            ['site_name', 'Quin Tech', 'general', 'text'],
            ['tagline', 'Software solutions, powered by engineering.', 'general', 'text'],
            ['accent_color', '#ff6a00', 'appearance', 'color'],
            ['logo', null, 'appearance', 'image'],
            ['nav_cta_label', 'Get a quote', 'general', 'text'],
            ['footer_tagline', 'Building digital products in Lagos since 2018.', 'general', 'textarea'],
            ['meta_description', 'Quin Tech is a Nigerian software agency building web platforms, mobile apps, and cloud systems for ambitious teams.', 'seo', 'textarea'],
            ['contact_email', 'hello@quintechnigeria.com', 'contact', 'text'],
            ['contact_phone', '+234 800 000 0000', 'contact', 'text'],
        ];
        foreach ($settings as [$key, $value, $group, $type]) {
            Setting::updateOrCreate(['key' => $key], compact('value', 'group', 'type'));
        }

        /* ---------------- Pages ---------------- */
        $pages = [
            ['Home', 'home', 0],
            ['About', 'about', 1],
            ['Services', 'services', 2],
            ['Projects', 'projects', 3],
            ['Team', 'team', 4],
            ['Blog', 'blog', 5],
            ['FAQ', 'faq', 6],
            ['Contact', 'contact', 7],
        ];
        foreach ($pages as [$title, $slug, $order]) {
            Page::updateOrCreate(['slug' => $slug], [
                'title'            => $title,
                'is_active'        => true,
                'show_in_nav'      => true,
                'sort_order'       => $order,
                'is_system'        => true,
                'meta_title'       => $title === 'Home' ? null : $title,
                'meta_description' => 'Quin Tech — '.$title.'.',
            ]);
        }

        /* ---------------- Home sections ---------------- */
        $home = Page::where('slug', 'home')->first();
        $home->sections()->delete();

        $sections = [
            [
                'type' => 'hero', 'name' => 'Hero', 'sort_order' => 0, 'content' => [
                    'badge_label'     => "What's new",
                    'badge_text'      => 'Q3 case studies',
                    'heading'         => 'Software solutions, powered by engineering.',
                    'subheading'      => 'We design, build, and ship web platforms, mobile apps, and cloud systems for ambitious teams across Nigeria.',
                    'primary_label'   => 'Get a quote',
                    'secondary_label' => 'Read more',
                    'secondary_url'   => '/about',
                    'image'           => null,
                ],
            ],
            [
                'type' => 'services', 'name' => 'Services', 'sort_order' => 1, 'content' => [
                    'heading'    => 'What we do',
                    'subheading' => 'Six service areas, each editable and toggleable from the admin panel.',
                    'limit'      => 6,
                ],
            ],
            [
                'type' => 'stats', 'name' => 'Stats', 'sort_order' => 2, 'content' => [
                    'items' => [
                        ['value' => '120+', 'label' => 'Projects delivered'],
                        ['value' => '60+',  'label' => 'Happy clients'],
                        ['value' => '8',    'label' => 'Years running'],
                        ['value' => '15',   'label' => 'Specialists'],
                    ],
                ],
            ],
            [
                'type' => 'features', 'name' => 'Features', 'sort_order' => 3, 'content' => [
                    'heading'        => 'Powerful capabilities',
                    'subheading'     => 'Explore what sets Quin Tech apart.',
                    'left_heading'   => 'Top management, to help you see the bigger picture',
                    'left_body'      => 'From first call to launch, we handle the build so you can focus on the business.',
                    'left_cta_label' => 'See our work',
                    'left_cta_url'   => '/projects',
                    'checks'         => [
                        'Custom architecture for every project.',
                        'Design tuned to your brand.',
                        'Scalable, maintainable code.',
                    ],
                    'rows' => [
                        ['icon' => 'layers', 'title' => 'Discovery',     'text' => 'Scope, goals, and a clear plan.'],
                        ['icon' => 'chat',   'title' => 'Design sprint', 'text' => 'Prototypes you can click.'],
                        ['icon' => 'rocket', 'title' => 'Launch',        'text' => 'Ship, monitor, iterate.'],
                    ],
                ],
            ],
            [
                'type' => 'projects', 'name' => 'Projects', 'sort_order' => 4, 'content' => [
                    'heading'    => 'Featured projects',
                    'subheading' => 'Pulled live from the admin Projects module.',
                    'limit'      => 3,
                ],
            ],
            [
                'type' => 'blog_teaser', 'name' => 'Insights', 'sort_order' => 5, 'content' => [
                    'heading'    => 'Insights & inspiration',
                    'subheading' => 'Articles, case studies, and lessons from building software in Nigeria.',
                    'limit'      => 3,
                ],
            ],
            [
                'type' => 'cta', 'name' => 'CTA', 'sort_order' => 6, 'content' => [
                    'heading'      => "Have a project in mind? Let's build it.",
                    'button_label' => 'Book a free consultation',
                ],
            ],
        ];
        foreach ($sections as $s) {
            Section::create(array_merge($s, ['page_id' => $home->id, 'is_active' => true]));
        }

        /* ---------------- Services ---------------- */
        $services = [
            ['Web development', 'code',   'Fast, scalable websites and web apps.'],
            ['Mobile apps',     'mobile', 'iOS and Android, built to last.'],
            ['Cloud & DevOps',  'cloud',  'Deploy, scale, and monitor with ease.'],
            ['UI / UX design',  'design', 'Interfaces people love to use.'],
            ['API & integrations', 'layers', 'Connect the tools your business runs on.'],
            ['Maintenance & support', 'shield', 'We keep everything running smoothly.'],
        ];
        foreach ($services as $i => [$title, $icon, $excerpt]) {
            Service::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title, 'icon' => $icon, 'excerpt' => $excerpt,
                'sort_order' => $i, 'is_active' => true,
            ]);
        }

        /* ---------------- Projects ---------------- */
        $projects = [
            ['PayFlow dashboard', 'Fintech',   'A real-time payments dashboard.'],
            ['SwiftMove app',     'Logistics', 'Last-mile delivery tracking.'],
            ['CareLink portal',   'Health',    'Patient records, simplified.'],
        ];
        foreach ($projects as $i => [$title, $category, $excerpt]) {
            Project::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title, 'category' => $category, 'excerpt' => $excerpt,
                'is_featured' => true, 'sort_order' => $i, 'is_active' => true,
            ]);
        }

        /* ---------------- Blog ---------------- */
        $cat = PostCategory::updateOrCreate(['slug' => 'insights'], ['name' => 'Insights']);
        $posts = [
            ['Digital age', 'How we build lasting client relationships.'],
            ['Shipping fast without breaking things', 'Our approach to reliable releases.'],
            ['Designing for Nigerian users', 'Lessons from the field.'],
        ];
        foreach ($posts as $i => [$title, $excerpt]) {
            Post::updateOrCreate(['slug' => Str::slug($title)], [
                'post_category_id' => $cat->id,
                'title' => $title, 'excerpt' => $excerpt,
                'body' => '<p>'.$excerpt.'</p>',
                'status' => 'published', 'published_at' => now()->subDays($i),
            ]);
        }

        /* ---------------- Team ---------------- */
        $team = [
            ['Ada Okeke', 'Founder & CEO'],
            ['Emeka Obi', 'Lead Engineer'],
            ['Zainab Bello', 'Head of Design'],
        ];
        foreach ($team as $i => [$name, $role]) {
            TeamMember::updateOrCreate(['name' => $name], [
                'role' => $role, 'sort_order' => $i, 'is_active' => true,
            ]);
        }

        /* ---------------- FAQs ---------------- */
        $faqs = [
            ['How long does a project take?', 'Most projects run 6–12 weeks depending on scope.'],
            ['Do you offer support after launch?', 'Yes — we offer ongoing maintenance and support plans.'],
            ['Where are you based?', 'We are based in Lagos, Nigeria, and work with clients worldwide.'],
        ];
        foreach ($faqs as $i => [$q, $a]) {
            Faq::updateOrCreate(['question' => $q], [
                'answer' => $a, 'sort_order' => $i, 'is_active' => true,
            ]);
        }
    }
}
