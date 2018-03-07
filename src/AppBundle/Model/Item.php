<?php 

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
* Item model class 
*/
class Item
{
    /**
     * name.
     *
     * @var string $name
     * @Serializer\SerializedName("name") 
     * @Serializer\Type("string")  
     * @Assert\NotBlank
     * @Assert\Length(max=10)     
     */
    protected $name;
    
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