<?php

class Application_Form_Usuarios extends Zend_Form
{
    public function init()
    {
        $this->setName( 'usuario' );
        $this->setAttrib( Zend_Form::ENCTYPE_MULTIPART, true );

        $id = new Zend_Form_Element_Hidden( 'id' );
        $id->addFilter( 'Int' );

        $nombre = new Zend_Form_Element_Text( 'nombre' );
        $nombre->setLabel( 'Nombre' )
               ->setRequired( 'true' )
               ->addFilter( 'StripTags' )
               ->addFilter( 'StringTrim' )
               ->addValidator( 'NotEmpty' )
               ->addValidator( 'Regex', false, array( '#^([a-z A-Z0-9ñÑáéíóúÁÉÍÓÚüÜ\.]+$.*)#' ) )
               ->addValidator( 'stringLength', true, array( 1, 255 ) );

        $estados = new Zend_Form_Element_Select( 'estados' );
        $estados->setLabel( 'Estado' )
                ->setRequired(true);

        $usuarios = new Application_Model_DbTable_Usuarios();
        $meta = $usuarios->getEnumValues( 'estado' );

        foreach( $meta as $cat ) {
            $estados->addMultiOption( $cat, $cat );
        }
        
        $roles = new Zend_Form_Element_Select( 'roles' );
        $roles->setLabel( 'Rol' )
              ->setRequired(true);
        
        $meta = $usuarios->getEnumValues( 'rol' );

        foreach( $meta as $cat ) {
            $roles->addMultiOption( $cat, $cat );
        }
        
        
        
        
        
        
        // @TODO: agregar validador de duplicados por correo. Ver si vamos a usar nuestro modelo ( Usuario->isDuplicate() )
        $email = new Zend_Form_Element_Text( 'email' );
        $email->setLabel( 'Email' )
              ->setRequired( 'true' )
              ->addFilter( 'StripTags' )
              ->addFilter( 'StringTrim' )
              ->addValidator( 'NotEmpty' )
              ->addValidator( 'EmailAddress' )
              ->addValidator( 'stringLength', true, array( 1, 255 ) )
              ->addValidator( 'campoUnico', true, array( "tabla"=>"usuarios", "campoTabla"=>"email", "campoIdTabla"=>"id", "campoIdForma"=>"id", "mensaje"=>"Ya existe otro usuario con el mismo email" ) );

        $telefono = new Zend_Form_Element_Text( 'telefono' );
        $telefono->setLabel( 'Telefono' )
                 ->setRequired( 'true' )
                 ->addFilter( 'StripTags' )
                 ->addFilter( 'StringTrim' )
                 ->addValidator( 'NotEmpty' )
                 // hace lo mismo que la linea de abajo, pero es un poco mas largo
                 //->addValidator( new Zend_Validate_Digits())
                 ->addValidator( 'Digits' )
                 ->addValidator( 'stringLength', true, array( 12, 12 ) );
        $clave = new Zend_Form_Element_Password( 'clave' );
        $clave->setLabel( 'Clave' )
              ->setRequired( 'true' )
              ->addFilter( 'StringTrim' )
              ->addValidator( 'NotEmpty' )
              ->addValidator( 'Identical', false, array( 'claveConfirma', 'Confirm Password' ) );
        $claveConfirma = new Zend_Form_Element_Password( 'claveConfirma' );
        $claveConfirma->setLabel( 'Corroborar clave' )
                      ->setRequired( 'true' )
                      ->addFilter( 'StringTrim' )
                      ->addValidator( 'NotEmpty' );
        $condimento = new Zend_Form_Element_Text( 'condimento' );
        $condimento->setLabel( 'Condimento' )
                   ->setRequired( 'true' )
                   ->addValidator( 'StringLength', true , array( 5, 40 ) );

        $enviar = new Zend_Form_Element_Submit( 'enviar' );
        $enviar->setAttrib( 'id', 'botonEnviar' );

        $this->addElements(
            array( $id, $nombre, $estados, $roles, $email, $telefono, $clave, $claveConfirma, $condimento, $enviar )
        );
    }
}
