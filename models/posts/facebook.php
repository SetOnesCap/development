<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/environment.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/models/post.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/services/jsonCacher.php');

class FacebookPost extends Post
{
    function containsImage($postType, $object_id)
    {
        if ($postType == 'photo' && $object_id != '') {
            return true;
        } else {
            return false;
        }
    }

    function getImage($object_id)
    {
        $image_source = '';
        $accessToken = $_ENV["FACEBOOK_ACCESS_TOKEN"];
        $jsonData = cacheJsonFromUrl('https://graph.facebook.com/' . $object_id . '?fields=images&access_token=' . $accessToken, 'images.json');
        $jsonObj = json_decode($jsonData);
        foreach ($jsonObj->images as $image) {
            if ($image->height >= 480 && $image->height < 700) {
                $image_source = $image->source;
                break;
            }
        };
        return $image_source;
    }

    function __construct($data)
    {
        $object_id = isset($data->object_id) ? $data->object_id : '';
        $message = isset($data->message) ? $data->message : '';
        $created_time = date('M j, Y', strtotime($data->created_time));
        $created_time_unix = strtotime($data->created_time);
        $contains_image = $this->containsImage($data->type, $object_id);
        $image_source = ($contains_image) ? $this->getImage($object_id) : '';
        $crop_image = false;
        $data_source = 'facebook';
        parent::__construct($message, $created_time, $created_time_unix, $image_source, $crop_image, $data_source);
    }
}

;