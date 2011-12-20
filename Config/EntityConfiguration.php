<?php

namespace JFSF\Bundle\UploadBundle\Config;

class EntityConfiguration implements EntityConfigurationInterface
{
    protected $uploaderConfiguration;
    
    protected $class;
    
    public function getClass()
    {
        return $this->class;
    }
    
    private $index = 0;
    
    private $properties = array();  

    public function __construct(UploaderConfigurationInterface $uploaderConfiguration, $class, array $configuration)
    {
        if(!class_exists($class)){
            throw new InvalidConfigurationException(sprintf('Uploader configuration has an error, class "%s" doesn\'t exist.', $class));
        }
        
        $this->uploaderConfiguration = $uploaderConfiguration;
        $this->class = $class;
        
        foreach ($configuration as $property => $parameters)
        {
            $this->properties[] = new PropertyConfiguration($this, $property, $parameters);
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