<?php

namespace AppBundle\Client;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Inject;
use calling\mothership as Client;
use AppBundle\Model\{User, LoginUser};
use AppBundle\Exception\MethodNotAllowedException;

/**
 * Class MothershipClient
 * Class for providing communication with mothership :D
 *
 * @Service("mothership.client")
 *
 * @author Adam Chrabaszcz
 */
class MothershipClient
{
    /**
     * client.
     *
     * @var Client $client
     */
    protected $client;
    
    public function __construct()
    {
        $this->client = new Client();
    }
    
    /**
     * login
     *
     * @param User $user 
     * @return void
     */
    public function login(LoginUser $user)
    {
        return $this->callClient('log_in', 
            [
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
            ]
        );
    }
    
    /**
     * Sign Up
     *
     * @param User $user 
     * @return string
     * @todo bcrypt password O.O!!!!!
     */
    public function signUp(User $user)
    {
        return $this->callClient('sign_up', 
            [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
            ]
        );
    }
    
    /**
     * Select Company Unique Id Suggestions
     *
     * @param string $token 
     * @param string $company 
     * @return array
     */
    public function selectCompanyUniqueIdSuggestions($token, $company)
    {
        return $this->callClient('select_company_unique_id_suggestions', 
            [
                'token' => $token,
                'company_name' => $company,
            ]
        );
    }
    
    /**
     * Select Company Unique Id Suggestions
     *
     * @param string $token 
     * @param string $company 
     * @return array
     */
    public function selectCompanyUniqueId($token, $companyId)
    {
        return $this->callClient('select_company_unique_id', 
            [
                'token' => $token,
                'unique_id' => $companyId,
            ]
        );
    }
    
    /**
     * Add Company Unique Id to account
     *
     * @param string $token 
     * @param string $companyId 
     * @return void    
     */
    public function addCompanyUniqueIdToAccount($token, $companyId)
    {
        return $this->callClient('add_company_unique_id_to_account', 
            [
                'token' => $token,
                'company_unique_id' => $companyId,
            ]
        );
    }
    
    /**
     * call Client method
     *
     * @param string $method 
     * @param array $data 
     * @return void
     */
    protected function callClient(string $method, array $data)
    {
        if (! in_array($method, [
                'log_in', 
                'sign_up', 
                'select_company_unique_id_suggestions', 
                'add_company_unique_id_to_account', 
                'select_company_unique_id'
        ])) {
            throw new MethodNotAllowedException(sprintf("Method %s not allowed.", $method));
        }
        
        try {
            return $this->client->{$method}($data);            
        } catch (\Exception $e) {
            return sprintf('Caught exception: %s',  $e->getMessage());
        }
    }
    
    /**
     * Get Client.
     *
     * @return Client
     */
    protected function getClient()
    {
      return $this->client;
    }
}