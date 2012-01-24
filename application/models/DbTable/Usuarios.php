<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract
{
    protected $_name = 'usuarios';

    public function getUsuario( $id )
    {
        $id = (int)$id;
        $row = $this->fetchRow( 'id = ' . $id );
        if ( !$row ) {
            throw new Exception( "Could not find row $id" );
        }

        return $row->toArray();
    }

    public function addUsuario( $nombre, $email, $telefono, $estado, $clave, $condimento )
    {
        $data = array(
            'nombre'    => $nombre,
            'email'     => $email,
            'telefono'  => $telefono,
            'estado'  => $estado,
            'clave'     => sha1( $clave . sha1( $condimento ) ),
            'condimento' => sha1( $condimento ),
        );
        
        $this->insert( $data );
    }

    public function updateUsuario( $id, $nombre, $email, $telefono, $estado, $clave, $condimento )
    {
        if( null == $clave || empty( $clave ) ) {
            $data = array(
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'estado' => $estado
            );
        } else {
            $data = array(
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'estado' => $estado,
                'clave'     => sha1( $clave . sha1( $condimento ) ),
                'condimento' => sha1( $condimento ),
            );
        }
            
        $this->update( $data, 'id = '. (int)$id );
    }
        

    public function deleteUsuario( $id )
    {
        $this->delete( 'id =' . (int)$id );
    }

    public function getTableMeta()
    {
        return $this->info( self::METADATA );
    }

    public function getEnumValues( $column ) 
    {
        $meta = $this->getTableMeta();
        preg_match_all( "/'(.*?)'/" , $meta[$column]['DATA_TYPE'], $arreglo );
        return $arreglo[1];
    }

    public function isDuplicate( $value )
    {
        $row = $this->fetchRow( "email = '$value'" ); 

        if ( $row ) {
            return true;
        } else {
            return false;
        }
    }
}
