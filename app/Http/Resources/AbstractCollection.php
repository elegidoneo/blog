<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class AbstractCollection extends ResourceCollection
{
    /**
     * @param $data
     * @return mixed
     */
    abstract protected function setData($data);

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request)
    {
        $data = [];
        foreach ($this->collection as $value) {
            $data[] = $this->setData($value);
        }

        return $data;
    }
}
