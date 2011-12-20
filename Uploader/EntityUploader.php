<?php

namespace JFSF\Bundle\UploadBundle\Uploader;

use JFSF\Bundle\UploadBundle\Config\EntityConfigurationInterface;

class EntityUploader implements \Iterator
{
    protected $uploader;
    
    public function getUploader()
    {
        return $this->uploader;
    }
    
    protected $entity;
    
    public function getEntity()
    {
        return $this->entity;
    }
    
    protected $entityConfiguration;
    
    public function getEntityConfiguration()
    {
        return $this->entityConfiguration;
    }
    
    private $index = 0;
    
    private $properties = array();  
    
    public function __construct(Uploader $uploader, $entity, EntityConfigurationInterface $entityConfiguration)
    {
        $this->uploader = $uploader;
        $this->entity = $entity;
        $this->entityConfiguration = $entityConfiguration;
        
        foreach ($this->entityConfiguration as $propertyConfiguration)
        {
            $uploadedFile = $this->entity->{ $propertyConfiguration->getFile() };
            
            if(null !== $uploadedFile) {
                $this->properties[] = new PropertyUploader($this, $propertyConfiguration->getProperty(), $propertyConfiguration, $uploadedFile);
            }
        }
    }

    public function rewind() 
    {
        $this->index = 0;
    }

    public function current() 
    {
        return $this->properties[$this->index];
    }

    public function key() 
    {
        return $this->index;
    }

    public function next() 
    {
        $this->index ++;
    }

    public function valid() 
    {
        return isset($this->properties[$this->index]);
    }
}