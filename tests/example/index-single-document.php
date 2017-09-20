<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/20/17
 * Time: 8:57 AM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Rayful\Elasticsearch\Builder;
use Rayful\Elasticsearch\Writer;

$connectionParams = '127.0.0.1:9200';
$builder = new Builder($connectionParams);
$writer = new Writer($builder);

$db = 'test';
$collection = 'user';

$properties = [
    'name' => ['type' => 'string'],
    'age' => ['type' => 'integer'],
    'balance' => ['type' => 'double'],
    'create_at' => ['type' => 'date']
];

$document = [
    'name' => 'lvinkim-'.rand(1,1000),
    'age' => rand(10,90),
    'balance' => rand(0,1000),
    'create_at' => time()
];

$writer->setNamespace($db, $collection);

// 手动指定 mapping 字段的类型，如果不手动指定，将由 Elasticsearch 自动生成
$writer->putMapping($properties);

$response = $writer->indexSingleDocument($document);

print_r($response);
