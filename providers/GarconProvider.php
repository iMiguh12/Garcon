<?php

require_once 'Zend/Tool/Project/Provider/Abstract.php';
require_once 'Zend/Tool/Project/Provider/Exception.php';

class GarconProvider extends Zend_Tool_Project_Provider_Abstract
{
    public function instalar( $db = 'garcon', $host = 'localhost', $root = 'root', $password = null, $guser = 'garcon', $gpassword = 'somecoolpassword', $data = 'no' )
    {
        $answers['db']          = $this->_getAnswer( 'Db?', $db );
        $answers['host']        = $this->_getAnswer( 'Host?', $host );
        $answers['root']        = $this->_getAnswer( 'Root User?', $root );
        $answers['password']    = $this->_getAnswer( 'Root Password?', $password );
        $answers['guser']       = $this->_getAnswer( 'Garcon User?', $guser );
        $answers['gpassword']   = $this->_getAnswer( 'Garcon Password?', $gpassword );
        $answers['data']        = $this->_getAnswer( 'Quieres datos de prueba?', $data );

        $confirmacion = <<<EOF

Confirmación
================================================================================

Las respuestas dadas fueron:
    DB              = {$answers['db']}
    Host            = {$answers['host']}
    Root User       = {$answers['root']}
    Root Password   = ++++++++
    Garcon User     = {$answers['guser']}
    Garcon Password = ++++++++
    Datos de prueba = {$answers['data']}
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

            // instalar
            $this->_install( $answers );
        } else {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Proceso Abortado, inténtalo de nuevo.' );
        }
    }

    public function desinstalar( $host = 'localhost', $root = 'root', $password = null, $db = 'no', $user = 'no', $files = 'si' )
    {
        $answers['host']        = $this->_getAnswer( 'Host?', $host);
        $answers['root']        = $this->_getAnswer( 'Root User?', $root );
        $answers['password']    = $this->_getAnswer( 'Root Password?', $password );
        $answers['db']          = $this->_getAnswer( 'Eliminar Db?', $db );
        $answers['user']        = $this->_getAnswer( 'Eliminar User?', $user );
        $answers['files']       = $this->_getAnswer( 'Eliminar archivos?', $files );

        $confirmacion = <<<EOF

Confirmación
================================================================================

Las respuestas dadas fueron:
    Host                = {$answers['host']}
    Root user           = {$answers['root']}
    Root password       = ++++++++
    Eliminar Db?        = {$answers['db']}
    Eliminar user?      = {$answers['user']}
    Eliminar archivos?  = {$answers['files']}
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

            // uninstall
            $this->_uninstall( $answers );
        } else {
            $this->_registry
                 ->getResponse()
                 ->appendContent( 'Proceso Abortado, inténtalo de nuevo.' );
        }
    }

    private function _getAnswer( $pregunta, $default )
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

    private function _install( $answers = array() )
    {
        // obtener conexión a DB
        $this->_registry
             ->getResponse()
             ->appendContent( 'Instalación no implementada...' );

        // escribir archivo de conexión a DB

        // instalar el usuario y la DB

        // instalar las tablas

        // instalar los datos de prueba

    }

    private function _uninstall( $answers = array() )
    {
        // obtener conexión a DB
        $this->_registry
             ->getResponse()
             ->appendContent( 'Desinstalación no implementada...' );

        // borrar la DB

        // borrar al usuario

        // borrar archivos

    }
}
