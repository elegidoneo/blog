<?php

namespace App\Http\Resources;

class CommentCollection extends AbstractCollection
{

    protected function setData($data)
    {
        return [
            "comment" => $data->comment,
            "user" => [
                "name" => $data->user->name,
                "email" => $data->user->email,
            ],
            "post" => [
                "title" => $data->post->title,
                "body" => $data->post->body,
                "image_url" => $data->post->image_url,
            ],
        ];
    }
}
