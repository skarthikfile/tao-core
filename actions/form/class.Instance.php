<?php

error_reporting(E_ALL);

/**
 * Create a form from a  resource of your ontology. 
 * Each property will be a field, regarding it's widget.
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package tao
 * @subpackage actions_form
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This container enables gives you tools to create a form from ontology
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 */
require_once('tao/actions/form/class.Generis.php');

/* user defined includes */
// section 127-0-1-1-56df1631:1284f2fd9c5:-8000:000000000000248C-includes begin
// section 127-0-1-1-56df1631:1284f2fd9c5:-8000:000000000000248C-includes end

/* user defined constants */
// section 127-0-1-1-56df1631:1284f2fd9c5:-8000:000000000000248C-constants begin
// section 127-0-1-1-56df1631:1284f2fd9c5:-8000:000000000000248C-constants end

/**
 * Create a form from a  resource of your ontology. 
 * Each property will be a field, regarding it's widget.
 *
 * @access public
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package tao
 * @subpackage actions_form
 */
class tao_actions_form_Instance
    extends tao_actions_form_Generis
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Initialize the form
     *
     * @access protected
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    protected function initForm()
    {
        // section 127-0-1-1-56df1631:1284f2fd9c5:-8000:0000000000002496 begin
        
    	(isset($this->options['name'])) ? $name = $this->options['name'] : $name = ''; 
    	if(empty($name)){
			$name = 'form_'.(count(self::$forms)+1);
		}
		unset($this->options['name']);
			
		$this->form = tao_helpers_form_FormFactory::getForm($name, $this->options);
    	
		//add translate action in toolbar
		$actions = tao_helpers_form_FormFactory::getCommonActions();
		
		if(!tao_helpers_Context::check('STANDALONE_MODE')){
			$translateELt = tao_helpers_form_FormFactory::getElement('translate', 'Free');
			$translateELt->setValue("<a href='#' class='form-translator' ><img src='".TAOBASE_WWW."/img/translate.png'  /> ".__('Translate')."</a>");
			$actions[] = $translateELt;
		}
		
		$this->form->setActions($actions, 'top');
		$this->form->setActions($actions, 'bottom');
		
        // section 127-0-1-1-56df1631:1284f2fd9c5:-8000:0000000000002496 end
    }

    /**
     * Initialize the form elements
     *
     * @access protected
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    protected function initElements()
    {
        // section 127-0-1-1-56df1631:1284f2fd9c5:-8000:0000000000002498 begin
        
    	
    	$clazz = $this->getClazz();
    	$instance = $this->getInstance();
    	
    	//get the list of properties to set in the form
    	$defaultProperties 	= tao_helpers_form_GenerisFormFactory::getDefaultProperties();
		$editedProperties = $defaultProperties;
		
		foreach(tao_helpers_form_GenerisFormFactory::getClassProperties($clazz, $this->getTopClazz()) as $property){
			$found = false;
			foreach($editedProperties as $editedProperty){
				if($editedProperty->uriResource == $property->uriResource){
					$found = true;
					break;
				}
			}
			if(!$found){
				$editedProperties[] = $property;
			}
		}
			
		foreach($editedProperties as $property){
				
			$property->feed();
				
			//map properties widgets to form elments 
			$element = tao_helpers_form_GenerisFormFactory::elementMap($property);
			
			if(!is_null($element)){
				
				//take instance values to populate the form
				if(!is_null($instance)){
					
					$values = $instance->getPropertyValuesCollection($property);
					foreach($values->getIterator() as $value){
						if(!is_null($value)){
							if($value instanceof core_kernel_classes_Resource){
								$element->setValue($value->uriResource);
							}
							if($value instanceof core_kernel_classes_Literal){
								$element->setValue((string)$value);
							}
						}
					}
				}
					
				//set label validator
				if($property->uriResource == RDFS_LABEL){
					$element->addValidators(array(
						tao_helpers_form_FormFactory::getValidator('NotEmpty'),
						tao_helpers_form_FormFactory::getValidator('Label', array('class' => $clazz, 'uri' => $instance->uriResource))
					));
				}
					
				$this->form->addElement($element);
			}
		}
			
		//add an hidden elt for the class uri
		$classUriElt = tao_helpers_form_FormFactory::getElement('classUri', 'Hidden');
		$classUriElt->setValue(tao_helpers_Uri::encode($clazz->uriResource));
		$this->form->addElement($classUriElt);
			
		if(!is_null($instance)){
			//add an hidden elt for the instance Uri
			$instanceUriElt = tao_helpers_form_FormFactory::getElement('uri', 'Hidden');
			$instanceUriElt->setValue(tao_helpers_Uri::encode($instance->uriResource));
			$this->form->addElement($instanceUriElt);
		}
			
        
        // section 127-0-1-1-56df1631:1284f2fd9c5:-8000:0000000000002498 end
    }

} /* end of class tao_actions_form_Instance */

?>