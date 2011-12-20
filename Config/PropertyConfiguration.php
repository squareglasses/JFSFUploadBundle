<?php

namespace JFSF\Bundle\UploadBundle\Config;

class PropertyConfiguration implements PropertyConfigurationInterface
{
    protected $entityConfiguration;
    
    protected $property;
    
    public function getProperty()
    {
        return $this->property;
    }
    
    protected $file;
    
    public function getFile()
    {
        return $this->file;
    }
    
    protected $destination;
    
    public function getDestination()
    {
        return $this->destination;
    }
    
    protected $isPublic;
    
    public function isPublic()
    {
        return $this->isPublic;
    }
    
    protected $setter;
    
    public function getSetter()
    {
        return $this->setter;
    }
    
    protected $getter;
    
    public function getGetter()
    {
        return $this->getter;
    }
    
    public function __construct(EntityConfigurationInterface $entityConfiguration, $property, array $configuration)
    {
        if(!isset($configuration['file']) || !isset($configuration['destination'])) {
            $this->error('the parameters "file" and "destination" are mandatory.');
        }
        
        $reflectionClass = new \ReflectionClass($entityConfiguration->getClass()); 
        
        if(!$reflectionClass->hasProperty($configuration['file'])){
            $this->error(sprintf('the property "%s" doesn\'t exist.', $configuration['file']));
        }
        
        $reflectionProperty = $reflectionClass->getProperty($configuration['file']);
        
        if(!$reflectionProperty->isPublic()){
            $this->error(sprintf('the property "%s" isn\'t public.', $configuration['file']));
        }
        
        $this->entityConfiguration = $entityConfiguration;
        $this->property = $property;
        $this->file = $configuration['file'];
        $this->destination = $configuration['destination'];
        $this->isPublic = isset($configuration['public']) && true === $configuration['public'] ? true : false;
        $this->setter = empty($configuration['setter']) ? 'set'.$this->property : $configuration['setter'];
        $this->getter = empty($configuration['getter']) ? 'get'.$this->property : $configuration['getter'];
    }
    
    protected function error($message)
    {
        throw new InvalidConfigurationException(sprintf('Uploader configuration has an error, %s', $message));
    }
}