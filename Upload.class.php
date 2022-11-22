<?php
require_once('appKonstanten.php');

class Upload{
	
	//public $src="uploadedFiles/";
	private $src=IMAGESPFAD;
	private $tmp;
	private $filename;
	private $type;
	private $size;
	private $uploadFilename;
	
	public function __construct($filename, $tmp, $type, $size){
		
			$this->filename = $filename;
			$this->tmp = $tmp;
			$this->type = $type;
			$this->size = $size;
			$this->uploadFilename =  $this->src . basename($this->filename);
	
	}
	
	public function doFileUpload(){
		if(is_dir($this->src) && move_uploaded_file($this->tmp, $this->uploadFilename)){
			return true;
		}else{
			return false;
		}
	}
	
	public function getFilename():String{
		return $this->filename;
	}
	
	public function getUploadFilename():String{
		return $this->uploadFilename;
	}
	
	public function getTmpName():String{
		return $this->tmp;
	}
	
	public function getSize():int{
		return $this->size;
	}
	
	public function checkImage():bool{
		
		if ((($this->type == 'image/gif') || ($this->type == 'image/jpeg') || 
		     ($this->type == 'image/pjpeg') || ($this->type == 'image/png')) && 
			 (($this->size > 0) && ($this->size <= MAXDATEIGROESSE))) {
			
			//Bild darf gespeichert werden
			return true;
		}else {
			//Bild darf nicht gespeichert werden, da Größe oder Typ nicht stimmt
			return false;
		}
	}
}
?>