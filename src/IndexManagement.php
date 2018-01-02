<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 10:55 PM
 */

namespace Ryum\ESClient;

use Elasticsearch\Client;

class IndexManagement
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function existsIndex($index)
    {
        $response = $this->client->indices()->exists(['index' => $index]);
        return $response;
    }

    public function createAnIndex($index)
    {
        $response = $this->client->indices()->create(['index' => $index]);
        return $response;
    }

    public function deleteAnIndex($index)
    {
        $response = $this->client->indices()->delete(['index' => $index]);
        return $response;
    }

    public function getSettings($indexs)
    {
        if (!is_array($indexs)) {
            settype($indexs, 'array');
        }
        $params = ['index' => $indexs];
        $response = $this->client->indices()->getSettings($params);
        return $response;
    }

    public function putMappings($index, $type, $properties)
    {
        $params = [
            'index' => $index,
            'type' => $type,
            'body' => [$type => ['properties' => $properties]]
        ];

        $response = $this->client->indices()->putMapping($params);
        return $response;
    }

    public function getMappings($index = null, $type = null)
    {
        $params = [];

        $index ? ($params['index'] = $index) : null;
        $type ? ($params['type'] = $type) : null;

        $response = $this->client->indices()->getMapping($params);
        return $response;

    }
}