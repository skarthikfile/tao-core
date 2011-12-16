<?php

error_reporting(E_ALL);

/**
 * TAO - tao/helpers/form/elements/xhtml/class.AsyncFile.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 16.12.2011, 11:52:46 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package tao
 * @subpackage helpers_form_elements_xhtml
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include tao_helpers_form_elements_AsyncFile
 *
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 */
require_once('tao/helpers/form/elements/class.AsyncFile.php');

/* user defined includes */
// section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023D9-includes begin
// section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023D9-includes end

/* user defined constants */
// section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023D9-constants begin
// section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023D9-constants end

/**
 * Short description of class tao_helpers_form_elements_xhtml_AsyncFile
 *
 * @access public
 * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
 * @package tao
 * @subpackage helpers_form_elements_xhtml
 */
class tao_helpers_form_elements_xhtml_AsyncFile
    extends tao_helpers_form_elements_AsyncFile
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method evaluate
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     */
    public function evaluate()
    {
        // section 127-0-1-1-3ba812e2:1284379704f:-8000:00000000000023F8 begin
        
        if(!is_null($this->value)){
        	$struct = @unserialize($this->value);
        	if($struct !== false){
        		$this->value = $struct;
        	}
        }
        $returnValue = $this->value;
        
        // section 127-0-1-1-3ba812e2:1284379704f:-8000:00000000000023F8 end
    }

    /**
     * Short description of method render
     *
     * @access public
     * @author Somsack Sipasseuth, <somsack.sipasseuth@tudor.lu>
     * @return string
     */
    public function render()
    {
        $returnValue = (string) '';

        // section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023DE begin
        
        $widgetName = 'AsyncFileUploader_'.md5($this->name);
		
        $returnValue .= "<label class='form_desc' for='{$this->name}'>". _dh($this->getDescription())."</label>";
        
		$returnValue .= "<div id='{$widgetName}_container' class='form-elt-container file-uploader'>";
        $returnValue .= "<input type='hidden' name='{$this->name}' id='{$this->name}' value='' />";
        $returnValue .= "<input type='file' name='{$widgetName}' id='{$widgetName}' ";
		$returnValue .= $this->renderAttributes();
		$returnValue .= " value='{$this->value}'  />";
		
		$returnValue .= "<br /><span>";
		$returnValue .= "<img src='".TAOBASE_WWW."img/file_upload.png' class='icon' />";
		$returnValue .= "<a href='#' id='{$widgetName}_starter' >".__('Start upload')."</a>";
		$returnValue .= "</span>";

		//get the upload max size (the min of those 3 directives)
		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$fileSize = min($max_upload, $max_post, $memory_limit) * 1024 * 1024;
		
		$extensions = array();
		
		//add a client validation
		foreach($this->validators as $validator){
			//get the valid file extensions
			if($validator instanceof tao_helpers_form_validators_FileMimeType){
				$options = $validator->getOptions();
				if(isset($options['extension'])){
					foreach($options['extension'] as $extension){
						$extensions[] = '*.'.$extension;
					}
				}
			}
			//get the max file size
			if($validator instanceof tao_helpers_form_validators_FileSize){
				$options = $validator->getOptions();
				if(isset($options['max'])){
					$validatorMax = (int)$options['max'];
					if($validatorMax > 0 && $validatorMax < $fileSize){
						$fileSize = $validatorMax;
					}
				}
			}
		}
		
		//default value for 'auto' is 'true':
		$auto = 'true';
		if(isset($this->attributes['auto'])){
			if(!$this->attributes['auto'] || $this->attributes['auto'] === 'false') $auto = 'false'; 
			unset($this->attributes['auto']);
		}
		
		//initialize the AsyncFileUpload Js component
		$id = md5($this->name);
		$returnValue .= '<script type="text/javascript">
			$(document).ready(function(){
				myUploader_'.$id.' = new AsyncFileUpload("#'.$widgetName.'", {
					"scriptData"	: {"session_id": "'.session_id().'"},
					"basePath"  : "'.TAOBASE_WWW.'",
					"sizeLimit"	: '.$fileSize.',';
		if(count($extensions) > 0){
 			$returnValue .='
					"fileDesc"	: "'.__('Allowed files types: ').implode(', ', $extensions).'",
					"fileExt"	: "'.implode(';', $extensions).'",';
		}
		$returnValue .='
					"starter"   : "#'.$widgetName.'_starter",
					"target"	: "#'.$widgetName.'_container input[id=\''.$this->name.'\']",
					"submiter"	: ".form-submiter",
					"auto"      : '.$auto.',
					"folder"    : "/"
				});
				
			});
			</script>';
        $returnValue .= "</div>";
		
        // section 127-0-1-1--79a39ca7:12824fe53d5:-8000:00000000000023DE end

        return (string) $returnValue;
    }

} /* end of class tao_helpers_form_elements_xhtml_AsyncFile */

?>