<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration;
use App\Enums\ConfigurationsTypeEnum;

class ConfigurationOptionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'score' => 0,
                'key' => ConfigurationsTypeEnum::CURRENCIES,
                'code' => 'EUR',
                'name' => [
                    'en' => 'EUR',
                    'ar' => 'يورو',
                    'de' => 'Euro',
                    'fr' => 'Euro',
                    'es' => 'Euro',
                ],
            ],
            [
                'id' => 2,
                'score' => 1,
                'key' => ConfigurationsTypeEnum::CURRENCIES,
                'code' => 'USD',
                'name' => [
                    'en' => 'USD',
                    'ar' => 'دولار أمريكي',
                    'de' => 'US-Dollar',
                    'fr' => 'Dollar américain',
                    'es' => 'Dólar estadounidense',
                ],
            ],
            [
                'id' => 3,
                'score' => 2,
                'key' => ConfigurationsTypeEnum::CURRENCIES,
                'code' => 'GBP',
                'name' => [
                    'en' => 'GBP',
                    'ar' => 'جنيه إسترليني',
                    'de' => 'Britisches Pfund',
                    'fr' => 'Livre sterling',
                    'es' => 'Libra esterlina',
                ],
            ],
        ];

        // Seed the data into the database
        foreach ($data as $item) {
            $model = Configuration::updateOrCreate([
                'id' => $item['id']
            ], [
                'score' => $item['score'],
                'key' => $item['key'],
                'active' => 1,
                'code' => $item['code'],
            ]);

            foreach ($item['name'] as $locale => $translation) {
                $model->translateOrNew($locale)->name = $translation;
            }

            $model->save();
        }
    }
}
