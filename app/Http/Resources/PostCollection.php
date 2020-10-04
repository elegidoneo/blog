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
            "id" => $data->id,
            "title" => $data->title,
            "body" => $data->body,
            "image_url" => $data->image_url,
            "created_at" => $data->created_at->format("Y-m-d"),
            "comments_count" => $data->comments_count,
            "comments" => $data->comments,
            "average" => $data->average,
            "rating" => $data->ratings,
            "user" => [
                "name" => $data->user->name,
                "email" => $data->user->email,
            ]
        ];
    }
}
