<?php

namespace App\Domain\ElasticSearch\BaseIndex\Service;

interface ElasticSearchIndexBuilder
{
    public function getIndex();
    public function getBody();
    public function getMappings();
    public function getSettings();
}