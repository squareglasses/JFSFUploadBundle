<?php

namespace JFSF\Bundle\UploadBundle\Uploader;

interface UploaderInterface
{
    function preUpload($entity, array $options);
    
    function upload($entity);
}