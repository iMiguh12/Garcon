<?php

class Application_Form_CrearCuenta extends Zend_Form
{

    public function init()
    {
        $this->setName( 'crer' );
        $this->setMethod( 'post');

        $this->addElement( 'text', 'nombre',
            array(
                'label'             => 'Nombre:',
                'required'          => true,
                'validators'        =>
                    array(
                        array( 'StringLength', false, array( 0, 75 ) ),
                        array( 'Alpha', false),
                    ),
            )
        );

        $this->addElement( 'text', 'telefono',
            array(
                'label'             => 'Telefono:',
                'required'          => true,
                'validators'        =>
                    array(
                        array( 'StringLength', false, array( 0, 12 ) ),
                        array( 'Digits', false),
                    ),
            )
        );

        $login = new Application_Form_Login();
        $login->removeElement( 'entrar' );
        $email = $login->getElement( 'email' );
        $email->addValidator( 'EmailAddress' );

        $this->addSubForm( $login, 'login' );

        $this->addElement( 'password', 'clave2',
            array(
                'label'         => 'Corrobar Clave:',
                'validators'    =>
                    array(
                        array( 'Identical', false, array( 'token' => 'clave', 'strict' => true)),
                        array( 'StringLength', false , array( 5, 80) ),
                    ),
                'required'      => true,
            )
        );

        $this->addElement( 'text', 'condimento',
            array(
                'label'         => 'Condimento',
                'title'         => 'Tu frace favorita:',
                'validators'    =>
                    array(
                        array( 'StringLength', false, array( 5, 40 ))
                    ),
                'required'      => true,
            )
        );

        $this->addElement( 'submit', 'crear',
            array(
                'required'  => false,
                'ignore'    => true,
                'label'     => 'Crear',
            )
        );
    }


}

