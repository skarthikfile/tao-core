<?php

error_reporting(E_ALL);

/**
 * This container initialize the import form.
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package tao
 * @subpackage actions_form
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This class provide a container for a specific form instance.
 * It's subclasses instanciate a form and it's elements to be used as a
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 */
require_once('tao/helpers/form/class.FormContainer.php');

/* user defined includes */
// section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CE-includes begin
// section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CE-includes end

/* user defined constants */
// section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CE-constants begin
// section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CE-constants end

/**
 * This container initialize the import form.
 *
 * @access public
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package tao
 * @subpackage actions_form
 */
class tao_actions_form_Import
    extends tao_helpers_form_FormContainer
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute formats
     *
     * @access protected
     * @var array
     */
    protected $formats = array('csv' => 'CSV');

    /**
     * Short description of attribute UPLOAD_MAX
     *
     * @access protected
     * @var int
     */
    const UPLOAD_MAX = 3000000;

    // --- OPERATIONS ---

    /**
     * Short description of method initForm
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    public function initForm()
    {
        // section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CF begin
        
    	$this->form = tao_helpers_form_FormFactory::getForm('import');
    	
    	$nextButton = true;
    	if(isset($_POST['format'])){
			if($_POST['format'] != 'csv'){
				$nextButton = false;
			}
    	}
    	
    	$submitElt = tao_helpers_form_FormFactory::getElement('import', 'Free');
    	if($nextButton){
			$submitElt->setValue( "<a href='#' class='form-submiter' ><img src='".TAOBASE_WWW."/img/next.png' /> ".__('Next')."</a>");
    	}
    	else{
			$submitElt->setValue( "<a href='#' class='form-submiter' ><img src='".TAOBASE_WWW."/img/import.png' /> ".__('Import')."</a>");
    	}
		$this->form->setActions(array($submitElt), 'bottom');
		$this->form->setActions(array(), 'top');
    	
        // section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023CF end
    }

    /**
     * Short description of method initElements
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    public function initElements()
    {
        // section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023D1 begin
        
    	//create the element to select the import format
    	$formatElt = tao_helpers_form_FormFactory::getElement('format', 'Radiobox');
    	$formatElt->setDescription(__('Please select the input data format to import'));
    	
    	//mandatory field
    	$formatElt->addValidator(tao_helpers_form_FormFactory::getValidator('NotEmpty'));
    	$formatElt->setOptions($this->formats);

    	//shortcut: add the default value here to load the first time the form is defined
		if(count($this->formats) == 1){
			foreach($this->formats as $format){
				$formatElt->setValue($format);
			}
		}
		if(isset($_POST['format'])){
			if(array_key_exists($_POST['format'], $this->formats)){
				$formatElt->setValue($_POST['format']);
			}
		}
		
    	$this->form->addElement($formatElt);
    	$this->form->createGroup('formats', __('Supported formats to import'), array('format'));
    	
    	//load dynamically the method regarding the selected format 
    	if(!is_null($formatElt->getValue())){
    		$method = "init".strtoupper($formatElt->getValue())."Elements";
    		
    		if(method_exists($this, $method)){
    			$this->$method();
    		}
    	}
    	
        // section 127-0-1-1--5823ae53:12820f19957:-8000:00000000000023D1 end
    }

    /**
     * Short description of method initCSVElements
     *
     * @access protected
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    protected function initCSVElements()
    {
        // section 127-0-1-1-2993bc96:12baebd89c3:-8000:0000000000002671 begin
        
    	$adapter = new tao_helpers_data_GenerisAdapterCsv();
		$options = $adapter->getOptions();
		
		//create import options form
		foreach($options as $optName => $optValue){
			(is_bool($optValue))  ? $eltType = 'Checkbox' : $eltType = 'Textbox';
			
			$optElt = tao_helpers_form_FormFactory::getElement($optName, $eltType);
			$optElt->setDescription(tao_helpers_Display::textCleaner($optName, ' '));
			$optElt->setValue(addslashes($optValue));
			
			$optElt->addAttribute("size", ($optName == 'column_order') ? 40 : 6);
			if(is_null($optValue) || $optName == 'line_break'){
				$optElt->addAttribute("disabled", "true");
			}
			$optElt->setValue($optValue);
			if($eltType == 'Checkbox'){
				$optElt->setOptions(array($optName => ''));
				$optElt->setValue($optName);
			}
			if(!preg_match("/column/", strtolower($optName))){
				$optElt->addValidator(
					tao_helpers_form_FormFactory::getValidator('NotEmpty')
				);
			}
			$this->form->addElement($optElt);
		}
		$this->form->createGroup('options', __('CSV Options'), array_keys($options));
		

		$descElt = tao_helpers_form_FormFactory::getElement('csv_desc', 'Label');
		$descElt->setValue(__('Please upload a CSV file formated as defined by the options above.'));
		$this->form->addElement($descElt);
		
		//create file upload form box
		$fileElt = tao_helpers_form_FormFactory::getElement('source', 'AsyncFile');
		$fileElt->setDescription(__("Add the source file"));
  	  	if(isset($_POST['import_sent_csv'])){
			$fileElt->addValidator(tao_helpers_form_FormFactory::getValidator('NotEmpty'));
		}
		else{
			$fileElt->addValidator(tao_helpers_form_FormFactory::getValidator('NotEmpty', array('message' => '')));
		}
		$fileElt->addValidators(array(
			tao_helpers_form_FormFactory::getValidator('FileMimeType', array('mimetype' => array('text/plain', 'text/csv', 'text/comma-separated-values', 'application/csv', 'application/csv-tab-delimited-table'), 'extension' => array('csv', 'txt'))),
			tao_helpers_form_FormFactory::getValidator('FileSize', array('max' => self::UPLOAD_MAX))
		));
		
		$this->form->addElement($fileElt);
		$this->form->createGroup('file', __('Upload CSV File'), array('csv_desc', 'source'));
		
		$csvSentElt = tao_helpers_form_FormFactory::getElement('import_sent_csv', 'Hidden');
		$csvSentElt->setValue(1);
		$this->form->addElement($csvSentElt);
    	
        // section 127-0-1-1-2993bc96:12baebd89c3:-8000:0000000000002671 end
    }

} /* end of class tao_actions_form_Import */

?>