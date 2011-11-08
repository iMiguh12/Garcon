<?php

class Application_Model_DbTable_Usuarios extends Zend_Db_Table_Abstract
{

    protected $_name = 'usuarios';

    public function getUsuario($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }

    public function addUsuario($nombre, $email, $telefono, $estado, $clave = '12345')
    {
        $condimento = 'LinuxCabal y PHP rulean, esos';
        $data = array(
            'nombre'    => $nombre,
            'email'     => $email,
            'telefono'  => $telefono,
            'estado'  => $estado,
            'clave'     => sha1( $clave . sha1( $condimento ) ),
            'condimento' => sha1( $condimento )
            );
            $this->insert($data);
    }

    public function updateUsuario($id, $nombre, $email, $telefono, $estado)
    {
        $data = array(
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'estado' => $estado,
            );
            $this->update($data, 'id = '. (int)$id);
    }

    public function deleteUsuario($id)
    {
        $this->delete('id =' . (int)$id);
    }

    public function getTableMeta()
    {
        return $this->info(self::METADATA);
    }

    public function getEnumValues( $column ) 
    {
        $column = $column;
        $meta = $this->getTableMeta();
        preg_match_all( "/'(.*?)'/" , $meta[$column]['DATA_TYPE'], $arreglo);
        return $arreglo[1];
    }
}

