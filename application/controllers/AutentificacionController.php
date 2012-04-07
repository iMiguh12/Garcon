<?php

class AutentificacionController extends Zend_Controller_Action
{
    public function init()
    {
    }

    public function indexAction()
    {
        $forma = new Application_Form_Login();
        $request = $this->getRequest();

        if ( $request->isPost() ) {
            if ( $forma->isValid( $request->getPost() ) ) {
                if ( $this->_process( $forma->getValues() ) ) {
                    // Add a message of success
                    $this->_helper->FlashMessenger( 'Usuario autenticado!' );

                    // We're authenticated! Redirect to the home page
                    $this->_helper->redirector('index', 'index');
                } else {
                    // Failed auth message
                    $this->_helper->FlashMessenger( 'No, no le atinaste. Inténtalo de nuevo...' );
                }
            } else {
                $this->view->forma = $forma;
            }
        } else {
            $this->_helper->redirector('index', 'index');
        }

        $this->view->forma = $forma;
    }

    private function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity( $values['email'] );
        $adapter->setCredential( $values['clave'] );

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate( $adapter );

        if ( $result->isValid() ) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write( $user );
            return true;
        }

        return false;
    }

    function _getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable( $dbAdapter );

        $authAdapter->setTableName( 'usuarios' )
                    ->setIdentityColumn( 'email' )
                    ->setCredentialColumn( 'clave' )
                    ->setCredentialTreatment( 'SHA1( CONCAT( ?, condimento ) )' );

        return $authAdapter;
    }

    function logoutAction()
    {
        // clear
        Zend_Auth::getInstance()->clearIdentity();

        // add message
        $this->_helper->FlashMessenger( 'Adios...' );

        // redirect
        $this->_helper->redirector( 'index', 'index' );
    }
}
