<?php

namespace App\Domain\ElasticSearch\BaseIndex\Service;

class BaseIndexBuilder implements ElasticSearchIndexBuilder
{
    public function getIndex(): array
    {
        return ['index' => 'my_index'];
    }

    public function getBody(): array
    {
        return [
            'settings' => $this->getSettings(),
            'mappings' => $this->getMappings()
        ];
    }

    public function getSettings(): array {
        return [
            'number_of_shards' => 1,
            'number_of_replicas' => 0,
            'analysis' => [
                'filter' => [
                    'shingle' => [
                        'type' => 'shingle'
                    ]
                ],
                'char_filter' => [
                    'pre_negs' => [
                        'type' => 'pattern_replace',
                        'pattern' => '(\\w+)\\s+((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\b',
                        'replacement' => '~$1 $2'
                    ],
                    'post_negs' => [
                        'type' => 'pattern_replace',
                        'pattern' => '\\b((?i:never|no|nothing|nowhere|noone|none|not|havent|hasnt|hadnt|cant|couldnt|shouldnt|wont|wouldnt|dont|doesnt|didnt|isnt|arent|aint))\\s+(\\w+)',
                        'replacement' => '$1 ~$2'
                    ]
                ],
                'analyzer' => [
                    'reuters' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase', 'stop', 'kstem']
                    ]
                ]
            ]
        ];
    }

    public function getMappings(): array
    {
        return [
            '_source' => [
                'enabled' => true
            ],
            'properties' => [
                'name' => [
                    'type' => 'keyword'
                ]
            ]];
    }
}