<?php

namespace App;

class RequestWrapper
{
    private $server;
    public function __construct($server) {
        $this->server = $server;
    }

    /**
     * @return null|string
     */
    public function getClientIp() {
        return $this->server['REMOTE_ADDR'] ?? null;
    }
}