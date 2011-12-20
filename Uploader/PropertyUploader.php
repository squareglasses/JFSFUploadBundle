<?php

namespace JFSF\Bundle\UploadBundle\Uploader;

use JFSF\Bundle\UploadBundle\Config\PropertyConfigurationInterface;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PropertyUploader
{
    protected $entityUploader;
    
    public function getEntityUploader()
    {
        return $this->entityUploader;
    }
    
    protected $property;
    
    public function getProperty()
    {
        return $this->property;
    }
    
    protected $propertyConfiguration;
    
    public function getPropertyConfiguration()
    {
        return $this->propertyConfiguration;
    }

    protected $uplodedFile;
    
    public function getUplodedFile()
    {
        return $this->uplodedFile;
    }
    
    public function __construct(EntityUploader $entityUploader, $property, PropertyConfigurationInterface $propertyConfiguration, UploadedFile $uplodedFile)
    {
        $this->entityUploader = $entityUploader;
        $this->property = $property;
        $this->propertyConfiguration = $propertyConfiguration;
        $this->uplodedFile = $uplodedFile;
    }
    
    public function preUpload(array $options)
    {
        $filename = empty($options[$this->property]['filename']) ? $this->getFilename() : $options[$this->property]['filename'];
        
        if($this->propertyConfiguration->isPublic()){
            $this->entityUploader->getEntity()->{$this->property} = $filename;
        }
        else {
            $setter = $this->propertyConfiguration->getSetter();
            $this->entityUploader->getEntity()->{$setter}( $filename );
        }
    }
    
    public function upload()
    {
        if($this->propertyConfiguration->isPublic()){
            $filename = $this->entityUploader->getEntity()->{$this->property};
        }
        else {
            $getter = $this->propertyConfiguration->getGetter();
            $filename = $this->entityUploader->getEntity()->{$getter}();
        }
	$this->uplodedFile->move( $this->propertyConfiguration->getDestination() , $filename );

        unset($this->entityUploader->getEntity()->{ $this->propertyConfiguration->getFile() });
    }
    
    protected function getFilename()
    {
        if($this->entityUploader->getUploader()->getUniqueFilename()) {
            return uniqid().'.'. pathinfo($this->uplodedFile->getClientOriginalName(), PATHINFO_EXTENSION);
        }
        else {
            return $this->uplodedFile->getClientOriginalName();
        }
    }
}
