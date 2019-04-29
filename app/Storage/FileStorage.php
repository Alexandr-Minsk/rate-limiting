<?php

namespace App\Storage;

class FileStorage implements IpStorage
{
    const STORAGE_PATH = 'storage';

    /**
     * @param string $ip
     * @return IpInfo|null
     */
    public function getIpInfo($ip) {
        $filename = static::STORAGE_PATH . '/' . $ip;
        $result = file_exists($filename) ? file_get_contents($filename) : null;
        if (isset($result)) {
            $resultObj = json_decode($result);
            $result = new IpInfo($ip, $resultObj->requestTimestamp, $resultObj->attempt, $resultObj->lockTimestamp);
        } 
        
        return $result;
    }

    /**
     * @param IpInfo $ipInfo
     * @return bool
     */
    public function setIpInfo($ipInfo) {
        try{
            $filename = static::STORAGE_PATH . '/' . $ipInfo->getIp();
            file_put_contents($filename, $ipInfo->toJson());
        } catch (\Exception $e) {
            return false;
        }
        
        return true;
    }
}