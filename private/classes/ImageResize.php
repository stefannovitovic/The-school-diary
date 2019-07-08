<?php

//klasa za resize slika, brisanje originala i kreiranje kopija

class ImageResize {
    protected $image = array ();
    protected $file;
    protected $mimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    protected $outputSizes = [];
    protected $useLongerDimension;
    protected $jpegQuality = 75;
    protected $pngCompression = 0;
    protected $resample = IMG_BILINEAR_FIXED;
    protected $destroyOriginal;
    protected $destination;
    protected $generated = [];
    protected $messages = array();
    protected $storedSizes = array();
    protected $useOriginalFileSizeNames;

    public function __construct($image, $destroyOriginal = false)  {
        $this->image['file'] = $image;
        $this->destroyOriginal = $destroyOriginal;
        $this->checkImage();
    }

    public function setOutputSizes(array $sizes, $useOriginalFileSizeNames = true, $useLongerDimension = true) {
        foreach($sizes as $size) {
            if(!is_numeric($size) || $size <= 0) {
                throw new Exception('Sizes must be array of pos. numbers');
            }
            $this->outputSizes[] = (int) $size;
        }
        $this->useLongerDimension = $useLongerDimension;
        $this->useOriginalFileSizeNames = $useOriginalFileSizeNames;
        if($useOriginalFileSizeNames) {
            $this->storedSizes = $this->outputSizes;
        }
    }

    public function setJpegQuality($number) {
        if (!is_numeric($number) || $number < 0 || $number > 100) {
            throw new \Exception('JPG Quality must be a number in the range 0-100.');
        }
        $this->jpegQuality = $number;
    }

    public function setPngCompression($number) {
        if (!is_numeric($number) || $number < 0 || $number > 9) {
            throw new \Exception('PNG Compression must be a number from 0 (no compression) to 9.');
        }
        $this->pngCompression = $number;
    }

    public function setResamplingMethod($value)
    {
        switch (strtolower($value)) {
            case 'bicubic':
                $this->resample = IMG_BICUBIC;
                break;
            case 'bicubic-fixed':
                $this->resample = IMG_BICUBIC_FIXED;
                break;
            case 'nearest-neighbour':
            case 'nearest-neighbor':
                $this->resample = IMG_NEAREST_NEIGHBOUR;
                break;
            default:
                $this->resample = IMG_BILINEAR_FIXED;
        }
    }

    public function outputImage($destination) {
        if (!is_dir($destination) || !is_writable($destination)) {
            throw new Exception('The destination must be a writable directory.');
        }
        $this->destination = $destination;
        $resource = $this->createImageResource($this->image['file'], $this->image['type'] );
        $this->generateOutput($this->image, $resource);
        imagedestroy($resource);
        if($this->destroyOriginal && !empty($this->generated)) {
            unlink($this->image['file']); // brise originalni fajl
        }
        if(empty($this->generated)) {
            $newFileName = str_replace('.','_'.$this->storedSizes[0].'.',$this->image['file']);
            rename($this->image['file'], $newFileName);
            $this->generated[] = $newFileName;
        }
        return ['output' => $this->generated];
    }

    public function deleteSimilar() {
        if(!$this->useOriginalFileSizeNames) {
            return;
        }
        $fileTypes = ['jpg', 'png', 'gif', 'webp'];
        $fileType = pathinfo($this->generated[0]);
        $baseName = strtok($fileType['basename'],'_');

        if(count($this->generated) != 1) {
            unset($fileTypes[array_search(strtolower($fileType['extension']),$fileTypes)]);
        } else {
            unset($this->storedSizes[0]);
            $this->storedSizes = array_values($this->storedSizes);
        }

        foreach($fileTypes as $type) {
            for($i=0, $c = count($this->storedSizes); $i < $c; $i++) {
                $file = $this->destination.$baseName."_".$this->storedSizes[$i].".".$type;
                if(file_exists($file)) {
                    unlink($file);
                }
            }
        }

    }

    protected function checkImage() {
        if(file_exists($this->image['file']) && is_readable($this->image['file'])) {
            $size = getimagesize($this->image['file']);
            if($size === false && mime_content_type($this->image['file']) == 'image/webp') {
                $this->image = $this->getWebpDetails($this->image['file']);
            } else if($size[0] === 0 || !in_array($size['mime'], $this->mimeTypes)) {
                $this->messages[]="type ne valja";
            } else {
                if($size['mime'] == 'image/jpeg') {
                    $result= $this->checkJpegOrientation($this->image['file'], $size);
                    $this->image['file'] = $result['file'];
                    $size = $result['size'];
                }
                $this->image['w']       = $size[0];
                $this->image['h']       = $size[1];
                $this->image['type']    = $size['mime'];
            }

        } else {
            $this->messages[] = "fajl ne postoji";
        }
    }

    protected function getWebpDetails($image)
    {
        $details = [];
        $resource = imagecreatefromwebp($image);
        $details['file'] = $image;
        $details['w'] = imagesx($resource);
        $details['h'] = imagesy($resource);
        $details['type'] = 'image/webp';
        imagedestroy($resource);
        return $details;
    }

    protected function checkJpegOrientation($image, $size)
    {
        $outputFile = $image;
        $exif = exif_read_data($image);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $angle = 180;
                    break;
                case 6:
                    $angle = -90;
                    break;
                case 8:
                    $angle = 90;
                    break;
                default:
                    $angle = null;
            }
            // If necessary, rotate the image
            if (!is_null($angle)) {
                $original = imagecreatefromjpeg($image);
                $rotated = imagerotate($original, $angle, 0);
                // Save the rotated file with a new name
                $extension = pathinfo($image, PATHINFO_EXTENSION);
                $outputFile = str_replace(".$extension", '_rotated.jpg', $image);
                imagejpeg($rotated, $outputFile, 100);
                // Get the dimensions and MIME type of the rotated file
                $size = getimagesize($outputFile);
                imagedestroy($original);
                imagedestroy($rotated);
            }
        }
        return ['file' => $outputFile, 'size' => $size];
    }

    protected function createImageResource($file, $type)
    {
        switch ($type) {
            case 'image/jpeg':
                return imagecreatefromjpeg($file);
            case 'image/png';
                return imagecreatefrompng($file);
            case 'image/gif':
                return imagecreatefromgif($file);
            case 'image/webp':
                return imagecreatefromwebp($file);
        }
    }

    protected function generateOutput($image, $resource)
    {
        $nameParts = pathinfo($image['file']);
        if ($this->useLongerDimension && imagesy($resource) > imagesx($resource)) {
            $this->recalculateSizes($resource);
        }

        for($i=0, $c = count($this->outputSizes); $i < $c; $i++) {
            if($this->outputSizes[$i] >= $image['w'] ) {
                //TODO ovde samo kopiraj fajl sa novim imenom
//
                continue;
            }
            $scaled = imagescale($resource, $this->outputSizes[$i], -1, $this->resample);
            if($this->useOriginalFileSizeNames) { //
                $filename = $nameParts['filename'] . '_' . $this->storedSizes[$i] . '.' . $nameParts['extension'];
            } else $filename = $nameParts['filename'] . '_' . $this->outputSizes[$i] . '.' . $nameParts['extension'];
            $this->outputFile($scaled, $image['type'], $filename);
        }

    }

    protected function recalculateSizes($resource)
    {
        $w = imagesx($resource);
        $h = imagesy($resource);
        foreach ($this->outputSizes as &$size) {
            $size = round($size * $w / $h, -1);
        }
    }

    protected function outputFile($scaled, $type, $name)
    {
        $success = false;
        $outputFile = $this->destination . $name;
        switch ($type) {
            case 'image/jpeg':
                $success = imagejpeg($scaled, $outputFile, $this->jpegQuality);
                break;
            case 'image/png':
                $success = imagepng($scaled, $outputFile, $this->pngCompression);
                break;
            case 'image/gif':
                $success = imagegif($scaled, $outputFile);
                break;
            case 'image/webp':
                $success = imagewebp($scaled, $outputFile);
        }
        imagedestroy($scaled);
        if ($success) {
            $this->generated[] = $outputFile;
        }
    }

}