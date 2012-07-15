<?php

class ProductosController extends Zend_Controller_Action
{
    public function init()
    {
        require_once 'PHPThumb/ThumbLib.inc.php';
    }

    public function indexAction()
    {
        // instanciar el modelo (tabla de productos)
        $productos = new Application_Model_DbTable_Productos();

        // generar el select
        $select = $productos->select()
                            ->from( $productos )
                            ->order( array( 'categoria ASC', 'nombre ASC' ) );

        // asignar resultado a una variable de la vista
        $this->view->productos = $productos->fetchAll( $select );

        // asignar el nombre del key a usar para el objeto del partial
        $this->view->partial()->setObjectKey('productos');
    }

    public function editAction()
    {
        // agregar forma
        $forma = new Application_Form_Productos();
        $forma->addElement( 'submit', 'enviar' );
        $forma->enviar->setLabel( 'Guardar' );

        // asignar forma a la vista
        $this->view->forma = $forma;

        if ( $this->getRequest()->isPost() ) {
            $datos = $this->getRequest()->getPost();
            $forma->imagen->setRequired ( false );

            if ( $forma->isValid( $datos ) ) {
                // asignar los valores de la forma a variables
                $id = (int) $forma->getValue( 'id' );
                $nombre = $forma->getValue( 'nombre' );
                $descripcion = $forma->getValue( 'descripcion' );
                $precio = $forma->getValue( 'precio' );
                $existencia = $forma->getValue( 'existencia' );
                $categoria =  $forma->getValue( 'categorias' );
                $imagen = $forma->getValue( 'imagen');
                $carga = $forma->imagen->getFileName ( 'imagen' );

                // @TODO: quitar la dependencia de PhpThumbFactory
                $param_miniatura = array( 'resizeUp' => true, 'jpegQuality' => 80);

                if ( $forma->getValue( 'imagen' ) != null ) {
                    $dimension = PhpThumbFactory::create( $carga, $param_miniatura );
                    $dimension->resize( 100, 100 );
                    $dimension->save( "{$carga}_thumb" );
                    $imagen = file_get_contents( "{$carga}_thumb" );
                } else {
                    $producto = new Application_Model_DbTable_Productos();
                    $datos = $producto->getProducto( $this->_getParam( 'id' ) );
                    $imagen = $datos['imagen'];
                }

                $mime =$forma->imagen->getMimeType( 'imagen' );

                // actualizar los datos
                $productos = new Application_Model_DbTable_Productos();
                $productos->updateProducto( $id, $nombre, $descripcion, $precio, $existencia, $categoria, $imagen, $mime );

                // redirigir al index
                $this->_helper->redirector( 'index' );
            } else {
                $id = $this->_getParam( 'id', 0 );
                if ( $id > 0 ) {
                    $this->_llenarForma( $forma , $id );
                }
            }
        } else {
            $id = $this->_getParam( 'id', 0 );
            if ( $id > 0 ) {
                $this->_llenarForma( $forma , $id );
            }
        }
    }

    private function _llenarForma( $forma, $id )
    {
        // obtener DbTable de productos
        $productos = new Application_Model_DbTable_Productos();

        // obtener el producto con la ID provista
        $datos = $productos->getProducto( $id );
        $this->view->imagen = 'data:' . $datos['mime'] . ';base64,' . base64_encode( $datos['imagen'] );

        // popular la forma con los datos del producto
        $forma->populate( $datos  );

        // configurar la categoría default
        $forma->categorias->setValue($datos['categoria']);

        // no requerir la imagen
        $forma->imagen->setRequired ( false );
    }

    public function deleteAction()
    {
        // checar si hay un post
        if ( $this->getRequest()->isPost() ) {
            // verificar si el post proviene de un botón de borrado; llamado borrar.
            $borrar = $this->getRequest()->getPost('borrar');

            // si el botón es afirmativo, borra el producto
            if ( $borrar == 'Sí' ) {
                // obtener id (cast)
                $id = (int) $this->getRequest()->getPost( 'id' );

                // obtener el modelo de la tabla productos
                $productos = new Application_Model_DbTable_Productos();

                // borrar el registro con la id determinada
                $productos->deleteProducto( $id );
            }

            // redirigir al listado
            $this->_helper->redirector( 'index' );
        } else {
            // obtener el parámetro de id
            $id = (int) $this->_getParam( 'id', 0 );

            // obtener una instancia del modelo de la tabla productos
            $productos = new Application_Model_DbTable_Productos();

            // Asignar a la variable productos, los datos del producto con la id determinada
            $this->view->producto = $productos->getProducto( $id );
        }
    }

    public function addAction()
    {
        // Agregar forma y ponerle un botón de guardar.
        $forma = new Application_Form_Productos();
        $forma->enviar->setLabel( 'Agregar' );

        // Obtener forma
        $this->view->forma = $forma;

        if ( $this->getRequest()->isPost() ) {
            $datos = $this->getRequest()->getPost();

            if ( $forma->isValid( $datos ) ) {
                // asignar los valores de la forma a variables
                $nombre = $forma->getValue( 'nombre' );
                $descripcion = $forma->getValue( 'descripcion' );
                $precio = $forma->getValue( 'precio' );
                $existencia = $forma->getValue( 'existencia' );
                $categoria =  $forma->getValue( 'categorias' );
                $imagen = $forma->getValue( 'imagen' );
                $carga = $forma->imagen->getFileName ( 'imagen' );

                // generar thumbnail
                $param_miniatura = array( 'resizeUp' => true, 'jpegQuality' => 80 );
                $dimension = PhpThumbFactory::create( $carga, $param_miniatura);
                $dimension->resize( 100, 100 );
                $dimension->save( "{$carga}_thumb" );

                // obtener imagen y mime-type
                $imagen = file_get_contents( "{$carga}_thumb" );
                $mime = $forma->imagen->getMimeType ( 'imagen' );

                // introducir los datos
                $productos = new Application_Model_DbTable_Productos();
                $productos->addProducto( $nombre, $descripcion, $precio, $existencia, $categoria, $imagen, $mime );

                // redirigir al index
                $this->_helper->redirector( 'index' );
            } else {
                $forma->populate( $datos );
            }
        }
    }
}
