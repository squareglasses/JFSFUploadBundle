<?php

namespace JFSF\Bundle\UploadBundle\Uploader;

use JFSF\Bundle\UploadBundle\Config\UploaderConfigurationInterface;

class Uploader implements UploaderInterface
{ 
    protected $configuration;
    protected $isPreUpload = false;
    
    protected $options = array(
        'unique_filename' => true,
    );
    
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function setUniqueFilename($value)
    {
        if(is_bool($value)){
            $this->options['unique_filename'] = $value;
        }
    }
    
    public function getUniqueFilename()
    {
        return $this->options['unique_filename'];
    }
    
    protected $entityUploader;
    
    public function __construct(UploaderConfigurationInterface $configuration, array $options)
    {
        $this->configuration = $configuration;
        
        $this->setOptions($options);
    }
    
    public function preUpload($entity, array $options = array())
    {
        $this->isPreUpload = true;
        $entityConfiguration = $this->configuration->getEntityConfiguration($entity);
        
        if (null !== $entityConfiguration) {

            $this->entityUploader = new EntityUploader($this, $entity, $entityConfiguration);

            foreach ($this->entityUploader as $propertyUploader)
            {
                $propertyUploader->preUpload($options);
            }
        }
    }
    
    public function upload($entity)
    {
        if (false === $this->isPreUpload) {
            throw new UploaderException('Uploading impossible, the method "preUpload" must be called.');
        }
        $this->isPreUpload = false;
        
        if (null !== $this->entityUploader) {
            foreach ($this->entityUploader as $propertyUploader)
            {
                $propertyUploader->upload();
            }

            $this->entityUploader = null;
        }
    }
    
    public function remove($entity)
    {
        $entityConfiguration = $this->configuration->getEntityConfiguration($entity);
        
        foreach ($entityConfiguration as $propertyConfiguration)
        {
            $getter = $propertyConfiguration->getGetter();
            $filename = $entity->$getter();
            
            if($filename) {
                @unlink($propertyConfiguration->getDestination() . '/' . $filename);
            }
        }
    }
}
