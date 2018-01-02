<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/20/17
 * Time: 8:41 AM
 */

namespace Ryum\ESClient;

use Elasticsearch\Client;

class Writer
{
    private $builder;

    private $index;
    private $type;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;

        /** @var Client $client */
        $client = $this->builder->get(Client::class);

        $this->builder->set(IndexingDocument::class, new IndexingDocument($client));
        $this->builder->set(IndexManagement::class, new IndexManagement($client));
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
        $IndexManagement = $this->builder->get(IndexManagement::class);

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
        $IndexingDocument = $this->builder->get(IndexingDocument::class);

        $response = $IndexingDocument->singleDocumentIndexing($this->index, $this->type, $document);

        return $response;
    }

    public function indexMultiDocuments($documents)
    {
        $this->prepare();

        /** @var IndexingDocument $IndexingDocument */
        $IndexingDocument = $this->builder->get(IndexingDocument::class);

        $IndexingDocument->bulkIndexing($this->index, $this->type, $documents);
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


}