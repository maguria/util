<?php


class FileUpload {
    const CONSERVAR = 1, REEMPLAZAR = 2, RENOMBRAR = 3;
    private $destino="./", $nombre="", $tamaño=1000000,$parametro,$extension,$error=false, $politica = self::RENOMBRAR;
//tipo, archivos
    private $arrayDeTipos = array(
        "jpg"=>1,
        "gif"=>1,
        "png"=>1,
        "jpeg"=>1
    );
    
    function __construct($parametro) {
        
        if(isset($_FILES[$parametro]) && $_FILES[$parametro]["name"]!==""){
        $this->parametro=$parametro;
        //$this->extension= PATHINFO_EXTENSION($_FILES[$parametro]["name"]);
        $this->extension=  pathinfo($_FILES[$parametro]["name"])["extension"];
        $this->nombre = pathinfo($_FILES[$parametro]["name"])["filename"];
        }
        else{$this->error=true;}
    }

    public function getDestino() {
        return $this->destino;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTamaño() {
        return $this->tamaño;
    }
    public function getExtension(){
        return $this->extension;
        
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setTamaño($tamaño) {
        $this->tamaño = $tamaño;
    }
    public function getPolitica(){
        return $this->politica;
    }
    public function setPolitica($politica){
        $this->politica = $politica;
    }

        public function addTipo($tipo){
        if(!$this->isTipo($tipo)){
            $this->arrayDeTipos[$tipo]=1;
            return true;
        }
        return false;
    }
    public function removeTipo($tipo){
       if($this->isTipo($tipo)){
            unset($this->arrayDeTipos[$this]);
            return true;
        }
        return false;
    }
    public function isTipo($tipo){
        return isset($this->arrayDeTipos[$tipo]);
        
        
    }

    public function upload(){
      if($_FILES[$this->parametro]["error"]!=UPLOAD_ERR_OK){
          return false;
      }  
      if($_FILES[$this->parametro]["size"]>$this->tamaño){
          return false;
      }
      if(!$this->isTipo($this->extension)){
      return false;
      }
      if(!is_dir($this->destino) && substr($this->destino, -1) === "/"){
          return false;
      }
      if($this->politica===self::CONSERVAR && file_exists($this->destino.$this->nombre.".".$this->extension)){
          return false;
      }
      $nombre = $this->nombre;
      if($this->politica===self::RENOMBRAR && file_exists($this->destino.$this->nombre.".".$this->extension)){
          $nombre= $this->renombrar($nombre);
          
      }
      
      return  move_uploaded_file($_FILES[$this->parametro]["tmp_name"],$this->destino.$nombre.".".$this->extension);
      
      
      
      }
    
    private function renombrar($nombre){
        $i=1;
        while (file_exists($this->destino.$this->nombre."(".$i.")".".".$this->extension)){
            $i++;
        }
        return $nombre."(".$i.")";
    }
    
     private static function multiFiles($multiFiles){
        $numFiles = count($multiFiles["name"]);
        $files = array();
        for($i=0;$i<$numFiles;$i++){
            
            $files[$i]['name']=$multiFiles['name'][$i];
            $files[$i]['type']=$multiFiles['type'][$i];
            $files[$i] ['tmp_name']=$multiFiles['tmp_name'][$i];
            $files[$i]['error']=$multiFiles['error'][$i];
            $files[$i]['size']=$multiFiles['size'][$i];
        }
        return $files;
    }
    
     static function subirMulti($parametro)
     {
         $cambioArray=self::multiFiles($parametro);
         
         for($i=0;$i<count($cambioArray);$i++)
         {
             $sube=new FileUpload($cambioArray[$i]);
         }
     }
     
    
}

