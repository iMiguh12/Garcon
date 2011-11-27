<?php

class Zend_View_Helper_DisplayProducto extends Zend_View_Helper_Abstract 
{
    public function displayProducto( $mime, $image, $id = null, $class = null, $alt = null, $title = '', $price = null, $description = null )
    {
        $image = base64_encode( $image );

    	$data_uri = ( 'data:' . $mime . ';base64,' . $image );

    	return <<<EOF
<figure class='imagen' id='producto-{$id}'>    
    <img src='$data_uri' alt='$alt' title='$title' />
    <figcaption class='edit'>edit</figcaption>
    <figcaption class='delete'>delete</figcaption>
    <figcaption class='precio'>{$price}</figcaption>
    <figcaption class='descripcion'>{$description}</figcaption>
</figure>
EOF;
    }
}
