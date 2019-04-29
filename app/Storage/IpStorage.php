<?php

namespace App\Storage;

interface IpStorage
{
    /**
     * @param string $ip
     * @return IpInfo 
     */
    public function getIpInfo($ip);
    
    /**
     * @param IpInfo $ipInfo
     * @return boolean
     */
    public function setIpInfo($ipInfo);
}