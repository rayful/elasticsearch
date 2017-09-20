<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 11:20 PM
 */

namespace Rayful\Elasticsearch;

use Elasticsearch\Client;

class IndexingDocument
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function singleDocumentIndexing($index, $type, array $document)
    {
        $this->checkDocument($document);

        $id = $document['id'];
        unset($document['id']);

        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $document
        ];

        $response = $this->client->index($params);
        return $response;
    }

    public function bulkIndexing($index, $type, $documents)
    {
        $number = 0;
        $params = ['body' => []];
        foreach ($documents as $document) {
            $number++;

            if ($number % 1000 == 0) {
                $this->client->bulk($params);
                $params = ['body' => []];
            }

            $this->checkDocument($document);

            $id = $document['id'];
            unset($document['id']);

            $params['body'][] = [
                'index' => [
                    '_index' => $index,
                    '_type' => $type,
                    '_id' => $id
                ]
            ];

            $params['body'][] = $document;
        }

        // Send the last batch if it exists
        if (!empty($params['body'])) {
            $this->client->bulk($params);
        }
    }

    private function checkDocument(array $document)
    {
        if (!isset($document['id'])) {
            throw new \Exception("Document must have key : 'id'.");
        }
    }
}