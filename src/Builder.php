<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/19/17
 * Time: 2:56 PM
 */

namespace Rayful\Elasticsearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class Builder
{
    /**
     * @var array
     */
    private $definitions = [];

    public function __construct($connectionParams)
    {
        if (!is_array($connectionParams)) {
            settype($connectionParams, 'array');
        }

        $this->setLog();

        $client = ClientBuilder::create()
            ->setHosts($connectionParams)
            ->setLogger($this->get('logger'))
            ->build();

        $this->set(Client::class, $client);
    }

    public function set($id, $service)
    {
        $this->definitions[$id] = $service;
    }

    public function get($id)
    {
        if (isset($this->definitions[$id])) {
            return $this->definitions[$id];
        }
        return false;
    }

    private function setLog()
    {
        $logPath = __DIR__ . '/../var/logs/rayful-elasticsearch.log.' . date("Y-m-d");
        $handler = new StreamHandler($logPath, Logger::INFO, true, 0777);
        $handler->setFormatter(new JsonFormatter);

        $logger = new Logger(__CLASS__);
        $logger->pushHandler($handler);

        $this->set('logger', $logger);
    }

}