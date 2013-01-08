<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga del logueo de los usuarios del sistema
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

Load::lib('dw_security');

class LoginController extends BackEndController {
    
    /**
     * Limite de parámetros por acción
     */
    public $limit_params = FALSE;
    
    /**
     * Nombre de la página
     */
    public $page_title = 'Entrar';
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        View::template('login');
    }
    
    /**
     * Método principal     
     */
    public function index() {        
        return DwRedirect::toAction('entrar/');
    }
    
    /**
     * Método para iniciar sesión
     */
    public function entrar() {        
        if(Input::hasPost('login') && Input::hasPost('password') && Input::hasPost('mode')) {
            if(Usuario::setSession('open')) {
                return DwRedirect::to('/');                
            }                       
        } else if(DwAuth::isLogged()) {
            return DwRedirect::to('/');
        }
    }
    
    /**
     * Método para cerrar sesión
     */
    public function salir($js='') {
        if($js == 'no-script') {
            DwMessage::info('Activa el uso de JavaScript en su navegador para poder continuar.');
        }        
        if(Usuario::setSession('close')) {
            DwMessage::valid("La sesión ha sido cerrada correctamente.");
        }
        return DwRedirect::toAction('entrar/');
    }
    
}

