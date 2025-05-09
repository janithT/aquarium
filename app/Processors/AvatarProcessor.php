<?php


namespace App\Processors;


class AvatarProcessor {
    public static function get( $model ) {
        if (empty($model->file)) {
            if (!empty($model->animal_name)) {
                return 'https://api.dicebear.com/9.x/identicon/svg?seed=' .  $model->animal_name;
            }

            return null;
        }

        return $model->file->url;
    }
}
