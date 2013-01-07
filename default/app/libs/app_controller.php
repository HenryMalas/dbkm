<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */

//Cargo los parámetros de configuración
DwConfig::Load();

class AppController extends Controller {
    
    /**
    * Titulo de la página
    */
    public $page_title = 'Página sin título';
    
    /**
    * Nombre del módulo en el que se encuentra
    */
    public $page_module = 'Indefinido';

    /**
    * Tipo de formato del reporte
    */
    public $page_format;
    
    /**
     * Variable que indica el cambio de título de la página en las respuestas ajax
     */
    public $set_title = true;

    /**
     * Callback que se ejecuta antes de los métodos de todos los controladores
     */
    final protected function initialize() {
        /**
         * Si el método de entrada es ajax, el tipo de respuesta es sólo la vista
         */
        if(Input::isAjax()) {
            View::template(null);
        }
        
        /**
         * Verifico que haya iniciado sesión
         */
        if( !DwAuth::isLogged() && $this->controller_name != 'pages' ) {
            //Verifico que no genere una redirección infinita
            if( ($this->controller_name != 'login') && ( $this->action_name != 'entrar' && $this->action_name != 'salir') ) {
                DwMessage::warning('No has iniciado sesión o ha caducado.');
                //Verifico que no sea una ventana emergente
                if($this->module_name == 'reporte') {
                    View::error();
                } else {        
                    //Si la petición es con ajax, cambio la vista
                    if(Input::isAjax()) {
                        View::redirectToLogin();                        
                    } else {
                        Router::redirect('login/entrar/');                        
                    }
                }
                return false;
            } 
        } else if( DwAuth::isLogged() && $this->controller_name!='login' ) {
            $acl = new DwAcl();
            if (!$acl->check(Session::get('perfil_id'))) {
                DwMessage::error('Tu no posees privilegios para acceder a <b>' . Router::get('route') . '</b>');
                (Input::isAjax()) ? View::ajax() : View::select(NULL);
                return false;
            }
            if(!defined('SKIN')) {
                define('SKIN', Session::get('tema'));
            }                       
        }

    }

    /**
     * Callback que se ejecuta después de los métodos de todos los controladores
     */
    final protected function finalize() {        
        if(defined('APP_CLIENT')) {
            $this->page_title = trim($this->page_title).' | '.APP_CLIENT.' ‹ '.APP_NAME;
        } else {	                    
            $this->page_title = trim($this->page_title).' ‹ '.APP_NAME;          
        }         
        //Se muestra la vista según el tipo de reporte
        if(Router::get('module') == 'reporte') {                        
            View::report($this->page_format);            
        }        
        //Se verifica si se cambia el título de la página
        if($this->set_title && Input::isAjax()) {
            $this->set_title = TRUE;
        }
    }

}
