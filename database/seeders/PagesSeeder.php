<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug' => 'about-us',
                'title' => 'About Us',
                'meta_title' => 'About ETS2LT',
                'meta_description' => 'Learn more about ETS2LT, our mission and community.',
                'content' => <<<MD
## About ETS2LT

ETS2LT is a community-driven platform for sharing high-quality mods for  
**Euro Truck Simulator 2** and **American Truck Simulator**.

Our goal is to provide a **safe, moderated and professional** environment
for both mod creators and players.

### What We Offer
- Verified mod authors
- Versioned mod releases
- Transparent moderation
- Community-driven ratings & feedback
MD,
            ],
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'How we collect, use, and protect your data.',
                'content' => <<<MD
## Privacy Policy

We respect your privacy.

### Data We Collect
- Account information
- Uploaded content
- Usage analytics
- Cookies (for functionality & ads)

We **never sell personal data**.

Contact us if you have any questions.
MD,
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Terms & Conditions',
                'meta_title' => 'Terms and Conditions',
                'meta_description' => 'Rules and conditions for using ETS2LT.',
                'content' => <<<MD
## Terms & Conditions

By using ETS2LT you agree to:

- Upload only content you own or have permission to share
- Not abuse download or report systems
- Respect moderators and other users

We reserve the right to remove content or accounts.
MD,
            ],
            [
                'slug' => 'cookie-policy',
                'title' => 'Cookie Policy',
                'meta_title' => 'Cookie Policy',
                'meta_description' => 'How ETS2LT uses cookies.',
                'content' => <<<MD
## Cookie Policy

ETS2LT uses cookies to:

- Maintain sessions
- Remember preferences
- Serve advertisements (Google AdSense)

You can manage cookie consent at any time.
MD,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
