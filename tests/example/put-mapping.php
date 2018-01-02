<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/20/17
 * Time: 8:50 AM
 */

require __DIR__ . '/../../vendor/autoload.php';

use Ryum\ESClient\Builder;
use Ryum\ESClient\Writer;

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

// 更多 mapping type 见官方文档: https://www.elastic.co/guide/en/elasticsearch/reference/2.3/mapping-types.html

$writer->setNamespace($db, $collection)->putMapping($properties);

