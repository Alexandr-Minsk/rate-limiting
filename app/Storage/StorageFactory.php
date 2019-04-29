<?php

namespace App\Storage;

use Exception;

class StorageFactory
{
    /**
     * @param string $type
     * @return IpStorage 
     * @throws Exception
     */
    public static function create($type) {
        $className = sprintf('App\Storage\%sStorage', ucfirst($type));
        if (!class_exists($className)) {
            throw new Exception('Not implemented');
        }
        
        return new $className;
    }
}