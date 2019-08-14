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
class Lemonline_Livezhat_Block_Adminhtml_System_Config_Fieldset_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'lemonline/livezhat/system/config/fieldset/hint.phtml';

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}