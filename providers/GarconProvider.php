<?php

require_once 'Zend/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class GarconProvider extends Zend_Tool_Project_Provider_Abstract
{
    private $db, $host, $user, $password;
    
    public function instalar( $db = 'garcon', $host = 'localhost', $user = 'root', $password = null )
    {    
        $this->db = $db;
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        
        $db = $this->getAnswer( 'Db?', 'db' );
        
        $respuesta = $this->_registry
                          ->getClient()
                          ->promptInteractiveInput( "DB? ({$db}) " );
        $db = $respuesta->getContent();
        
        $respuesta = $this->_registry
                          ->getClient()
                          ->promptInteractiveInput( "Host? ({$host})");
        $host = $respuesta->getContent();
        
        $respuesta = $this->_registry
                     ->getClient()
                     ->promptInteractiveInput( "User? ({$user})");
        $user = $respuesta->getContent();
        
        $respuesta = $this->_registry
                         ->getClient()
                         ->promptInteractiveInput( "Password? ({$password})");
        $password = $respuesta->getContent();
        
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
                 ->appendContent( 'Ok, inténtalo de nuevo.' );
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
        
        if ( $respuesta->getContent() == $this->{$default} ) {
            return $this->$default;
        } else {
            return $respuesta->getContent();
        }
    }
}
