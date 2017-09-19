<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/19/17
 * Time: 2:57 PM
 */

require __DIR__ . '/../vendor/autoload.php';

use Rayful\Elasticsearch\Builder;

$connectionParams = '127.0.0.1:9200';
$esbuilder = new Builder($connectionParams);

$db = 'lvinkim';
$collection = 'user';
$docJson = '{"_id":{"$id":"59b79cf119b7880d6f8b4567"},"name":"lvinkim163809","passwod":"18ffb150a101acdc74e882dbe5cba5db","age":14}';

$response = $esbuilder->addIndex($db, $collection, $docJson);

var_dump($response);



