<?php


namespace App\Lib\Uploads;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class UploadFile
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    protected $path = "";

    /**
     * @var string
     */
    protected $input = "";

    /**
     * UploadFile constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return false|string
     */
    public function upload()
    {
        $file = $this->request->file($this->input);
        return $file->storeAs($this->path, $file->getClientOriginalName());
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath)
    {
        return Storage::delete($filePath);
    }
}
