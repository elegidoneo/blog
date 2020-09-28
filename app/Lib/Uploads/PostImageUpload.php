<?php

namespace App\Lib\Uploads;

class PostImageUpload extends UploadFile
{
    /**
     * @var string
     */
    protected $path = "posts";

    /**
     * @var string
     */
    protected $input = "image";
}
