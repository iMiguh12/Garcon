<?php

require_once 'Zend/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class GarconProvider extends Zend_Tool_Project_Provider_Abstract
{
    public function instalar( $db = 'garcon', $host = 'localhost', $user = 'root', $password = null )
    {    
        $db = $this->getAnswer( 'Db?', $db );
        $host = $this->getAnswer( 'Host?', $host );
        $user = $this->getAnswer( 'User?', $user );
        $password = $this->getAnswer( 'Password?', $password );
        
        $confirmacion = <<<EOF

Confirmación
================================================================================

Las respuestas dadas fueron:
    DB          = $db
    Host        = $host
    User        = $user
    Password    = $password
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

    public function desinstalar()
    {
        /** @todo Implementation */
        $this->_registry
             ->getResponse()
             ->appendContent("No implementado");
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
