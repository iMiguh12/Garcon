<?php

class Garcon_Plugins_AuthPlugin extends Zend_Controller_Plugin_Abstract 
{   
    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $autentificacion = Zend_Auth::getInstance();

        $acl = Zend_Registry::get( 'acl' );
        try{    
        if ( $autentificacion->hasIdentity() ) { 
            $rol = $autentificacion->getIdentity()->rol;            
                
            if ( ! $acl->isAllowed( $autentificacion->getIdentity()->rol, $request->getControllerName() , $request->getActionName() ) ) {
                $request->setControllerName( 'index' );
                $request->setActionName( 'index' );
            }
            
            return true;
        } else  {
            // Si se llega a este ELSE quiere decir que el usuario no se ha logueado, por lo que se comportara con el rol de invitado
            if ( ! $acl->isAllowed( 'invitado', $request->getControllerName() , $request->getActionName() ) ) {
                $request->setControllerName( 'index' );
                $request->setActionName( 'index' );
            }
        }
	}catch (Exception $e) {
		echo 'Excepción capturada: ',  $e->getMessage(), "\n";
	}
    }
}
