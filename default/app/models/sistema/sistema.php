<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Clase que permite el mantenimiento a las tablas de la base de datos
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class Sistema {
    
    /**
     * Variable que contiene las tablas del sistema
     * @var type 
     */
    protected $_tables = array();
    
    /**
     * Varible que contiene la conexión
     */
    protected $_db;
    
    /**
     * Variable con el pull de conexión
     */
    protected $_database;

    /**
     * Método contructor
     */
    public function __construct() {
        //Reviso la configuración actual
        $config = Config::read('config');
        $this->_database =  $config['application']['database'];
        //Conecto a la bd
        $this->_connect();
        //Cargo las tablas
        $this->_loadTables();
    }
    
    /**
     * Se conecta a la base de datos 
     *
     * @param boolean $new_connection
     */
    protected function _connect($new_connection = false) {
        if (!is_object($this->_db) || $new_connection) {
            $this->_db = Db::factory($this->_database, $new_connection);
        }        
    }
    
    /**
     * Método almacenar las tablas
     */
    protected function _loadTables() {
        $tablas = $this->_db->list_tables();
        foreach($tablas as $tabla) {            
            $this->_tables[] = $tabla[0];
        }
    }
    
    /**
     * Método para listar las tablas
     */
    public function getEstadoTablas() { 
        $all_status = array();        
        $tables = $this->_db->query("SHOW TABLE STATUS"); 
        foreach($tables as $table) {
            $status = $this->_db->fetch_all('CHECK TABLE '.$table['Name']);
            $status = $status[0];
            $table['Op'] = $status['Op'];
            $table['Msg_type'] = $status['Msg_type'];
            $table['Msg_text'] = $status['Msg_text'];            
            $all_status[] = $table;            
        }                
        return $all_status;        
    }
    
    /**
     * Método para desfragmentar una tabla
     */
    public function getDesfragmentacion($tabla) {
        if(in_array($tabla, $this->_tables)) {
            return ($this->_db->query("ALTER TABLE $tabla ENGINE=INNODB"));            
        } else {
            return FALSE;
        }
    }
    
    /**
     * Método para vaciar el cache de una tabla
     */
    public function getVaciadoCache($tabla) {
        if(in_array($tabla, $this->_tables)) {
            return ($this->_db->query("FLUSH TABLE $tabla"));            
        } else {
            return FALSE;
        }
    }
    
    /**
     * Método para reparar una tabla
     */
    public function getReparacionTabla($tabla) {
        if(in_array($tabla, $this->_tables)) {
            return ($this->_db->query("REPAIR TABLE $tabla"));
        } else {
            return FALSE;
        }
    }
    
    /**
     * Método para optimizar una tabla
     */    
    public function getOptimizacion($tabla) {
        if(in_array($tabla, $this->_tables)) {
            return ($this->_db->query("OPTIMIZE TABLE $tabla"));            
        } else {
            return FALSE;
        }
    }
    
}
?>
