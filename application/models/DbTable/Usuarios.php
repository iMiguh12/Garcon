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

    public function addUsuario($nombre, $email, $telefono, $clave)
    {
        $data = array(
            'nombre'    => $nombre,
            'email'     => $email,
            'telefono'  => $telefono,
            'clave'     => sha1( $clave . sha1( $condimento ) ),
            'condimento' => sha1( $condimento ),
            );
            $this->insert($data);
    }

    public function updateUsuario($nombre, $email, $telefono, $clave)
    {
        $data = array(
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono,
            'clave' => $clave,
            );
            $this->update($data, 'id = '. (int)$id);
    }

    public function deleteUsuario($id)
    {
        $this->delete('id =' . (int)$id);
    }
}

