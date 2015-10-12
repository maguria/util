<?php

class FileUpload {
 
    //Validamos el tamaño del archivo
    
    static function fileSize($archivo, $tam)
    {
        if($_FILES[$archivo]["size"]<$tam)
        {
            return true;
        }
        else
        {
        return self::errorType($archivo);
        }
        
    }
    
    //Validamos las extensiones permitidas del archivo
    
    static function fileExtension($archivo)
    {
        $e=explode(".",$_FILES[$archivo]["name"]);
        if($e=="jpg"||$e=="png"||$e=="gif")
        {
            return true;
        }
        else
        {
            return self::errorType($archivo);
        }
    }
    
    
    //Hacemos un switch para escribir los posibles errores
    
    private static function errorType($archivo)
    {
        $error=$FILES_[$archivo]["error"];
        
        switch($error)
        {
            case 0: echo "Archivo subido con éxito";
            case 1: echo "El fichero subido excede la directiva upload_max_filesize de php.ini.";
            case 2: echo "El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
            case 3: echo " El fichero fue sólo parcialmente subido.";
            case 4: echo "No se subió ningún fichero.";
            case 6: echo "Falta la carpeta temporal.";
            case 7: echo "No se pudo escribir el fichero en el disco.";
            case 8: echo "Una extensión de PHP detuvo la subida de ficheros.";
                
        }
    }
    
    
    //Subimos el archivo y renombramos si ya existe
    
    static function upFile($archivo,$a)
    {
        if((self::fileExtension($archivo))&& (self::fileSize($archivo, $tam))&&(file_exists($archivo)))
        {
           move_uploaded_file($_FILES[$archivo]["tmp_name"],$a,$newName);
        }
        else
        {
           move_uploaded_file($_FILES[$archivo]["tmp_name"],$a);  
        }
    }
}
