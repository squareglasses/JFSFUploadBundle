<?php

namespace JFSF\Bundle\UploadBundle\Config;

interface UploaderConfigurationInterface
{
    function getEntityConfiguration($entity);
}