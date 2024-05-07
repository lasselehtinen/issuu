<?php

namespace Tests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use lasselehtinen\Issuu\Issuu;
use lasselehtinen\Issuu\Drafts;
use GuzzleHttp\Handler\MockHandler;
use lasselehtinen\Issuu\Publications;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Issuu instance
     *
     * @var Issuu
     */
    public $issuu;

    /**
     * Common setup for all tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        
        $issuuApiKey = getenv('ISSUU_API_KEY');

        if (empty($issuuApiKey)) {
            throw new Exception('Could not fetch Issuu API key from env variable.');
        }

        $this->issuu = new Issuu($issuuApiKey);
    }

    /**
     * Clean up generated Drafts and Publications
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        $issuuApiKey = getenv('ISSUU_API_KEY');

        if (empty($issuuApiKey)) {
            throw new Exception('Could not fetch Issuu API key from env variable.');
        }

        $issuu = new Issuu($issuuApiKey);
        
        // Remove test Drafts
        $drafts = new Drafts($issuu);
        $draftsList = $drafts->list(q: 'Test document', size: 50);
        
        foreach ($draftsList->results as $result) {
            $drafts->deleteDraftBySlug($result->slug);
        }

        // Remove test Publications
        $publications = new Publications($issuu);
        $publicationsList = $publications->list(q: 'Test document', size: 50);

        foreach ($publicationsList->results as $result) {
            $publications->deletePublicationBySlug($result->slug);
        }
    }
}
