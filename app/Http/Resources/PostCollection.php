<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PostCollection extends AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    protected function setData($data)
    {
        return [
            "title" => $data->title,
            "body" => $data->body,
            "image_url" => $data->image_url,
            "created_at" => $data->created_at->format("Y-m-d"),
            "user" => [
                "name" => $data->user->name,
                "email" => $data->user->email,
            ]
        ];
    }
}
