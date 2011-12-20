<?php

namespace JFSF\Bundle\UploadBundle\Listener;

use Doctrine\Common\EventArgs;

interface UploaderListenerInterface
{
    function prePersist(EventArgs $eventArgs);
    
    function preUpdate(EventArgs $eventArgs);

    function postPersist(EventArgs $eventArgs);
    
    function postUpdate(EventArgs $eventArgs);
}