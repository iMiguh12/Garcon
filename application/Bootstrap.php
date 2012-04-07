<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    // Get site-wide configuration
    private static function getConfig( $config )
    {
        return new Zend_Config_Ini( APPLICATION_PATH . "/configs/{$config}.ini", APPLICATION_ENV );
    }

    // View
    protected function _initView()
    {
        // inicializar vista
        $view = new Zend_View();

        // obtener configuración global
        $sitio = $this::getConfig( 'sitio' );

        // hacer $sitio disponible a la vista
        $view->sitio = $sitio;

        // doctype
        $view->doctype( $sitio->doctype );

        // encoding
        $view->setEncoding( $sitio->encoding );

        // title
        $view->headTitle( $sitio->name )
             ->setSeparator(' | ')
             ->setIndent(8);

        // meta tags
        $view->headMeta()->setName( 'keywords', $sitio->keywords )
                         ->appendName( 'description', $sitio->description )
                         ->appendName( 'google-site-verification', $sitio->googleVerification )
                         ->setIndent( 8 );

        // stylesheets & feeds (headLinks)
        $view->headLink()->setStylesheet( '/css/reset.css', 'all' )
                         ->appendStylesheet( '/css/layout.css', 'all' )
                         ->appendStylesheet( '/css/skin.css', 'all' )
                         ->appendStylesheet( '/css/menu.css', 'all' )
                         ->headLink(
                             array(
                                'rel' => 'shortcut icon',
                                'href' => '/images/favicon.ico'
                            ),
                            'PREPEND'
                        )
                         ->headLink(
                             array(
                                'rel' => 'shortcut icon',
                                'href' => '/images/favicon.ico'
                            ),
                            'PREPEND'
                         )
                         ->appendAlternate( '/feed/', 'application/rss+xml', 'News' )
                         ->setIndent( 8 );

        // fonts
        $view->headLink()->appendStylesheet( 'http://fonts.googleapis.com/css?family=Julee', 'all' );

        // jQuery y Javascript
        $view->addHelperPath( "ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper" );
        $view->jQuery()->enable()
                       ->setVersion( $sitio->jquery )
                       ->uiEnable()
                       ->setUiVersion( $sitio->jqueryUI );

        $view->headScript()->appendFile( '/js/garcon.js', 'text/javascript',
            array(
                'charset' => $sitio->encoding
            )
        )->setIndent( 8 );

        // agregarlo al ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'ViewRenderer' );
        $viewRenderer->setView( $view );

        // registrar viewRenderer
        Zend_Controller_Action_HelperBroker::addHelper( $viewRenderer );

        // return it, so that it can be stored by the bootstrap
        return $view;
    }
    
    // configuración de las ACLs
    protected function _initAcl()
    {
    	$acl = new Zend_Acl();
    	
    	$acl->addRole( 'invitado' );
    	$acl->addRole( 'usuario' );
    	$acl->addRole( 'administrador' );
		
		$acl->add( new Zend_Acl_Resource( 'admin:productos' ) );
		$acl->add( new Zend_Acl_Resource( 'admin:usuarios' ) );
		
		$acl->allow('administrador', 'admin:productos' );
		$acl->allow('administrador', 'admin:usuarios' );

 
		// Store ACL and role in the proxy helper
		$view = $this->view;
		$view->navigation()->setAcl($acl)->setRole( 'invitado' );
    }

    // Menu
    protected function _initMenu()
    {
        // obtener la configuración del menú (xml)
        $config = new Zend_Config_Xml( APPLICATION_PATH . '/models/menu.xml', 'nav' );

        // generar el contenedor
        $container = new Zend_Navigation( $config );

        // registrarlo
        Zend_Registry::set( 'Zend_Navigation', $container );
    }

    // Sessions
    protected function _initSession()
    {
        // obtener configuraciones específicas del sitio
        $config = $this::getConfig( 'sessions' );

        Zend_Session::setOptions( $config->toArray() );

        // iniciar sesión
        Zend_Session::start();
    }

    // Locale
    protected function _initLocale()
    {
        // obtener configuraciones específicas del sitio
        $config = $this::getConfig( 'sitio' );

        // definir el locale por default
        Zend_Locale::setDefault( $config->locale );

        // ponerlo en el registro
        $locale = new Zend_Locale( $config->locale );
        Zend_Registry::set( 'Zend_Locale', $locale );
    }

    // Translator
    protected function _initL18n()
    {
        $translator = new Zend_Translate (
            array(
                'adapter' => 'array',
                'content' => APPLICATION_PATH .'/../resources/languages',
                'locale' => 'es',
                'scan' => Zend_Translate::LOCALE_DIRECTORY
            )
        );

        Zend_Validate_Abstract::setDefaultTranslator( $translator );
    }

    // FlashMessenger
    protected function _initMessages()
    {
        Zend_Controller_Action_HelperBroker::addHelper( new Zend_Controller_Action_Helper_FlashMessenger() );
    }

    // Feed (rss)
    protected function _initFeed()
    {
        // configurar las opciones de cache
        $frontendOptions = array(
            'lifetime' => 86400,
            'automatic_serialization' => true
        );

        // definir el directorio de cache
        $backendOptions = array( 'cache_dir' => APPLICATION_PATH . '/../data/cache' );

        // configurar cache
        $cache = Zend_Cache::factory(
            'Core', 'File', $frontendOptions, $backendOptions
        );

        // configurar el feed para que use cache y httpConditionalGet
        Zend_Feed_Reader::setCache($cache);
        Zend_Feed_Reader::useHttpConditionalGet();
    }
}
