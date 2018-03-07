<?php 

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
* LoginUser model class 
*/
class LoginUser
{
    
    /**
     * password.
     *
     * @var string $password
     * @Serializer\Groups({"post"})
     * @Serializer\SerializedName("password")    
     * @Serializer\Type("string") 
     * @Assert\NotBlank
     * @Assert\Length(max=10)      
     */
    protected $password;
    
    /**
     * email.
     *
     * @Serializer\Groups({"post"})
     * @Serializer\SerializedName("email")    
     * @Serializer\Type("string") 
     * @Assert\NotBlank
     * @Assert\Email
     * @var string $email 
     */
    protected $email;
    
    /**
     * Set Email.
     *
     * @param string $email 
     *
     * @return this
     */
    public function setEmail (string $email)
    {
      $this->email  = $email;
      
      return $this;
    }
    
    /**
     * Get Email.
     *
     * @return string
     */
    public function getEmail ()
    {
      return $this->email;
    }

    /**
     * Set Password.
     *
     * @param string $password
     *
     * @return this
     */
    public function setPassword(string $password)
    {
      $this->password = $password;
      
      return $this;
    }
    
    /**
     * Get Password.
     *
     * @return string
     */
    public function getPassword()
    {
      return $this->password;
    }
}