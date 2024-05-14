<?php
namespace lasselehtinen\Issuu\Test;

use Exception;
use Tests\TestCase;
use lasselehtinen\Issuu\Issuu;
use lasselehtinen\Issuu\Drafts;
use lasselehtinen\Issuu\Documents;
use lasselehtinen\Issuu\Publications;
use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;

class PublicationsTest extends TestCase
{
    /**
     * Clean up generated Publications
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
        
        $publications = new Publications($issuu);
        $publicationsList = $publications->list(q: 'Test document', size: 50);

        foreach ($publicationsList->results as $result) {
            $publications->deletePublicationBySlug($result->slug);
        }
    }

    /**
     * Test getting list of publications
     * @return void
     */
    public function testListingPublications()
    {
        $publications = new Publications($this->issuu);
        $publicationsList = $publications->list();

        $this->assertIsObject($publicationsList);

        // Pagination attributes
        $this->assertIsInt($publicationsList->count);
        $this->assertSame(10, $publicationsList->pageSize);

        // Additional checks
        $this->assertIsArray($publicationsList->results);
        $this->assertCount(10, $publicationsList->results);
        $this->assertIsString($publicationsList->results[0]->state);
    }

    /**
     * Test getting Publications by slug
     *
     * @return void
     */
    public function testGettingPublicationBySlug()
    {
        $slug = $this->createPublication();
        
        $publications = new Publications($this->issuu);
    
        $getPublicationBySlug = $publications->getPublicationBySlug($slug);
        $this->assertIsObject($getPublicationBySlug);
        $this->assertIsString($getPublicationBySlug->slug);
        $this->assertSame($slug, $getPublicationBySlug->slug);
    }

    /**
     * Test deleting publication by slug
     *
     * @return void
     */
    public function testDeletingPublicationsBySlug()
    {
        $slug = $this->createPublication();

        $publications = new Publications($this->issuu);
        
        // Publication should still exist
        $getPublicationBySlug = $publications->getPublicationBySlug($slug);
        $this->assertIsObject($getPublicationBySlug);
        $this->assertIsString($getPublicationBySlug->slug);
        $this->assertSame($slug, $getPublicationBySlug->slug);

        // Delete Publication
        $publications->deletePublicationBySlug($slug);

        // Trying to get Publication by slug should throw 404 error
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);
        $getPublicationBySlug = $publications->getPublicationBySlug($slug);
    }

    /**
     * Helper method to create Draft and publish it
     *
     * @return string
     */
    public function createPublication()
    {
        $drafts = new Drafts($this->issuu);

        $body = [
            'confirmCopyright' => true,
            'fileUrl' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
            'info' => [
                'file' => 0,
                'access' => 'PUBLIC',
                'title' => 'Test document',
                'description' => 'Description',
                'preview' => false,
                'type' => 'editorial',
                'showDetectedLinks' => false,
                'downloadable' => false,
                'originalPublishDate' => '1970-01-01T00:00:00.000Z',
            ],
        ];

        // Create draft and publish it
        $createDraft = $drafts->create($body);
        
        // Try few times until the file is converted
        for ($i=0; $i < 10; $i++) {
            $draft = $drafts->getDraftBySlug($createDraft->slug);
            $conversionStatus =  $draft->fileInfo->conversionStatus;
            
            if ($conversionStatus === 'DONE') {
                break;
            }

            sleep(2);
        }

        $publishDraftBySlug = $drafts->publishDraftBySlug($createDraft->slug, ['desiredName' => 'foobar']);

        return $createDraft->slug;
    }
}
