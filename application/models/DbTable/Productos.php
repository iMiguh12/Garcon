<?php

class Application_Model_DbTable_Productos extends Zend_Db_Table_Abstract 
{  
    protected $_name = 'productos';

    public function getProducto( $id )
    {
        $id = (int) $id;
        $row = $this->fetchRow( 'id = ' . $id );

        if ( !$row ) {
            throw new Exception( "No puede encontrar el registro $id" );
        }
        
        return $row->toArray();
    }

    public function addProducto( $nombre, $descripcion, $precio, $existencia, $imagen, $mime )
    {
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'existencia' => $existencia, 
            'imagen' => $imagen,
            'mime' => $mime,
 	 );
              	    
        $this->insert($data);
    }

    public function updateProducto( $id, $nombre, $descripcion, $precio, $existencia, $imagen, $mime )
    {
        $data = array(
            'nombre' => $nombre,
            'descripcion' => $descripcion, 
            'precio' => $precio,
            'existencia' => $existencia,
            'imagen' => $imagen,
            'mime' => $mime,
         );

        $this->update( $data, 'id = '. (int) $id );
    }

    public function deleteProducto( $id )
    {
        $this->delete( 'id =' . (int) $id );
    }
}
