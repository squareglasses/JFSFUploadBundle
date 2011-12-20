<?php

namespace JFSF\Bundle\UploadBundle\Listener;

use JFSF\Bundle\UploadBundle\Uploader\UploaderInterface;

use Doctrine\Common\EventArgs;

class UploaderListener implements UploaderListenerInterface
{
    protected $uploader;
    
    public function __construct( UploaderInterface $uploader )
    {
        $this->uploader = $uploader;
    }
    
    public function prePersist(EventArgs $eventArgs)
    {
        $this->uploader->preUpload($eventArgs->getEntity());
    }
    
    public function preUpdate(EventArgs $eventArgs)
    {
        $this->uploader->preUpload($eventArgs->getEntity());
    }
    
    public function postPersist(EventArgs $eventArgs)
    {
        $this->uploader->upload($eventArgs->getEntity());
    }
    
    public function postUpdate(EventArgs $eventArgs)
    {
        $this->uploader->upload($eventArgs->getEntity());
    }
}