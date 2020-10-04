<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "image_url" => $this->image_url,
            "created_at" => $this->created_at->format("Y-m-d"),
            "comments_count" => $this->comments_count,
            "comments" => $this->comments,
            "average" => $this->average,
            "rating" => $this->ratings,
            "user" => [
                "name" => $this->user->name,
                "email" => $this->user->email,
            ]
        ];
    }
}
