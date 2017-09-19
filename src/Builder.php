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
    /**
     * @var array
     */
    private $definitions = [];

    private $index;
    private $type;

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

        $this->set(IndexingDocument::class, new IndexingDocument($client));
        $this->set(IndexManagement::class, new IndexManagement($client));
    }

    public function setNamespace($index, $type)
    {
        $this->index = $index;
        $this->type = $type;

        return $this;
    }

    public function putMapping(array $properties)
    {
        $this->prepare();

        /** @var IndexManagement $IndexManagement */
        $IndexManagement = $this->get(IndexManagement::class);

        if (!$IndexManagement->existsIndex($this->index)) {
            $IndexManagement->createAnIndex($this->index);
        }

        $existsMapping = $IndexManagement->getMappings($this->index, $this->type);

        if ($properties && !$existsMapping) {
            $IndexManagement->putMappings($this->index, $this->type, $properties);
        }

        return $this;
    }

    public function indexSingleDocument(array $document)
    {
        $this->prepare();

        /** @var IndexingDocument $IndexingDocument */
        $IndexingDocument = $this->get(IndexingDocument::class);

        $response = $IndexingDocument->singleDocumentIndexing($this->index, $this->type, $document);

        return $response;
    }

    public function indexMultiDocuments($documents)
    {
        $this->prepare();

        /** @var IndexingDocument $IndexingDocument */
        $IndexingDocument = $this->get(IndexingDocument::class);

        $IndexingDocument->bulkIndexing($this->index, $this->type, $documents);
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

    private function prepare()
    {
        if (!$this->index) {
            throw new \Exception("'index' is required. See setNamespace(...) method.");
        }
        if (!$this->type) {
            throw new \Exception("'type' is required. See setNamespace(...) method.");
        }
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