<?php

class UsuariosController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $usuarios= new Application_Model_DbTable_Usuarios();
        $this->view->usuarios = $usuarios->fetchAll();

  // asignar el nombre del key a usar para el objeto del partial
        $this->view->partial()->setObjectKey('usuarios');
    }

    public function addAction()
    {
        $forma = new Application_Form_Usuarios();
        $forma->enviar->setLabel( 'Agregar' );

        $this->view->forma = $forma;

        if ( $this->getRequest()->isPost() ) {
            $datos = $this->getRequest()->getPost();

            if ( $forma->isValid( $datos ) ) {
          // actualizar los datos
                $usuarios = new Application_Model_DbTable_Usuarios();
                $nombre = $forma->getValue('nombre');
                $email = $forma->getValue('email');
                $telefono = $forma->getValue('telefono');
                $estado = $forma->getValue('estados');
                $clave = $forma->getValue('clave');
                $condimento = $forma->getValue('condimento');

                $usuarios->addUsuario( $nombre, $email, $telefono, $estado, $clave, $condimento);
                // redirigir al index
                $this->_helper->redirector( 'index' );
            } else {
                $forma->populate( $datos );
            }
        }

    }

    public function editAction()
    {
         // agregar forma
        $forma = new Application_Form_Usuarios();
        $forma->addElement( 'submit', 'enviar' );
        $forma->enviar->setLabel( 'Guardar' );

        // asignar forma a la vista
        $this->view->forma = $forma;
        $forma->getElement( 'clave' )->setLabel( 'Clave (dejar vacio si no se desea cambiar el password)' );
        $forma->getElement( 'condimento' )->setLabel( 'Condimento (dejar vacio si no se desea cambiar el condimento)' );

        if ( $this->getRequest()->isPost() )
        {
            $datos = $this->getRequest()->getPost();

            if( null == $datos['clave'] && null == $datos['condimento'] ) {
                $forma->clave->setRequired( false );
                $forma->claveConfirma->setRequired( false );
                $forma->condimento->setRequired( false );
            }

            if ( $forma->isValid( $datos ) )
            {
                // asignar los valores de la forma a variables
                $id = (int) $forma->getValue( 'id' );
                $nombre = $forma->getValue( 'nombre' );
                $email = $forma->getValue( 'email' );
                $telefono = $forma->getValue( 'telefono' );
                $estado = $forma->getValue( 'estados' );
                $clave = $forma->getValue('clave');
                $condimento = $forma->getValue('condimento');

                // actualizar los datos
                $usuarios = new Application_Model_DbTable_Usuarios();

                if($clave=='' && $condimento=='')
                    $usuarios->updateUsuario( $id, $nombre, $email, $telefono, $estado);
                else
                    $usuarios->updateUsuario( $id, $nombre, $email, $telefono, $estado, $clave, $condimento );

                // redirigir al index
                $this->_helper->redirector( 'index' );
            } else {
                $id = $this->_getParam( 'id', 0 );
                if ( $id > 0 ) {
                    $forma->populate( $datos );
                }
            }
        } else {
            $id = $this->_getParam( 'id', 0 );
            if ( $id > 0 ) {
                $usuarios = new Application_Model_DbTable_Usuarios();
                $datos = $usuarios->getUsuario( $id );
                $this->view->datos = $datos;
                $forma->populate( $datos  );
                $forma->estados->setValue( $datos['estado'] );
                $forma->condimento->setValue( null );

            }
        }
    }

    public function deleteAction()
    {
        // checar si hay un post
        if ( $this->getRequest()->isPost() ) {
            // verificar si el post proviene de un botón de borrado; llamado borrar.
            $borrar = $this->getRequest()->getPost( 'borrar' );

            // si el botón es afirmativo, borra el producto
            if ( $borrar == 'Sí' ) {
                // obtener id (cast)
                $id = (int) $this->getRequest()->getPost( 'id' );

                // obtener el modelo de la tabla productos
                $usuarios = new Application_Model_DbTable_Usuarios();

                // borrar el registro con la id determinada
                $usuarios->deleteUsuario( $id );
            }

            // redirigir al listado
            $this->_helper->redirector( 'index' );
        } else {
            // obtener el parámetro de id
            $id = (int) $this->_getParam( 'id', 0 );

            // obtener una instancia del modelo de la tabla productos
            $usuarios = new Application_Model_DbTable_Usuarios();

            // Asignar a la variable productos, los datos del producto con la id determinada
            $this->view->usuario = $usuarios->getUsuario( $id );
        }
    }
}
