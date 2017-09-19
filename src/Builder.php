<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/19/17
 * Time: 2:56 PM
 */

namespace Rayful\Elasticsearch;

use Elasticsearch\ClientBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class Builder
{
    private $client;

    private $logger;

    public function __construct($connectionParams)
    {
        if (!is_array($connectionParams)) {
            settype($connectionParams, 'array');
        }

        $this->setLog();

        $this->client = ClientBuilder::create()
            ->setHosts($connectionParams)
            ->setLogger($this->logger)
            ->build();
    }

    public function addIndex($db, $collection, $docJson)
    {
        $doc = json_decode($docJson, true);

        $params = ['index' => $db, 'type' => $collection];

        if (isset($doc['_id']['$id'])) {
            $params['id'] = $doc['_id']['$id'];
            unset($doc['_id']);
        }

        $params['body'] = $doc;

        $response = $this->client->index($params);

        return $response;
    }

    public function bulkIndex($db, $collection, $docJsons)
    {
        $params = ['body' => []];

        $number = 0;
        foreach ($docJsons as $docJson) {
            $number++;
            if ($number % 1000 == 0) {
                $this->client->bulk($params);
                $params = ['body' => []];
            }

            $doc = json_decode($docJson, true);
            $index = ['_index' => $db, '_type' => $collection];

            if (isset($doc['_id']['$id'])) {
                $index['_id'] = $doc['_id']['$id'];
                unset($doc['_id']);
            }

            $params['body'][] = ['index' => $index];
            $params['body'][] = $doc;
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }

    private function setLog()
    {
        $logPath = __DIR__ . '/../var/logs/rayful-elasticsearch.log.' . date("Y-m-d");
        $handler = new StreamHandler($logPath, Logger::INFO, true, 0777);
        $handler->setFormatter(new JsonFormatter);

        $this->logger = new Logger(__CLASS__);
        $this->logger->pushHandler($handler);
    }


}