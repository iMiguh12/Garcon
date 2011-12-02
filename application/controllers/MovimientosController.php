<?php

class MovimientosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function adquirirAction()
    {
        $productos = new Application_Model_DbTable_Productos();
        
        $select = $productos->select()
                            ->from( $productos, array(
                                'id',
                                'nombre',
                                'descripcion',
                                'precio',
                                'imagen',
                                'mime')
                            );

        // asignar resultado a una variable de la vista                    
        $this->view->productos = $productos->fetchAll( $select );

        // asignar el nombre del key a usar para el objeto del partial
        $this->view->partial()->setObjectKey('productos');
    }

    public function donarAction()
    {
        // action body
    }

}





