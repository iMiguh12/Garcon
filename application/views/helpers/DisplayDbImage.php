<?php

class Zend_View_Helper_DisplayDbImage extends Zend_View_Helper_Abstract
{
    public function displayDbImage( $mime, $image, $id = null, $class = null, $alt = null, $title = null )
    {
        $image = base64_encode( $image );

        $data_uri = ( 'data:' . $mime . ';base64,' . $image );

        return "<img id='$id' class='$class' src='$data_uri' alt='$alt' title='$title' />";
    }
}
