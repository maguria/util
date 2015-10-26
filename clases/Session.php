<?php

class Session {
    private static $init = false;
    private $trusted = true;
    
    function __construct($nombre= null) {
        if(!self::$init){
            if($nombre!=null){
                session_name($nombre);
            }
            session_start();
            self::$init = true;
            $ip = $this->get('_ip');
            $client = $this->get('_client');
            if($ip === null && $client === null){
                $this->set('_ip', Server::getClientAddres());
                $this->set('_client', Server::gerUserAgent());
            }else{
                if($ip !== Server::getClientAdrr() || $client !== Server::getUserAgent()){
                    $this->trusted = false;
                }
            }           
        }
    }
    
    function get($name){
        if(!$this->trusted){
            return null;
        }
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }
        return null;
    } 
    
    function set($name, $valor){
        if(!$this->trusted){
            return null;
        }
        $_SESSION[$name]=$valor;
    }
    
    function setUser(Usuario $usuario){
        $this_>set("_usuario", $usuario);
    }
    
    function delete($name){
        if(!$this->trusted){
            return null;
        }
        if(isset($_SESSION[$name])){
            unset($_SESSION[$name]);
        }
    }    
    function destroy(){
        if(!$this->trusted){
            return null;
        }
        session_destroy();
    }
}

