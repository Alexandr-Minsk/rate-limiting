<?php

namespace App\Storage;


class IpInfo
{
    public $ip;
    public $requestTimestamp;
    public $attempt;
    public $lockTimestamp;
    
    public function __construct($ip, $requestTimestamp = null, $attempt = 1, $lockTimestamp = null) {
        $this->ip = $ip;
        $this->requestTimestamp = $requestTimestamp ?? time();
        $this->attempt = $attempt;
        $this->lockTimestamp = $lockTimestamp;
    }
    
    public function getIp() {
        return $this->ip;
    }
    
    public function addAttempt() {
        $this->attempt++;    
        
        return $this;
    }
    
    public function lock() {
        $this->lockTimestamp = time();

        return $this;
    }
    
    public function toJson() {
        return json_encode([
            'requestTimestamp' => $this->requestTimestamp,
            'attempt' => $this->attempt,
            'lockTimestamp' => $this->lockTimestamp
        ]);
    }
}