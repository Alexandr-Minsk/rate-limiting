<?php

namespace App\Middleware;

use App\RequestWrapper;
use App\Storage\IpInfo;
use App\Storage\StorageFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RateLimiting
 * @package App\Middleware
 * @property IpInfo|null $ipInfo
 */
class RateLimiting implements MiddlewareInterface
{
    const BLOCK_IN_SEC = 60;

    private $ipInfo;

    /**
     * @param RequestWrapper $request
     * @return RequestWrapper|Response
     * @throws \Exception
     */
    public function handle(RequestWrapper $request) {
        $ip = $request->getClientIp();
        $storage = StorageFactory::create(static::getStorageTypeFromConfig());
        $this->ipInfo = $storage->getIpInfo($ip);

        if (is_null($this->ipInfo)) {
            $storage->setIpInfo(new IpInfo($ip));
            return $request;
        }

        if ($this->ipIsBlocked()) {
            return $this->createBlockedResponse();
        }

        if ($this->ipInfo->attempt < 5) {
            $this->ipInfo->addAttempt();
            $storage->setIpInfo($this->ipInfo);
            return $request;
        }
        if (($this->ipInfo->requestTimestamp + 60) > time()) {
            $this->ipInfo = $this->ipInfo->lock();
            $storage->setIpInfo($this->ipInfo);
            return $this->createBlockedResponse();
        }

        $storage->setIpInfo(new IpInfo($ip));

        return $request;
    }

    /**
     * @return bool
     */
    private function ipIsBlocked() {
        if (isset($this->ipInfo->lockTimestamp)) {
            return $this->getEndBlockingTimestamp() > time();
        }

        return false;
    }

    /**
     * @return integer
     */
    private function getEndBlockingTimestamp() {
        return $this->ipInfo->lockTimestamp + static::BLOCK_IN_SEC;
    }

    /**
     * @return Response
     */
    private function createBlockedResponse() {
        return Response::create('', 429, ['Retry-After' => $this->getEndBlockingTimestamp() - time()]);
    }

    /**
     * @return string
     */
    private static function getStorageTypeFromConfig() {
        return 'file';
    }
}