<?php

namespace App\Domain\ElasticSearch\BaseIndex\Service;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;

class ElasticService
{
    private $client;
    /**
     * @var ElasticSearchIndexBuilder
     */
    private $indexBuilder;

    public function getInstance(): Client
    {
        if(!$this->client) {
            $this->client = ClientBuilder::create()
                ->setHosts([
                    'http://172.17.0.1:9200'
                ])

                ->setSerializer('\Elasticsearch\Serializers\EverythingToJSONSerializer')
                ->build();
        }

        return $this->client;
    }

    public function __construct(BaseIndexBuilder $indexBuilder)
    {
        $this->getInstance();
        $this->indexBuilder = $indexBuilder;
    }

    public function createIndicesAction(): array
    {
        $index = $this->indexBuilder->getIndex();
        $index['body'] = $this->indexBuilder->getBody();
        return $this->getInstance()->indices()->create($index);
    }

    public function deleteIndicesAction(): array
    {
        return $this->getInstance()->indices()->delete($this->indexBuilder->getIndex());
    }

    public function reindexDocumentAction() {
//                ->setElasticCloudId(getenv('ELASTICSEARCH_API_KEY'))
//                ->setBasicAuthentication(
//                    getenv('ELASTICSEARCH_LOGIN'),
//                    getenv('ELASTICSEARCH_PASSWORD')
//                )
        $this->deleteIndicesAction();
        $this->createIndicesAction();

        $index = $this->indexBuilder->getIndex();
        $index['id'] = 'my_id';
        $index['body'] = [
            'title' => 'test'
        ];
        return $this->getInstance()->index($index);
    }

    public function getIndexAction($index = 'my_index', $id = 'my_id') {
        return $this->getInstance()->get(['index' => $index, 'id' => $id]);
    }

}