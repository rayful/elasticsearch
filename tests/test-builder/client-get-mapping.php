<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 10:37 PM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$connectionParams[] = '127.0.0.1:9200';

$client = ClientBuilder::create()
    ->setHosts($connectionParams)
    ->build();

$db = 'lvinkim';
$collection = 'user';

$params = [
    'index' => $db,
    'type' => $collection
];

$response = $client->indices()->getMapping($params);

var_dump($response);
