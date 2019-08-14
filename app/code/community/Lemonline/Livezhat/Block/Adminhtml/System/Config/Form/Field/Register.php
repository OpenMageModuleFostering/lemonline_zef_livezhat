<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released and licensed under a limited, non-exclusive and non-assignable commercial license by Lemonline.
 *
 * @category   Lemonline
 * @package    Lemonline_Livezhat
 * @copyright  Copyright (c) 2015 Lemonline (http://www.lemonline.fi)
 * @license    http://www.lemonline.fi/licenses/lemonline-license-1.0.txt  Lemonline License
 */
class Lemonline_Livezhat_Block_Adminhtml_System_Config_Form_Field_Register extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * adds Register -button on the Livezhat adminhtml
     * 
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {   
        $this->setElement($element);
        $buttonBlock = $element->getForm()->getParent()->getLayout()->createBlock('adminhtml/widget_button');
        $data = array(
            'label'     => Mage::helper('livezhat')->__('Register'),
            'onclick'   => 'register()',
            'class'     => 'scalable save',
            ''
        );
        $buttonBlock->setData($data);
        $html = $buttonBlock->toHtml();       
        $script = '<script type="text/javascript">function register() {';
        $script .=  'var form = document.createElement("form");';
        $script .=  'form.setAttribute("method", "get");';
        $script .=  'form.setAttribute("action", "'. Mage::helper('adminhtml')->getUrl("*/livezhat/register") . '");';
        foreach (Mage::helper("livezhat")->getRegisterFields() as $field) {
            $script .=  'var field = document.createElement("input");';
            $script .=  'field.setAttribute("type", "hidden");';
            $script .=  'field.setAttribute("name", "' . $field . '");';
            $script .=  'field.setAttribute("value", document.getElementById("' . $field .'").value);';
            $script .=  'form.appendChild(field);';    
        }
        $script .=  'document.body.appendChild(form);';
        $script .=  'form.submit();';      
        $script .=  '}</script>';
        $html = $html . $script;
        return $html;
    }
}
