<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 9/19/17
 * Time: 10:51 PM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$connectionParams[] = '127.0.0.1:9200';

$client = ClientBuilder::create()
    ->setHosts($connectionParams)
    ->build();

$db = 'lvinkim';
$collection = 'user';

$params = ['body' => []];

for ($i = 1; $i <= 10; $i++) {
    $params['body'][] = [
        'index' => [
            '_index' => $db,
            '_type' => $collection,
            '_id' => $i.'-'.uniqid()
        ]
    ];

    $params['body'][] = [
        'name'=>'lvinkim'.rand(1, 100),
        'password'=>md5(rand(1, 100)),
        'age'=>rand(10,90),
        'date'=> time()
    ];

    if ($i % 3 == 0) {
        $responses = $client->bulk($params);
        $params = ['body' => []];
        unset($responses);
    }
}

if (!empty($params['body'])) {
    $responses = $client->bulk($params);
}
