<?php

class Zend_View_Helper_DisplayBlobImage extends Zend_View_Helper_Abstract 
{
    public function displayBlobImage( $mime, $image, $id = null, $class = null, $alt = null, $title = '', $caption = null )
    {
		$image = base64_encode( $image );
    	$data_uri = ( 'data:' . $mime . ';base64,' . $image );

    	return <<<EOF
<figure class='{$class}\' id='{$id}'>    
    <img src='$data_uri' alt='$alt' title='$title' />
    <figcaption>{$caption}</figcaption>
</figure>
EOF;
    }
}
