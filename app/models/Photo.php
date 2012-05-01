<?php

define('PATH_PHOTOS',PATH_ROOT.'/statics/photos');

class Photo extends Kate {
	
	public $source = array(
		'type' => 'db',
		'table' => array(
			'name' => array('p' => 'photos'),
			'primary' => 'pid',
			'fields' => '*',
			'nowFields' => 'dateadded'
		)
	);
	
	
	
	public function src($size = 'thumb') {
		
		$data = $this->getData();
		
		if(empty($data['filename'])) return '';
		
		return '/resizer/'.trim($size,'/').'/y0/f/'.ltrim($data['filename'],'/');
			
	}
	
	public function isHorizontal() {
		
		$data = $this->getData();
		
		return ((int)$data['width'] > (int)$data['height']) ? true:false;
			
	}
	
	public function isVertical() {
		
		$data = $this->getData();
		
		return ((int)$data['width'] < (int)$data['height']) ? true:false;
			
	}
	
	public function getRotation() {
		
		$data = $this->getData();
		
		return (int)$data['rotation'];
			
	}
	
	public function getCropURL() {
		$data = $this->getData();
			
		$src = '/resizer';
		
		if($this->isHorizontal()) $src .= '/w370/h155';
		else $src .= '/w240/h250';
			
		if((int)$data['crop_w'] > 0) {
			$src .= '/cw'.$data['crop_w'].'/ch'.$data['crop_h'].'/x'.$data['crop_x'].'/y'.$data['crop_y'];
		}
		
		$src .= '/ratio/f/'.$data['filename'];
		
		return $src;
	}
	
	
	/*
	 *
	 * Photo list
	 *
	 */
	
	
	public function rotate($degree) {
		
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		
		$data = $this->getData();
		
		if((int)$degree == 360) $degree = 0;
		else $degree = (int)$degree;
		
		if((int)$data['rotation'] != (int)$degree) {
			
			$file = $data['filename'];
			$filePath = substr($file,0,strrpos($file,'/'));
			$file = $filePath.'/'.$data['pid'].substr($file,strrpos($file,'.'));
			
			$newFile = $filePath.'/'.$data['pid'].'_r'.$degree.substr($file,strrpos($file,'.'));
			
			if($degree == 0) $newFile = $file;
			
			$path = dirname(__FILE__).'/../../public_html/statics/photos/';
			
			$imagesize = getimagesize($path.$file);
			$mime = $imagesize['mime'];
			
			switch($mime) {
				case 'image/gif':
					$creationFunction	= 'imagecreatefromgif';
					$outputFunction		= 'imagegif';
				break;
				
				case 'image/x-png':
				case 'image/png':
					$creationFunction	= 'imagecreatefrompng';
					$outputFunction		= 'imagepng';
				break;
				
				default:
					$creationFunction	= 'imagecreatefromjpeg';
					$outputFunction	 	= 'imagejpeg';
				break;
			}
			
			if(!empty($creationFunction) && !file_exists($path.$newFile)) {
				ini_set("memory_limit",'500M');
				$source = $creationFunction($path.$file);
				$rotate = imagerotate($source, $degree, 0);
				if($outputFunction == 'imagegif') $outputFunction($rotate,$path.$newFile);
				else $outputFunction($rotate,$path.$newFile,100);
				imagedestroy($source);
				imagedestroy($rotate);
			}
			
			$imagesize = getimagesize($path.$newFile);
			$this->setData(array(
				'filename' => $newFile,
				'rotation' => $degree,
				'width' => $imagesize[0],
				'height' => $imagesize[1]
			));
			$this->save();
			$this->fetch();
		}
		
	}
	
	
	/*
	 *
	 * Items query custom parameters
	 *
	 */
	 
	protected function _queryAvailable($select, $value) {
		
		if($value === true || $value === 1 || $value === '1' || $value === 'true') {
			$select->where('p.published = 1 AND p.deleted = 0');
		} else if($value === false || $value === 0 || $value === '0' || $value === 'false') {
			$select->where('p.published = 0 OR p.deleted = 1');
		}
		
		return $select;	
	}
	
	
	
	
	
	/*
	 *
	 * Photo uploading
	 *
	 */
	public static $IMAGES_MAXSIZE = 10000000;
	public static $IMAGES_MIMES = array(
		'image/pjpeg' => 'jpg',
		'image/jpeg' => 'jpg',
		'image/x-png' => 'png',
		'image/png' => 'png',
		'image/gif' => 'gif'
	);
	
	public static function addPhoto($file,$uploadKey = '') {
		
		$Photo = new Photo();
		$Photo->setData(array(
			'uploadKey' => $uploadKey,
			'owner' => (Gregory::get()->auth->isLogged()) ? Gregory::get()->auth->getIdentity()->uid:0,
			'published' => 1
		));
		$Photo->save();
		
		try {
			$id = $Photo->getPrimary();
			$folder = date('Ymd').'/'.date('H');
			$path = PATH_PHOTOS.'/'.$folder;
			
			$photo = self::process($file,$id,$path);
		
		} catch(Exception $e) {
			$Photo->cancel();
			throw $e;	
		}
		
		try {
			$Photo->setData(array(
				'width' => $photo['width'],
				'height' => $photo['height'],
				'size' => $photo['size'],
				'original' => $photo['original'],
				'filename' => $folder.'/'.$photo['filename']
			));
			$Photo->save();
		} catch(Exception $e) {
			$Photo->cancel();
			throw new Exception('Il s\'est produit une erreur avec l\'envoi de photo');	
		}
		
		return $Photo;
		
	}
	 
	public static function process($file,$filename,$path) {
		
		if(is_array($file) && isset($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			
			list($width, $height, $type, $attr) = getimagesize($file_tmp);
			$file_type = image_type_to_mime_type($type);
			
		} elseif(is_string($file) && !empty($file)) {
			if(file_exists($file)) {
				$file_tmp = $file;
				$file_name = basename($file);
			} else {
				$content = @file_get_contents($file);
				if(!$content) throw new Exception("La photo doit être au format JPEG, GIF ou PNG.");
				$file_tmp = $path.'/'.$filename.".tmp";
				file_put_contents($file_tmp,$content);
			}
			$file_size = filesize($file_tmp);
			list($width, $height, $type, $attr) = getimagesize($file_tmp);
			$file_type = image_type_to_mime_type($type);
		} else {
			throw new Exception("La photo doit être au format JPEG, GIF ou PNG.");
		}
		
		if(file_exists($file_tmp)){
		
			if($file_size){
				if($file_size > self::$IMAGES_MAXSIZE) throw new Exception("Le poids de cette photo dépasse les 100 mo autorisés.");

				if(isset(self::$IMAGES_MIMES[$file_type])) $ext = self::$IMAGES_MIMES[$file_type];
				else throw new Exception(("La photo doit être au format JPEG, GIF ou PNG."));
				
				$path = '/'.trim($path,'/');
				
				if(!file_exists($path)) mkdir($path,0774,true);
				
				$newfilename = $path.'/'.$filename.".".$ext;
				$original = isset($file_name) ? $file_name:$filename.".".$ext;

				if(file_exists($newfilename)) unlink($newfilename);
				
				copy($file_tmp,$newfilename);
				
				unlink($file_tmp);
				
				return array("original"=>$original,"filename"=>$filename.".".$ext,"path"=>$newfilename,"ext"=>$ext,"width"=>$width,"height"=>$height,"size"=>$file_size);
				
			} else {
				throw new Exception("L'image envoyée est vide");
			}
			
		} else {
			throw new Exception("Il s'est produit une erreur avec l'envoi d'image.");
		}
		
	}
	
	
	public static function safeItems($photos) {
		
		$return = array();
		foreach($photos as $photo) {
			$Photo = new Photo();
			$Photo->setData($photo);
			$photo['cropurl'] = $Photo->getCropURL();
			unset($photo['size']);
			unset($photo['original']);
			unset($photo['uploadKey']);
			unset($photo['published']);
			unset($photo['dateadded']);
			$return[] = $photo;	
		}
		
		return $return;
		
	}
	
	
}