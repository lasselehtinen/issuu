<?php
namespace lasselehtinen\Issuu\Test;

use Exception;
use Tests\TestCase;
use lasselehtinen\Issuu\Issuu;
use lasselehtinen\Issuu\Drafts;

class DraftsTest extends TestCase
{
    /**
     * Clean up generated Drafts
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

        $drafts = new Drafts($issuu);
        $draftsList = $drafts->list(q: 'Test document', size: 50);
        
        foreach ($draftsList->results as $result) {
            $drafts->deleteDraftBySlug($result->slug);
        }
    }
    
    /**
     * Test getting list of drafts
     * @return void
     */
    public function testListingDrafts()
    {
        $drafts = new Drafts($this->issuu);
        $draftsList = $drafts->list();

        // Pagination attributes
        $this->assertIsInt($draftsList->count);
        $this->assertIsInt($draftsList->pageSize);

        // Additional checks
        $this->assertIsArray($draftsList->results);
        $this->assertIsString($draftsList->results[0]->slug); /* @phpstan-ignore property.nonObject */
    }

    /**
     * Test creating drafts
     *
     * @return void
     */
    public function testCreatingDrafts()
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

        $createDraft = $drafts->create($body);

        $this->assertIsString($createDraft->slug);
    }

    /**
     * Test getting draft by slug
     *
     * @return void
     */
    public function testGettingDraftBySlug()
    {
        $drafts = new Drafts($this->issuu);

        $getDraftBySlug = $drafts->getDraftBySlug('zp0m1zti7ir');

        $this->assertIsString($getDraftBySlug->slug);
        $this->assertSame('zp0m1zti7ir', $getDraftBySlug->slug);
    }

    /**
     * Test publishing draft by slug
     *
     * @return void
     */
    public function testPublishingDraftBySlug()
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
        $this->assertIsString($publishDraftBySlug->location);
        $this->assertIsString($publishDraftBySlug->publicLocation);
    }

    /**
     * Test deleting draft by slug
     *
     * @return void
     */
    public function testDeletingDraftBySlug()
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

        // Create draft
        $createDraft = $drafts->create($body);
        $getDraftBySlug = $drafts->getDraftBySlug($createDraft->slug);

        $this->assertIsString($getDraftBySlug->slug);
        
        // Delete draft by slug
        $drafts->deleteDraftBySlug($createDraft->slug);

        // Trying to get draft by slug should throw 404 error
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);
        $getDraftBySlug = $drafts->getDraftBySlug($createDraft->slug);
    }

    /**
     * Test updating draft by slug
     *
     * @return void
     */
    public function testUpdatingDraftBySlug()
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

        $createDraft = $drafts->create($body);

        $this->assertIsString($createDraft->slug);

        // Check before and after update
        $getDraftBySlug = $drafts->getDraftBySlug($createDraft->slug);
        $this->assertSame('Description', $getDraftBySlug->changes->description);
        
        $body['info']['description'] = 'Updated description';
        $drafts->updateDraftBySlug($createDraft->slug, $body);
        
        $getDraftBySlug = $drafts->getDraftBySlug($createDraft->slug);
        $this->assertSame('Updated description', $getDraftBySlug->changes->description);
    }
}
