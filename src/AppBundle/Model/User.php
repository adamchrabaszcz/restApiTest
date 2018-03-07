<?php 

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
* User model class 
*/
class User extends LoginUser
{
    /**
     * name.
     *
     * @var string $name
     * @Serializer\Groups({"post"})
     * @Serializer\SerializedName("name") 
     * @Serializer\Type("string")  
     * @Assert\NotBlank
     * @Assert\Length(max=10)     
     */
    protected $name;
    
    /**
     * token.
     *
     * @var string $token
     */
    protected $token;
    
    /**
     * Set Token.
     *
     * @param string $token
     *
     * @return this
     */
    public function setToken(string $token)
    {
      $this->token = $token;
      
      return $this;
    }
    
    /**
     * Get Token.
     *
     * @return string
     */
    public function getToken()
    {
      return $this->token;
    }
    
    /**
     * Set Name.
     *
     * @param string $name
     *
     * @return this
     */
    public function setName(string $name)
    {
      $this->name = $name;
      
      return $this;
    }
    
    /**
     * Get Name.
     *
     * @return string
     */
    public function getName()
    {
      return $this->name;
    }
}