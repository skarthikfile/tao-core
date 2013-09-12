<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2013 (original work) Open Assessment Techonologies SA (under the project TAO-PRODUCT);
 *               
 * 
 */

/**
 * Service to manage services and calls to these services
 * 
 * @author Joel Bout, <joel@taotesting.com>
 */
class tao_models_classes_service_ServiceCallHelper
{

    public static function getBaseUrl( core_kernel_classes_Resource $serviceDefinition) {
        $serviceDefinitionUrl = $serviceDefinition->getOnePropertyValue(new core_kernel_classes_Property(PROPERTY_SUPPORTSERVICES_URL));
        if($serviceDefinitionUrl instanceof core_kernel_classes_Literal){
            $serviceUrl = $serviceDefinitionUrl->literal;
        }else if($serviceDefinitionUrl instanceof core_kernel_classes_Resource){
            // hack nescessary since fully qualified urls are considered to be resources
            $serviceUrl = $serviceDefinitionUrl->getUri();
        } else {
            throw new common_exception_InconsistentData('Invalid service definition url for '.$serviceDefinition->getUri());
        }
        // Remove the parameters because they are only for show, and they are actualy encoded in the variables
        $urlPart = explode('?',$serviceUrl);
        $returnValue = $urlPart[0];
        if(preg_match('/^\//i', $returnValue)){
            //create absolute url (prevent issue when TAO installed on a subfolder
            $returnValue = ROOT_URL.ltrim($returnValue, '/');
        }
        return $returnValue;
    }
    
    public static function getInputValues(tao_models_classes_service_ServiceCall $serviceCall, $callParameters) {
        $returnValue = array();
        foreach ($serviceCall->getInParameters() as $param) {
            $paramDefinition = $param->getDefinition();
            $paramKey = common_Utils::fullTrim($paramDefinition->getUniquePropertyValue(new core_kernel_classes_Property(PROPERTY_FORMALPARAMETER_NAME)));
            switch (get_class($param)) {
            	case 'tao_models_classes_service_ConstantParameter' :
            	    $returnValue[$paramKey] = $param->getValue();
            	    break;
            	case 'tao_models_classes_service_VariableParameter' :
            	    if (isset($callParameters[$paramKey])) {
            	        $returnValue[$paramKey] = $callParameters;
            	    } else {
            	        common_Logger::w('No parameter provided for variable '.$paramDefinition->getUri());
            	    }
            	    break;
            	default:
            	    throw new common_exception_Error('Unknown class of parameter: '.get_class($param));
            }
        }
        return $returnValue;
    }
}