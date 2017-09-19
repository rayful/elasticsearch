<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 9/19/17
 * Time: 5:18 PM
 */

require __DIR__ . '/../vendor/autoload.php';

use Rayful\Elasticsearch\Builder;

$connectionParams = '127.0.0.1:9200';
$esbuilder = new Builder($connectionParams);

$db = 'lvinkim';
$collection = 'user';
$docJsons = getDocJsons();

$esbuilder->bulkIndex($db, $collection, $docJsons);

var_dump($response);

function getDocJsons()
{
    for ($i = 0; $i < 10000; $i++) {
        $doc = [
            "_id" => ['$id' => $i],
            "name" => "lvinkim" . rand(1, 100000),
            "passwod" => md5(rand(1, 10000)),
            "age" => rand(10, 90)
        ];
        yield json_encode($doc);
    }
}