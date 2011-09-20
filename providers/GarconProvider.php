<?php

require_once 'Zend/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class GarconProvider extends Zend_Tool_Project_Provider_Abstract
{
    public function instalar( $db = 'garcon', $host = 'localhost', $user = 'root', $password = null, $guser = 'garcon', $gpassword = 'somecoolpassword', $datos = 'no' )
    {    
        $db = $this->getAnswer( 'Db?', $db );
        $host = $this->getAnswer( 'Host?', $host );
        $user = $this->getAnswer( 'Root User?', $user );
        $password = $this->getAnswer( 'Root Password?', $password );
        $guser = $this->getAnswer( 'Garcon User?', $guser );
        $gpassword = $this->getAnswer( 'Garcon Password?', $gpassword );
        $datos = $this->getAnswer( 'Quieres datos de prueba?', $datos );
        
        $confirmacion = <<<EOF

Confirmación
================================================================================

Las respuestas dadas fueron:
    DB              = $db
    Host            = $host
    Root User       = $user
    Root Password   = ++++++++
    Garcon User     = $guser
    Garcon Password = ++++++++
    Datos de prueba = $datos
EOF;
    
        $this->_registry
             ->getResponse()
             ->appendContent( $confirmacion );

        $respuesta = $this->_registry
                          ->getClient()
                          ->promptInteractiveInput( 'Es esta información correcta? (si o no)');
        $confirmacion = $respuesta->getContent();
        
        if( $confirmacion === 'si' ) {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Ya vas! Instlando...' );
        } else {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Proceso Abortado, inténtalo de nuevo.' );
        }
    }

    public function desinstalar( $host = 'localhost', $root = 'root', $password = null, $db = 'no', $user = 'no', $files = 'si' )
    {
        $host = $this->getAnswer( 'Host?', $host);
        $root = $this->getAnswer( 'Root User?', $root );
        $password = $this->getAnswer( 'Root Password?', $password );
        $db = $this->getAnswer( 'Eliminar Db?', $db );
        $user = $this->getAnswer( 'Eliminar User?', $user );
        $files = $this->getAnswer( 'Eliminar archivos?', $files );
        
        $confirmacion = <<<EOF

Confirmación
================================================================================

Las respuestas dadas fueron:
    Host                = $host
    Root user           = $root
    Root password       = ++++++++
    Eliminar Db?        = $db
    Eliminar user?      = $user
    Eliminar archivos?  = $files
EOF;
    
        $this->_registry
             ->getResponse()
             ->appendContent( $confirmacion );

        $respuesta = $this->_registry
                          ->getClient()
                          ->promptInteractiveInput( 'Es esta información correcta? (si o no)');
        $confirmacion = $respuesta->getContent();
        
        if( $confirmacion === 'si' ) {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Ya vas! Desinstalando...' );
        } else {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Proceso Abortado, inténtalo de nuevo.' );
        }
    }
    
    private function getAnswer( $pregunta, $default )
    {
        $respuesta = $this->_registry
                          ->getClient()
                          ->promptInteractiveInput( "$pregunta ({$default}) " );
        
        if ( $respuesta->getContent() == null ) {
            return $default;
        } else {
            return $respuesta->getContent();
        }
    }
}
