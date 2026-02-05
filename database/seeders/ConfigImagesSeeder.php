<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use App\Models\ConfigImage;
use App\Traits\FileHandler;
use Illuminate\Database\Seeder;
use File;
use Illuminate\Support\Facades\Http;


class ConfigImagesSeeder extends Seeder
{
    use FileHandler;
    public function run()
    {
        $list = [
            [
                'id'=>1,
                'name' => 'images/logos/logo.png',
                'key'=>ConfigEnum::LOGO,
                'page'=>ConfigEnum::LOGO,
            ],
            [
                'id'=>2,
                'name' => 'images/logos/dark-logo.png',
                'key'=>ConfigEnum::DARK_LOGO,
                'page'=>ConfigEnum::LOGO,
            ],

            [
                'id'=>3,
                'name' => 'images/favicon.png',
                'key'=>ConfigEnum::FAVICON,
                'page'=>ConfigEnum::LOGO,
            ],
            [
                'id'=>4,
                'name' => 'images/config-images/app3902094-qkyahp.apk',
                'key'=>ConfigEnum::APK,
                'page'=>ConfigEnum::APK,
            ],
        ];
        $this->deleteDirectory('config-images');
        foreach ($list as $item) {

    if(isset($item['name'])) {
        $extension = pathinfo($item['name'], PATHINFO_EXTENSION);
        $localPath = public_path($item['name']);
        $image_content = file_get_contents($localPath);
        $image =  $this->storeImage($image_content, 'config-images', $extension);
        $newService = ConfigImage::updateOrCreate([
            'id' => $item['id'],
        ],[
            'page'=>$item['page'],
            'key'=>$item['key'],
            'name'=>$image,
        ]);
        $newService->save();
    }
}
    }
}

