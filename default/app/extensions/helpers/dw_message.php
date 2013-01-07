<?php
/**
 * Dailyscript - Web | App | media
 *
 * Extension para el manejo de mensajes. 
 *
 * @category    Flash
 * @author      Iván D. Meléndez
 * @package     Helpers
 * @copyright   Copyright (c) 2012 Dailyscript Team (http://www.dailyscript.com.co)
 * @revision    1.1
 * 
 * Se utiliza en el método content de la clase view.php
 * if(DwMessage::has()) {
 *      DwMessage::output();
 * }
 * 
 */

class DwMessage {
    
    /**
     * Variable que contiene los diferentes mensajes repetitivos para mostrarlos en el método fixed
     * @var array
     */
    protected static $_msg = array();

    /**
     * Mensajes almacenados en un request
     */
    private static $_contentMsj = array();
        
    /**
     * Setea un mensaje dw-flash
     *
     * @param string $name Tipo de mensaje y para CSS class='$name'.
     * @param string $msg Mensaje a mostrar
     * @param boolean $logger Indica si el mensaje se almacena como logger
     */
    public static function set($name, $msg, $logger=false) {        
        //Verifico si hay mensajes almacenados en sesión por otro request.
        if(self::has('dw-messages')) {            
            self::$_contentMsj = Session::get('dw-messages');                
        }        
        //Guardo el mensaje en el array
        if (isset($_SERVER['SERVER_SOFTWARE'])) {                    
            self::$_contentMsj[] = '<div class="alert alert-block alert-'.$name.'"><button class="close" data-dismiss="alert">×</button>'.$msg.'</div>'.PHP_EOL;
        } else {
            self::$_contentMsj[] = $name.': '.Filter::get($msg, 'striptags').PHP_EOL;            
        }        
        //Almaceno los mensajes guardados en una variable de sesión, para mostrar los mensajes provenientes de otro request.
        Session::set('dw-messages', self::$_contentMsj);
        //Verifico si el mensaje se almacena como looger
        if($logger) {
            DwLogger::$name($msg);
        }            
    }
    
    /**
     * Verifica si tiene mensajes para mostrar.
     *
     * @return bool
     */
    public static function has() {
        return Session::has('dw-messages') ?  true : false;
    }
    
    /**
     * Muestra los mensajes dw-flash
     */    
    public static function output() {            
        //Asigno los mensajes almacenados en sesión en una variable temporal
        $tmp = Session::get('dw-messages');
        //Recorro los mensajes        
        foreach($tmp as $msg) {
            // Imprimo los mensajes
            echo $msg;
        }                        
        //Reinicio la variable de los mensajes
        self::$_contentMsj = array();        
        //Elimino los almacenados en sesión        
        Session::delete('dw-messages');       
    }

    /**
     * Carga un mensaje de error
     *
     * @param string $msg
     * @param boolean $logger Indica si se registra el mensaje como un logger
     */
    public static function error($msg, $logger=false) {
        self::set('error',$msg, $logger);          
    }

    /**
     * Carga un mensaje de advertencia en pantalla
     *
     * @param string $msg
     * @param boolean $logger Indica si se registra el mensaje como un logger
     */
    public static function warning($msg, $logger=false) {
        self::set('warning',$msg, $logger);
    }

    /**
     * Carga informacion en pantalla
     *
     * @param string $msg
     * @param boolean $logger Indica si se registra el mensaje como un logger
     */
    public static function info($msg, $logger=false) {
        self::set('info',$msg, $logger);
    }
    
    /**
     * Carga información de suceso correcto en pantalla
     *
     * @param string $msg
     * @param boolean $logger Indica si se registra el mensaje como un logger
     */
    public static function valid($msg, $logger=false) {
        self::set('success',$msg, $logger);
    }

    /** 
     * Carga mensajes por defecto almacenados en un array
     * 
     * @param type $num Nombre del mensaje a mostrar
     * @param type $logger 
     */
    public static function get($msg, $logger=false) {
        $message = self::$_msg[$msg];
        self::set($message[1], $message[2], $logger);        
    }    
    
}
