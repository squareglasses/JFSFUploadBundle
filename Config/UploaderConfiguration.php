<?php

namespace JFSF\Bundle\UploadBundle\Config;

class UploaderConfiguration implements UploaderConfigurationInterface
{
    protected $entities = array();
    
    public function __construct(array $configuration)
    {
        foreach ($configuration as $class => $properties) 
        {
            $this->entities[] = new EntityConfiguration($this, $class, $properties);
        }
    }
    
    public function getEntityConfiguration($entity)
    {
        $entityConfiguration = null;
        
        foreach ($this->entities as $entityConfig) 
        {
            $classEntity = $entityConfig->getClass();
            
            if($entity instanceof $classEntity) {
                $entityConfiguration = $entityConfig;
            }
        }
        
        if(null === $entityConfiguration) {
            throw new InvalidConfigurationException(sprintf('The entity class "%s" is not configured for uploader.', get_class($entity)));
        }
        
        return $entityConfiguration;
    }
}