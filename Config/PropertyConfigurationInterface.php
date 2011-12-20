<?php

namespace JFSF\Bundle\UploadBundle\Config;

interface PropertyConfigurationInterface
{
    function getFile();
    
    function getDestination();
    
    function isPublic();
    
    function getSetter();
    
    function getGetter();
}