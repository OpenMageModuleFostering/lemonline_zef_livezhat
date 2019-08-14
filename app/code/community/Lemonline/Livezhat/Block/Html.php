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
class Lemonline_Livezhat_Block_Html extends Mage_Core_Block_Template
{
    /**
     * 
     * @return string
     */
    protected function getCustomerKey()
    {
        return Mage::helper('livezhat')->getCustomerKey();
    }
    
    /**
     * 
     * @return string
     */
    protected function getZhatKey()
    {
        return Mage::helper('livezhat')->getZhatKey();
    }
}
