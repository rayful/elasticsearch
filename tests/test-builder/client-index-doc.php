<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 10:44 PM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$connectionParams[] = '127.0.0.1:9200';

$client = ClientBuilder::create()
    ->setHosts($connectionParams)
    ->build();

$db = 'lvinkim';
$collection = 'user';

$id = uniqid();

$document = [
    'name'=>'lvinkim'.rand(1, 100),
    'password'=>md5(rand(1, 100)),
    'age'=>rand(10,90),
    'date'=> time()
];

$params = [
    'index' => $db,
    'type' => $collection,
    'id' => $id,
    'body' => $document
];

// Document will be indexed to my_index/my_type/my_id
$response = $client->index($params);
