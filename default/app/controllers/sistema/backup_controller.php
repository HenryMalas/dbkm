<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de las copias de seguridad del sistema
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('sistema/backup');

class BackupController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Backups';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para buscar
     */
    public function buscar($field='denominacion', $value='none', $order='order.id.asc', $page=1) {        
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $field = (Input::hasPost('field')) ? Input::post('field') : $field;
        $value = (Input::hasPost('field')) ? Input::post('value') : $value;
        
        $backup = new Backup();
        $backups = $backup->getAjaxBackup($field, $value, $order, $page);        
        if(empty($backups->items)) {
            DwMessage::info('No se han encontrado registros');
        }
        $this->backups = $backups;
        $this->order = $order;
        $this->field = $field;
        $this->value = $value;
        $this->page_title = 'Búsqueda de copias de seguridad';        
    }
    
    /**
     * Método para listar
     */
    public function listar($order='order.id.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $backup = new Backup();
        $backups = $backup->getListadoBackup($order, $page);
        if(empty($backups->items)) {
            DwMessage::warning("Por favor realiza una copia de seguridad lo antes posible.");
        }
        $this->backups = $backups;
        $this->order = $order;        
        $this->page_title = 'Listado de copias de seguridad';
    }
    
    /**
     * Método para crear
     */
    public function crear() {
        if(Input::hasPost('backup')) {
            if($backup = Backup::createBackup(Input::post('backup'))) {                
                DwMessage::valid('Se ha realizado una nueva copia de seguridad bajo el archivo <b>'.$backup->archivo.' </b> correctamente.');
                return DwRedirect::toAction('listar');
            }
        }
        $this->page_title = 'Crear copia de seguridad';
    }
    
    /**
     * Método para restaurar
     */
    public function restaurar($key='') {                  
        if(!Input::isAjax()) {
            DwMessage::error('Método incorrecto para restaurar el sistema.');
            return DwRedirect::toAction('listar');
        }        
        if(!$id = DwSecurity::isValidKey($key, 'restaurar_backup', 'int')) {
            return View::ajax();
        }        
        $pass = Input::post('password');
        $usuario = Usuario::getUsuarioLogueado();
        if($usuario->password != md5(sha1($pass))) {
            DwMessage::error('Acceso incorrecto al sistema. Tu no tienes los permisos necesarios para realizar esta acción.');
            return View::ajax();
        }
        if($backup = Backup::restoreBackup($id, 'files/backup')) {
            DwMessage::valid('El sistema se ha restaurado satisfactoriamente con la copia de seguridad <b>'.$backup->archivo.'</b>');
        } else {
            DwMessage::error('Se ha producido un error interno al restaurar el sistema. Por favor contacta al administrador.');
        }
        return View::ajax();
        
    }
    
    /**
     * Método para descargar
     */
    public function descargar($key='') {
        DwMessage::info('Esta opción no se encuentra disponible temporalmente.');
        return DwRedirect::toAction('listar');
    }
    
}

