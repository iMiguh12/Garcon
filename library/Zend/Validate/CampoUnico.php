<?php
/**
 * --Validador de Campos unicos en formas
 * Se usa para validar si un campo ingresado en una forma se encuentra repetido en algun registro 
 * de una tabla de la base de datos.
 * 
 * Para el correcto funcionamiento de este validador se le deben mandar los siguientes parametros:
 * -tabla               Nombre de la tabla de la base de datos donde se alberga el campo con el que queremos validar
 * -campoTabla          Nombre del campo con el que queremos validar
 * -campoIdTabla        Nombre de la llave primaria de la tabla descrita anteriormente
 * -campoIdForma        Es el campo (normalmente debe ser un hidden) de la forma que contiene el id (identificador) del registro que se esta modificando
 * 
 * Los siguientes campos son opcionales
 * -mensaje             Es el mensaje que se muestra cuando no se pasa la validacion (cuando se encuentra duplicidad de valores)
 * 
 */
require_once 'Zend/Validate/Abstract.php';

class Zend_Validate_CampoUnico extends Zend_Validate_Abstract
{
    
    /**
     * Error codes
     * @const string
     */
    const ESTA_REPETIDO            = 'estaRepetido';   
    const MENSAJE_COSTUMIZADO      = 'mensajeCostumizado';   

    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::ESTA_REPETIDO      => "Ya existe un registro con ese mismo valor",
        self::MENSAJE_COSTUMIZADO => ""
    );
    
    
    protected $_tabla;
    protected $_campoTabla;
    protected $_campoIdTabla;    
    protected $_campoIdForma;
    protected $_mensaje;
    
    
    public function __construct($options = array())
    {           
        $this->setTabla($options['tabla']);
        $this->setCampoTabla($options['campoTabla']);
        $this->setCampoIdTabla($options['campoIdTabla']);
        $this->setCampoIdForma($options['campoIdForma']);
        $this->setMensaje($options['mensaje']);        
        
        //print_r(array_keys($options));
        $arrayDeLlaves = array_keys($options);
        //echo "res = ".array_search("tabla", $arrayDeLlaves);
    }


    public function isValid($value, $context = null)
    {
        require_once 'funcionesComunes.php';
        $db = getObjetoConexionBD();
        $query = "select count(1) num from ".$this->getTabla()." where ".$this->getCampoTabla()." = '".$value."'";
        if($context[$this->getCampoIdForma()]!=''){//Si es edicion de un registro
            $query = $query." and ".$this->getCampoIdTabla()." != ".$context[$this->getCampoIdForma()];
        }
        //echo "query = ".$query;        
             
        $res = $db->query($query);
        $filas = $res->fetchAll();
        
        if($filas[0]['num']=='0')
            return true;
        else{
            if($this->getMensaje()!=null && $this->getMensaje()!=''){                
                $this->_messageTemplates[self::MENSAJE_COSTUMIZADO] = $this->getMensaje();
                $this->_error(self::MENSAJE_COSTUMIZADO);
            }else
                $this->_error(self::ESTA_REPETIDO);
            return false;
        }
    }
    
    
    
    public function setCampoIdTabla($campoIdTabla){
        $this->_campoIdTabla = $campoIdTabla;
        return $this;
    }
    
    public function getCampoIdTabla(){
        return $this->_campoIdTabla;
    }
    
    public function setCampoTabla($campoTabla){        
        $this->_campoTabla = $campoTabla;        
        return $this;
    }
    
    public function getCampoTabla(){
        return $this->_campoTabla;
    }
    
    public function setTabla($tabla){
        $this->_tabla = $tabla;
        return $this;
    }
    
    public function getTabla(){
        return $this->_tabla;
    }
    
    public function setCampoIdForma($campoIdForma){
        $this->_campoIdForma = $campoIdForma;
        return $this;
    }
    
    public function getCampoIdForma(){
        return $this->_campoIdForma;
    }
    
    public function setMensaje($mensaje){
        $this->_mensaje = $mensaje;
        return $this;
    }
    
    public function getMensaje(){
        return $this->_mensaje;
    }
            
}

?>
