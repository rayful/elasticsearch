# elasticsearch

使用 PHP 对 Elasticsearch 做索引管理，以及索引新文档

## 安装
```
$ composer install

``` 
## 添加 mapping 

```php
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

// 更多 mapping type 见官方文档: https://www.elastic.co/guide/en/elasticsearch/reference/2.3/mapping-types.html

$writer->setNamespace($db, $collection)->putMapping($properties);
```


## 索引单个文档
```php
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
    'id'=> '59c1cfb9a008b',
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
```


## 索引批量文档
```php
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

$documents = genDocuments();

$writer->setNamespace($db, $collection);

// 手动指定 mapping 字段的类型，如果不手动指定，将由 Elasticsearch 自动生成
$writer->putMapping($properties);

$writer->indexMultiDocuments($documents);

function genDocuments()
{
    for ($i = 0; $i < 10000; $i++) {
        $document = [
            'id' => $i . '-' . uniqid(),
            'name' => 'lvinkim-' . rand(1, 1000),
            'age' => rand(10, 90),
            'balance' => rand(0, 1000),
            'create_at' => time()
        ];
        yield $document;
    }
}
```

