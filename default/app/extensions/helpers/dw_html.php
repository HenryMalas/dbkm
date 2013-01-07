<?php
/**
 * Dailyscript - Web | App | media
 *
 * Extension para el manejo de algunas etiquetas html
 *
 * @category    Helpers
 * @author      Iván D. Meléndez
 * @package     Helpers
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class DwHtml extends Html {
    
    /**
     * Método para genera un link con ícono
     * @param string $action
     * @param type $text
     * @param type $attrs
     * @param type $icon
     * @param type $loadAjax
     * @return type
     */
    public static function link ($action, $text, $attrs = NULL, $icon='', $loadAjax = TRUE) {
        if (is_array($attrs)) {
            if(APP_AJAX or $loadAjax) {
                $attrs['class'] = (empty($attrs['class'])) ? 'dw-ajax' : $attrs['class']. ' dw-ajax';
            }
            $attrs = Tag::getAttrs($attrs);
        }
        $action = trim($action, '/').'/';
        if($icon) {
            $text = "<i class=\"$icon icon-expand\"></i> $text";
        }
        return '<a href="' . PUBLIC_PATH . "$action\" $attrs >$text</a>";
    }
    
    
    /**
     * Método para generar un link tipo botón
     * @param string $action
     * @param string $text
     * @param array $attrs
     * @param string $icon
     * @param boolean $loadAjax
     * @return type
     */
    public static function button($action, $text = NULL, $attrs = NULL, $icon='', $loadAjax = TRUE) {
        if (is_array($attrs)) {
            if($loadAjax) {
                if(empty($attrs['class'])) {
                    $attrs['class'] = 'dw-ajax dw-spinner';
                } else {                                                            
                    if(!preg_match("/\bno-ajax\b/i", $attrs['class'])) {
                        $attrs['class'] = 'dw-ajax '.$attrs['class'];
                    }                     
                    if(!preg_match("/\bno-spinner\b/i", $attrs['class'])) {
                        $attrs['class'] = 'dw-spinner '.$attrs['class'];
                    } 
                }                                   
            }
            $attrs['class'] = 'btn '.$attrs['class'];
            if(!preg_match("/\bdw-text-bold\b/i", $attrs['class'])) {
                $attrs['class'] = $attrs['class'].' dw-text-bold';
            }            
            $attrs = Tag::getAttrs($attrs);
        } 
        $action = trim($action, '/').'/';
        $text = (!empty($text) && $icon) ? '<span class="hidden-phone">'.strtoupper($text).'</span>' : strtoupper($text);
        if($icon) {
            $text = '<i class="btn-icon-only icon-'.$icon.'"></i> '.$text;
        }
        return '<a href="' . PUBLIC_PATH . "$action\" $attrs >$text</a>";
    }

        
    
    /**
     * Crea un enlace externo
     * 
     * @example echo DwHtml::outLink('http://dailyscript.com.co', 'Enlace') Crea un enlace a esa url
     *
     * @param string $action Ruta o dirección de la página web
     * @param string $text Texto a mostrar
     * @param mixed $attrs Atributos adicionales
     * @return string
     */
    public static function outLink($url, $text, $attrs=NULL) {
        if (is_array($attrs)) {
            $attrs = Tag::getAttrs($attrs);
        }
        return '<a href="' . "$url\" $attrs >$text</a>";
    }
        
}
