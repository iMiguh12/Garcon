<?php

class Plugins_AuthPlugin extends Zend_Controller_Plugin_Abstract {   


   public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $autentificacion = Zend_Auth::getInstance();
        try
        {	
        	$acl = Zend_Registry::get('acl');
		if ( $autentificacion->hasIdentity() ) {			
			$rol = $autentificacion->getIdentity()->rol;			
			if (!$acl->isAllowed($autentificacion->getIdentity()->rol, $request->getControllerName()."/".$request->getActionName())) {
			    $request->setControllerName('index');
			    $request->setActionName('index');
			}
		}
		else
		{//Si se llega a este ELSE quiere decir que el usuario no se ha logueado, por lo que se comportara con el rol de invitado
			if (!$acl->isAllowed('invitado', $request->getControllerName()."/".$request->getActionName())) {
			    $request->setControllerName('index');
			    $request->setActionName('index');
			}
		}
	}
	catch (Exception $e)
	{
	    //echo '<br />Exception caught: ',  $e->getMessage(), "\n";
	    
	    //Si se entra a este catch lo mas probable es que hubo un error al momento de ejecutar la funcion isAllowed del $acl porque
	    //no se han registrado todas las urls (recursos) que tiene Garcon, y al tratar de ver si se tiene permisos sobre un recurso
	    //que nisiquiera existe pues truena, una vez registradas todas las urls ya no deberia ser necesario poner
	    //un try/catch en este plugin
	    
	}
    }
   
   
   
   
   
   /*
   
   public function __construct() {
      // Create ACL
      $this->_acl = new Zend_Acl();
      $this->_acl
            // 
            // Add resources. Each resource have format of /admin/Controller_Name/Action_Name/
            //
            ->add(new Zend_Acl_Resource('/admin/user/manage/'))
            ->add(new Zend_Acl_Resource('/admin/article/add/'))
            ->add(new Zend_Acl_Resource('/admin/article/edit/'))
            ->add(new Zend_Acl_Resource('/admin/article/delete/'))
            
             
            // Roles. I assume that your website have three level members:
            // - guest: Not a registered member
            // - member: Registered member which permission is higher than guest
            // - admin: Admin which have higher permission is higher than member
             
            ->addRole(new Zend_Acl_Role('guest'))
            ->addRole(new Zend_Acl_Role('member'), 'guest')
            ->addRole(new Zend_Acl_Role('admin'), 'member')
            
             
            // Set previleges for each roles 
            //
            // Admin can access every resources
            ->allow('admin', null)
            
            // Members can manage articles but have not permission to manage users
            ->allow('member', '/admin/article/add/')
            ->allow('member', '/admin/article/edit/')
            ->allow('member', '/admin/article/delete/')
            ->deny('member', '/admin/user/manage/')
            
            // Guest can not access any resources
            ->deny('guest', '/admin/article/add/')
            ->deny('guest', '/admin/article/edit/')
            ->deny('guest', '/admin/article/delete/')
            ->deny('guest', '/admin/user/manage/');            
   }
   
   public function preDispatch(Zend_Controller_Request_Abstract $request) {
      // Get current request
      $uri = $request->getRequestUri();
      $uri = strtolower($uri);

      $uri = rtrim($uri, '/').'/';
      // We only check for permission if the URI contains "/admin/"
      if (strpos($uri, '/admin/') === false) {
         return;
      }      
      
      $auth = Zend_Auth::getInstance();
      $isAllowed = false;
      
      // Check if the user has logged in or not
      if ($auth->hasIdentity()) {
         // I assume that when user logged in successfully,
         // you set the a property named role_name for authenticated object
         $role = $auth->getIdentity()->role_name;
         
         // Get current controller and action name
         $controller = $request->getControllerName();
         $action = $request->getActionName();
         
         // Generate the resource name
         $resourceName = '/admin/'.$controller.'/'.$action.'/';
         
         // Check if user can access this resource or not
         $isAllowed = $this->_acl->isAllowed($role, $resourceName);
      }
      
      // Forward user to access denied or login page
      if (!$isAllowed) {
         $forwardAction = Zend_Auth::getInstance()->hasIdentity() ? 'deny' : 'login';
         
         //
         // DON'T use redirect as folow:
         // $this->getResponse()->setRedirect('/Login/');
         
         $request->setControllerName('Auth')
               ->setActionName($forwardAction)
               ->setDispatched(true);
      }
   }
   
   
   */
   
   
}
