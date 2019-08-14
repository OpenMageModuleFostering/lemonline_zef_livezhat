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
class Lemonline_Livezhat_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH_CUSTOMER_KEY = 'livezhat/general/customer_key';
    const CONFIG_PATH_ZHAT_KEY     = 'livezhat/general/zhat_key';
    const CONFIG_PATH_USER_EMAIL   = 'livezhat/register/user_email';
    
    /**
     * 
     * @return string
     */
    public function getCustomerKey()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_CUSTOMER_KEY);
    }
    
    /**
     * 
     * @return string
     */
    public function getZhatKey()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_ZHAT_KEY);
    }
    
	/**
     * 
     * @return string
     */
    public function getUserEmail()
    {
        return Mage::getStoreConfig(self::CONFIG_PATH_USER_EMAIL);
    }
    
    /**
     * 
     * @return string
     */
    public function getZhatRegisterUrl()
    {
        return 'https://zefzhat.appspot.com/PartnerAPI?auth=';
    }
    
    /**
     * 
     * @return string
     */
    public function getPartnerId()
    {
        return '169094019';
    }
    
    /**
     * 
     * @return string
     */
    public function getPartnerKey()
    {
        return 'aglzfnplZnpoYXRyEAsSB1BhcnRuZXIYg9fQUAw';
    }
    
    /**
     * 
     * @return array
     */
    public function getRegisterFields()
    {
        return array (
            "livezhat_register_user_name",
            "livezhat_register_user_email",
            "livezhat_register_company_name",
            //"livezhat_register_company_id", //Not used
        );
    }
    
    /**
     * Livezhat registration
     * 
     * @param $userName
     * @param $userEmail
     * @param $companyName
     * @param $companyId
     * @return String zhatPw
     */
    public function register($userName, $userEmail, $companyName, $companyId)
    {
        Mage::getConfig()->saveConfig('livezhat/register/user_name',   $userName);
        Mage::getConfig()->saveConfig('livezhat/register/user_email',  $userEmail);
        Mage::getConfig()->saveConfig('livezhat/register/company_name',$companyName);
        //Mage::getConfig()->saveConfig('livezhat/register/company_id',  $companyId);//Not used in configuration

        $contact_person = array(
            'name'             => $userName,
            'email'            => $userEmail,
        );
        $customer = array(
            'name'             => $companyName,
            'company_id'       => $companyId,
            'contact_person'   => $contact_person,
        );
        $partner = array(
            'id'               => $this->getPartnerId(),
            'create_customer'  => $customer,
        );
        $create_customer = array(
            'livezhat_partner' => $partner,
        );
        $code = $this->getPartnerKey();
        $content = json_encode($create_customer);
        $secret_hash = md5($code . $content);
        $url = $this->getZhatRegisterUrl() . $secret_hash;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        $result     = curl_exec($curl);
        $response   = json_decode($result);
        curl_close($curl);
        if(!isset($response))
        {
            throw new Exception('Unexpected error, request could not be processed', 300);
            
        } else if(isset($response->error))
        {
            $error = $response->error;
            throw new Exception($error->msg, $error->code);
        } else {
            if(isset($response->livezhat_customer->key) &&
               isset($response->livezhat_customer->zhat->default_embed_key) &&
               isset($response->livezhat_customer->pwkey)
            ) {
                $zhatUser = $response->livezhat_customer->key;
                $zhatKey  = $response->livezhat_customer->zhat->default_embed_key; 
                $zhatPw   = $response->livezhat_customer->pwkey;
                Mage::getConfig()->saveConfig('livezhat/general/active','1');
                Mage::getConfig()->saveConfig('livezhat/general/customer_key', $zhatUser);
                Mage::getConfig()->saveConfig('livezhat/general/zhat_key',     $zhatKey);
                Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => 'config'));
                Mage::app()->getCacheInstance()->flush();
                return $zhatPw;
            } else {
                throw new Exception('Unexpected error, interface returned an unexpected result', 301);
            }
        }
    }
}
