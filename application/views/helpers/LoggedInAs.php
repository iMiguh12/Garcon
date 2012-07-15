<?php

class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
    public function loggedInAs ()
    {
        $autentificacion = Zend_Auth::getInstance();

        if ( $autentificacion->hasIdentity() ) {
            $usuario = $autentificacion->getIdentity()->nombre;
            $logoutUrl = $this->view->url(
                array(
                    'controller' => 'autentificacion',
                    'action'     => 'logout'
                ),
                null,
                true
            );

            return "<span>Bienvenido, {$usuario}! <a href='$logoutUrl'>salir</a></span>";
        } else {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $controller = $request->getControllerName();
            $action = $request->getActionName();

            if( $controller == 'autentificacion' && $action == 'index' ) {
                return '';
            } else {
                $loginUrl = $this->view->url(
                    array(
                        'controller'    => 'autentificacion',
                        'action'        => 'index'
                    )
                );

                $forma = new Application_Form_Login();
                $forma->setAction( '/autentificacion/index' );
                return $forma;
                //return "<span><a href='$loginUrl'>entrar</a></span>";
            }
        }
    }
}
