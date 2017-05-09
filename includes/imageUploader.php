<?php
function uploadFile($file,$target,$type,$resize,$sizearr)
{
	$defaultImageDimension = is_array($sizearr) ? $sizearr : array('256','256');
	$error='';
	if(!$file['error'])
	{
		$allowed = getMIMEArray($type);
		if(in_array($file['type'],$allowed))
		{
			//list($filename,$fileext) = explode(".",basename($file['name']));
			
			$name_parts = pathinfo($target);
			
 			$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

			$path = $name_parts['dirname']."/".time().mt_rand(9,99999).".".$name_parts['extension'];
			//print_r($name_parts);exit;
			//$path=$target;
 			if(@move_uploaded_file($file['tmp_name'],$path))
			{
				$error 		= 0;
				$pic 		= $path;				
				$iSizes  	= getimagesize($path);				
				if($resize and $type === "IMAGE")
				{
					if(($iSizes[0] >  $defaultImageDimension[0]) or ($iSizes[1] > $defaultImageDimension[1]))
					{						
						$pic 	= createthumb($path,$defaultImageDimension[0],$defaultImageDimension[1],$target);
						@unlink($path);						
					}else{
						//@unlink($path);
						@rename($pic,$target);
						//$pic = 
					}
				}
			}else{$error = 3;}
				
		}else{$error = 2;}
		
	}else{$error = 1;}
	
	if(!$error)
		{return $pic;}
	else
		{return $error;}
 }
//==============Creating Thumbnail ==============//
function createthumb($img,$constrainw,$constrainh,$target)
{
	$oldsize = getimagesize($img);
	$newsize = array($constrainw, $constrainh);
	$info    = pathinfo($img);
	//print_r($img);exit();
	//$exp = explode (".", $img);
	//Check if you need a gif or jpeg.
	if(mb_strtolower($info['extension']) == "gif")
	{
		$src = imagecreatefromgif($img);
	}
	else if(mb_strtolower($info['extension']) == "jpg" or mb_strtolower($info['extension']) == "jpeg")
	{
		$src = imagecreatefromjpeg($img);
	}
	else if(mb_strtolower($info['extension']) == "png")
	{
		$src = imagecreatefrompng($img);
	}
	//Make a true type dupe.
	$dst = imagecreatetruecolor($newsize[0],$newsize[1]);
	//Resample it.
	imagecopyresampled($dst,$src,0,0,0,0,$newsize[0],$newsize[1],$oldsize[0],$oldsize[1]);
	//Create a thumbnail.
	//$thumbname = $info['dirname']."/".mt_rand(time(),intval(time()+2000)).".".$info['extension'];
	$thumbname = $target;
	if(mb_strtolower($info['extension']) == "gif")
	{
		imagegif ($dst,$thumbname);
	}
	else if(mb_strtolower($info['extension']) == "jpg" or mb_strtolower($info['extension']) == "jpeg")
	{
		imagejpeg ($dst,$thumbname);
	}
	else if(mb_strtolower($info['extension']) == "png")
	{
		imagepng ($dst,$thumbname);
	}
	imagedestroy ($dst);
	imagedestroy ($src);
	return $thumbname;
 }
 
function getMIMEArray($type)
{
	$mime = array();
	switch(mb_strtoupper($type)){
		case 'IMAGE' : $mime = array('image/gif','image/jpeg','image/pjpeg','image/png'); break;
		case 'DOCUMENT' : $mime = array('application/pdf','text/plain'); break;
		case 'FLASH' : $mime = array('application/x-shockwave-flash'); break;
		case 'COMPRESSEDFILE' : $mime = array('application/x-rar-compressed','application/octet-stream','application/zip','application/octet-stream'); break;
		default: ;
	}
	return $mime;
 }
?>