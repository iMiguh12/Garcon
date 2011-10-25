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
                    // We're authenticated! Redirect to the home page
                    $this->_helper->redirector('index', 'index');
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

    private function _getAuthAdapter()
    {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable( $dbAdapter );

        $authAdapter->setTableName( 'usuarios' )
                    ->setIdentityColumn( 'email' )
                    ->setCredentialColumn( 'clave' )
                    ->setCredentialTreatment( 'SHA1( CONCAT( ?, condimento ) )' );

        return $authAdapter;
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector( 'index', 'index' );
    }

    public function crearAction()
    {
        $forma = new Application_Form_CrearCuenta();
        $request = $this->getRequest();

        if ( $request->isPost() )
        {
            if ( $forma->isValid( $request->getPost() ) )
            {
                //Recuperar la subforma
                $login = $forma->getSubForm( 'login' );

                //Poner los datos de la forma el variables
                $nombre = $forma->getValue( 'nombre' );
                $telefono = $forma->getValue( 'telefono' );
                $email = $login->getValue( 'email' );
                $clave = $login->getValue( 'clave' );
                $condimento = $forma->getValue( 'condimento' );

                //Guardar información en la DB
                $usuarios   = new Application_Model_DbTable_Usuarios();
                $usuarios->addUsuario( $nombre, $email, $telefono, $clave, $condimento );

                //Iniciar seción
                $this->_process( $login->getValues() );
                $this->_helper->redirector( 'index', 'index' );
            }
        }

        $this->view->forma = $forma;
    }
}
