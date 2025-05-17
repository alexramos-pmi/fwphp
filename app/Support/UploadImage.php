<?php

namespace App\Support;

use Intervention\Image\ImageManagerStatic;

class UploadImage
{
    private $image = false;
    private $path = false;
    private $width = 500;
    private $height = 500;

    public function __construct($image = false)
    {
        if($image)
        {
            $this->image = $image;
        }
    }

    public function setFolder($folder)
    {
        if($this->image)
        {
            $nameFile = $this->image->getClientOriginalName();
            $extFile = $this->image->getClientOriginalExtension();

            $ext = ['jpeg', 'jpg', 'webp', 'png', 'gif'];

            if(in_array($extFile, $ext))
            {
                $this->path = $folder . '/' . md5(date('YmdHis') . $nameFile) . '.' . $extFile;
            }
        }

        return $this;
    }

    public function size($width, $height)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function remove($imagePath)
    {
        if($imagePath)
        {
            cropperFlush($imagePath);
        }

        return $this;
    }

    public function fit()
    {
        if($this->path)
        {
            $path = storage_path('app/public/' . $this->path);

            $result = ImageManagerStatic::make($this->image->getRealPath())
                ->fit($this->width, $this->height)
                ->save($path);

            if($result)
            {
                return $this->path;
            }

            return null;
        }

        return null;
    }
}
