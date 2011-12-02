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
                                    
        $this->view->movimientos = $productos->fetchAll( $select );
    }

    public function donarAction()
    {
        // action body
    }

}





