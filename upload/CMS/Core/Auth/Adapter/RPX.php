<?php

namespace Core\Auth\Adapter;

/**
 * @package     CMS
 * @subpackage  Core
 * @category    Auth
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class RPX implements \Zend_Auth_Adapter_Interface
{
    const LOGIN_ERROR = 'The login credentials provided were invalid';
    const UNVALIDATED_USER = 'Unvalidated users cannot login';

    protected $_apiKey;

    protected $token;
    
    protected $identifier;

    public function __construct($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        /* STEP 2: Use the token to make the auth_info API call */
        $post_data = array('token' => $this->token,
                         'apiKey' => $this->_apiKey,
                         'format' => 'json');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://rpxnow.com/api/v2/auth_info');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $raw_json = curl_exec($curl);
        curl_close($curl);


        /* STEP 3: Parse the JSON auth_info response */
        $auth_info = json_decode($raw_json, true);

        if ($auth_info['stat'] == 'ok') {

            /* STEP 3 Continued: Extract the 'identifier' from the response */
            $profile = $auth_info['profile'];
            $this->identifier = $profile['identifier'];

            /** set profile data: {@link https://rpxnow.com/docs#profile_data documentation} */
            $session = new \Zend_Session_Namespace('openid');
            $session->profile = $profile;
            
            return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $this->identifier);
        } else {
            return $this->_authError(null, self::LOGIN_ERROR);
        }
    }

    /**
     * Return an authentication error
     *
     * @param  string $msg
     * @return Zend_Auth_Result
     */
    protected function _authError($currentUser, $msg)
    {
        return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE, $currentUser, array($msg));
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }
}