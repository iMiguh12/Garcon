<?php

class Zend_View_Helper_DisplayDbImage extends Zend_View_Helper_Abstract 
{
    public function displayDbImage( $mime, $image, $alt = null, $title = '' )
    {
        $image = base64_encode( $image );

    	$data_uri = ( 'data:' . $mime . ';base64,' . $image );

    	return "<img src='$data_uri' alt='$alt' title='$title' />";
    }
}
