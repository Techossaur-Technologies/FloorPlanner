<?php

class imageGrid
{

    private $realWidth;
    private $realHeight;
    private $gridWidth;
    private $gridHeight;
    private $image;

    public function __construct($realWidth, $realHeight, $gridWidth, $gridHeight)
    {
        $this->realWidth = $realWidth;
        $this->realHeight = $realHeight;
        $this->gridWidth = $gridWidth;
        $this->gridHeight = $gridHeight;

        // create destination image
        $this->image = imagecreatetruecolor($realWidth, $realHeight);

        // set image default background
        $white = imagecolorallocate($this->image, 255, 255, 255);
        imagefill($this->image, 0, 0, $white);
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }

    public function display($imagePath)
    {
        // header("Content-type: image/png");
        // imagepng($this->image);
        return imagejpeg($this->image, $imagePath);
        imagedestroy($im);
    }

    public function demoGrid()
	{
	    $black = imagecolorallocate($this->image, 0, 0, 0);
	    imagesetthickness($this->image, 3);
	    $cellWidth = ($this->realWidth - 1) / $this->gridWidth;   // note: -1 to avoid writting
	    $cellHeight = ($this->realHeight - 1) / $this->gridHeight; // a pixel outside the image
	    for ($x = 0; ($x <= $this->gridWidth); $x++)
	    {
	        for ($y = 0; ($y <= $this->gridHeight); $y++)
	        {
	            imageline($this->image, ($x * $cellWidth), 0, ($x * $cellWidth), $this->realHeight, $black);
	            imageline($this->image, 0, ($y * $cellHeight), $this->realWidth, ($y * $cellHeight), $black);
	        }
	    }
	}

	public function demoPutSquare($sizeW, $sizeH, $posX, $posY, $color)
	{
	    // Cell width
	    $cellWidth = $this->realWidth / $this->gridWidth;
	    $cellHeight = $this->realHeight / $this->gridHeight;

	    // Conversion of our virtual sizes/positions to real ones
	    $realSizeW = ($cellWidth * $sizeW);
	    $realSizeH = ($cellHeight * $sizeH);
	    $realPosX = ($cellWidth * $posX);
	    $realPosY = ($cellHeight * $posY);

	    // Getting top left and bottom right of our rectangle
	    $topLeftX = $realPosX;
	    $topLeftY = $realPosY;
	    $bottomRightX = $realPosX + $realSizeW;
	    $bottomRightY = $realPosY + $realSizeH;

	    // Displaying rectangle
	    $red = imagecolorallocate($this->image, 100, 0, 0);
	    $green = imagecolorallocate($this->image, 0, 100, 0);
	    $blue = imagecolorallocate($this->image, 0, 0, 100);
	    if($color == 'R')
	    imagefilledrectangle($this->image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY, $red);
		if($color == 'G')
	    imagefilledrectangle($this->image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY, $green);
		if($color == 'B')
	    imagefilledrectangle($this->image, $topLeftX, $topLeftY, $bottomRightX, $bottomRightY, $blue);
	}

	public function putImage($img, $sizeW, $sizeH, $posX, $posY)
	{
	    // Cell width
	    $cellWidth = $this->realWidth / $this->gridWidth;
	    $cellHeight = $this->realHeight / $this->gridHeight;

	    // Conversion of our virtual sizes/positions to real ones
	    $realSizeW = ceil($cellWidth * $sizeW);
	    $realSizeH = ceil($cellHeight * $sizeH);
	    $realPosX = ($cellWidth * $posX);
	    $realPosY = ($cellHeight * $posY);

	    //$img = $this->resizePreservingAspectRatio($img, $realSizeW, $realSizeH);
	    // Copying the image
	    imagecopyresampled($this->image, $img, $realPosX, $realPosY, 0, 0, $realSizeW, $realSizeH, imagesx($img), imagesy($img));
	}

	public function resizePreservingAspectRatio($img, $targetWidth, $targetHeight)
	{
	    $srcWidth = imagesx($img);
	    $srcHeight = imagesy($img);

	    $srcRatio = $srcWidth / $srcHeight;
	    $targetRatio = $targetWidth / $targetHeight;
	    if (($srcWidth <= $targetWidth) && ($srcHeight <= $targetHeight))
	    {
	        $imgTargetWidth = $srcWidth;
	        $imgTargetHeight = $srcHeight;
	    }
	    else if ($targetRatio > $srcRatio)
	    {
	        $imgTargetWidth = (int) ($targetHeight * $srcRatio);
	        $imgTargetHeight = $targetHeight;
	    }
	    else
	    {
	        $imgTargetWidth = $targetWidth;
	        $imgTargetHeight = (int) ($targetWidth / $srcRatio);
	    }

	    $targetImg = imagecreatetruecolor($targetWidth, $targetHeight);

	    imagecopyresampled(
	       $targetImg,
	       $img,
	       ($targetWidth - $imgTargetWidth) / 2, // centered
	       ($targetHeight - $imgTargetHeight) / 2, // centered
	       0,
	       0,
	       $imgTargetWidth,
	       $imgTargetHeight,
	       $srcWidth,
	       $srcHeight
	    );

	    return $targetImg;
	}

}