<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class test extends Controller
{
    public function myrun()
    {
        try {
            $data = json_decode(file_get_contents(storage_path().'/likulator-vk-export.json'), true);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            return;
        }

        // Process source file content to store it into database
        $seeds = [];

        foreach ($data['users'] as $user) {

            $seeds[] = [
                'vk_id' => $user['id'],
                'city_id' => $user['cityId']!="undefined" ? (int)$user['cityId'] : 0,
                'avatar_url' => $user['photoSrc'] != '' ? $user['photoSrc'] : '',
                'name' => $user['name'] ,
                'photo_like_count' => $user['photoLikeCount'],
                'video_like_count' => $user['videoLikeCount'],
                'wall_like_count' => $user['wallLikeCount'],
                'total_like_count' => isset($user['totalLikeCount']) ? $user['totalLikeCount'] : 0,
            ];

        }

        if(!empty($seeds)) {
            DB::table('vk_users')->insert($seeds);
        }

    }
}
