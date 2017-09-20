<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 10:23 PM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$connectionParams[] = '127.0.0.1:9200';

$client = ClientBuilder::create()
    ->setHosts($connectionParams)
    ->build();

$db = 'lvinkim';

$params = [
    'index' => $db
];

// Create the index
$response = $client->indices()->create($params);

var_dump($response);