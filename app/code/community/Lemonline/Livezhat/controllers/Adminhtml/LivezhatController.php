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
class Lemonline_Livezhat_Adminhtml_LivezhatController extends Mage_Adminhtml_Controller_Action
{
    /**
     * register action called from Livezhat adminhtml
     * 
     */
    public function registerAction() {
        $fields      = Mage::helper("livezhat")->getRegisterFields();
        $userName    = trim($this->getRequest()->getParam($fields[0]));
        $userEmail   = trim($this->getRequest()->getParam($fields[1]));
        $companyName = trim($this->getRequest()->getParam($fields[2]));
        //$companyId   = trim($this->getRequest()->getParam($fields[3])); // A generated id is used instead (below)
        $companyId = uniqid();
        if(strlen($userName) > 0 &&
           strlen($userEmail) > 0 &&
           strlen($companyName) > 0 &&
           strlen($companyId) > 0
        )
        {
            try {
                $zhatPw = Mage::helper("livezhat")->register($userName, $userEmail, $companyName, $companyId);
                $this->_getSession()->addSuccess(Mage::helper('livezhat')->__('Thank you for your Livezhat registration, %s.', $userName));
                $this->_getSession()->addSuccess(Mage::helper('livezhat')
                    ->__('&raquo; <a href="http://www.livezhat.com/PasswordChange.html?key=%s&email=%s&locale=en" target="_new">Continue to Livezhat account activation</a>', $zhatPw, $userEmail));
            } catch(Exception $e) {
                switch ($e->getCode())
                {
                    case 100:
                        $this->_getSession()->addError(Mage::helper('livezhat')->__('Livezhat registration error : Customer %s already exists with given information', $userName));
                        break;
                    case 300:
                        $this->_getSession()->addError(Mage::helper('livezhat')->__('Livezhat registration error: Unexpected error, request could not be processed'));
                        break;
                    case 301:
                        $this->_getSession()->addError(Mage::helper('livezhat')->__('Livezhat registration error: Unexpected error, interface returned an unexpected result'));
                        break;
                    default:
                        $this->_getSession()->addError(Mage::helper('livezhat')->__('Livezhat registration error: %s %s', $e->getCode(), $e->getMessage()));
                }
            }
        } else {
            $this->_getSession()->addError(Mage::helper('livezhat')->__('All fields are mandatory for Livezhat registration'));
        }
        $this->_redirectReferer();
    }
}
