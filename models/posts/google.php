<?php

class GooglePost extends Post
{
    function getImage($data)
    {
        if (isset($data->attachments)) {
            if (isset($data->attachments[0]->fullImage)){
                return $data->attachments[0]->fullImage->url;
            }
            elseif (isset($data->attachments[0]->image)){
                return $data->attachments[0]->image->url;
            }
            /*if (isset($data->attachments[0]->image)){
                return $data->attachments[0]->image->url;
            }*/
        else{
                return '';
            }
        } else {
            return '';
        }
    }


    function __construct($data)
    {
        $message = strip_tags($data->object->content);
        $created_time = date('M j, Y', strtotime($data->published));
        $created_time_unix = strtotime($data->published);
        $image_source = $this->getImage($data->object);
        $data_source = 'google';
        parent::__construct($message, $created_time, $created_time_unix, $image_source, $data_source);

    }
}