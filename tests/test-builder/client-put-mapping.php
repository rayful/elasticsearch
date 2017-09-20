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

$properties = [
    'name'=>[
        'type'=>'string'
    ],
    'password'=>[
        'type'=>'string'
    ],
    'age'=>[
        'type'=>'integer'
    ],
    'date'=>[
        'type'=>'date'
    ]
];

$params = [
    'index' => $db,
    'type' => $collection,
    'body' => [
        $collection => [
            '_source' => [
                'enabled' => true
            ],
            'properties' => $properties
        ]
    ]
];

// Update the index mapping
$client->indices()->putMapping($params);

