<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use App\Models\ConfigLink;
use Illuminate\Database\Seeder;


class ConfigEmailLinkSeeder extends Seeder
{
    public function run()
    {
        $list = [
            [
                'id' => 1,
                'name' => '#',
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::FOOTER_LINKED_IN,
            ],
            [
                'id' => 2,
                'name' => '#',
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::FOOTER_FACEBOOK,
            ],
            [
                'id' => 3,
                'name' => '#',
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::FOOTER_TWITTER,
            ],
            [
                'id' => 4,
                'name' => '#',
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::FOOTER_INSTAGRAM,
            ],
            [
                'id' => 5,
                'name' => 'support@hakimclinics.com',
                'key' => ConfigEnum::INFO_EMAIL,
                'page' => ConfigEnum::FOOTER,
            ],
            [
                'id' => 6,
                'name' => '+49 123 456 7890',
                'key' => ConfigEnum::INFO_PHONE,
                'page' => ConfigEnum::FOOTER,
            ],
            [
                'id' => 7,
                'name' => 'hakimclinics.com',
                'key' => ConfigEnum::DOMAIN,
                'page' => ConfigEnum::FOOTER,
            ],
            [
                'id' => 8,
                'name' => '1',
                'key' => ConfigEnum::NUMBER_OF_MONTHS_FREE_SUBSCRIPTION,
                'page' => ConfigEnum::NUMBER_OF_MONTHS_FREE_SUBSCRIPTION,
            ],
        ];

        foreach ($list as $item) {
            $newService = ConfigLink::updateOrCreate([
                'id' => $item['id'],
            ], [
                'page' => $item['page'],
                'key' => $item['key'],
                'name' => $item['name'],
            ]);

            $newService->save();
        }
    }
}

