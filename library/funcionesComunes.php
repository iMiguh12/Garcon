<?php

    function getObjetoConexionBD() {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam( "bootstrap" );        
        $opcionesDelApplicationIni =  $bootstrap->getOptions();
        
        $parameters = array(
            'host'     => $opcionesDelApplicationIni['resources']['db']['params']['host'],
            'username' => $opcionesDelApplicationIni['resources']['db']['params']['username'],
            'password' => $opcionesDelApplicationIni['resources']['db']['params']['password'],
            'dbname'   => $opcionesDelApplicationIni['resources']['db']['params']['dbname']
        );

        $db = Zend_Db::factory( 'Pdo_Mysql', $parameters );
        $db->getConnection();
        return $db;
    }
