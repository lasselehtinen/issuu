<?php
namespace lasselehtinen\Issuu\Test;

use Exception;
use Tests\TestCase;
use lasselehtinen\Issuu\Issuu;
use lasselehtinen\Issuu\Drafts;
use lasselehtinen\Issuu\Stacks;

class StacksTest extends TestCase
{
    /**
     * Clean up generated Stacks
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
        
        $stacks = new Stacks($issuu);
        $stacksList = $stacks->list(size: 100, showUnlisted: true);

        foreach ($stacksList->results as $result) {
            if ($result->title === 'Test stack') {
                $stacks->deleteStackById($result->id);
            }
        }
    }

    /**
     * Test getting list of stacks
     * @return void
     */
    public function testListingStacks()
    {
        $stacks = new Stacks($this->issuu);
        $stacksList = $stacks->list();

        $this->assertIsObject($stacksList);

        // Pagination attributes
        $this->assertIsInt($stacksList->count);
        $this->assertSame(10, $stacksList->pageSize);

        // Additional checks
        $this->assertIsArray($stacksList->results);
        $this->assertCount(10, $stacksList->results);
        $this->assertIsString($stacksList->results[0]->id);
    }

    /**
     * Test creating Stacks
     *
     * @return void
     */
    public function testCreatingStacks()
    {
        $stacks = new Stacks($this->issuu);
        $stacksList = $stacks->list(size: 99, showUnlisted: true);
        $countBeforeCreating = $stacksList->count;

        $body = [
            'accessType' => 'UNLISTED',
            'description' => 'Test stack',
            'title' => 'Test stack',
        ];

        $stackCreate = $stacks->create($body);
        $this->assertIsObject($stackCreate);
        $this->assertIsString($stackCreate->content);

        // List Stacks again, count should have increased by one
        $stacksList = $stacks->list(size: 99, showUnlisted: true);
        $this->assertSame($countBeforeCreating+1, $stacksList->count);
    }

    /**
     * Test getting Stack data by ID
     *
     * @return void
     */
    public function testGettingStackDataById()
    {
        $stacks = new Stacks($this->issuu);
        $stacksList = $stacks->list(size: 1);

        // Pagination attributes
        $this->assertIsInt($stacksList->count);
        $this->assertSame(1, $stacksList->pageSize);

        // Additional checks
        $this->assertIsArray($stacksList->results);
        $this->assertCount(1, $stacksList->results);
        $this->assertIsString($stacksList->results[0]->id);
        
        // Get Stack by id
        $stackData = $stacks->getStackDataById($stacksList->results[0]->id);
        $this->assertTrue(property_exists($stackData, 'id'));
        $this->assertSame($stacksList->results[0]->id, $stackData->id);
        $this->assertTrue(property_exists($stackData, 'title'));
        $this->assertTrue(property_exists($stackData, 'description'));
        $this->assertTrue(property_exists($stackData, 'accessType'));
    }

    /**
     * Test deleting Stacks
     *
     * @return void
     */
    public function testDeletingStacks()
    {
        $body = [
            'accessType' => 'UNLISTED',
            'description' => 'Test stack',
            'title' => 'Test stack',
        ];

        $stacks = new Stacks($this->issuu);
        $stackCreate = $stacks->create($body);
        $this->assertIsObject($stackCreate);
        $this->assertIsString($stackCreate->content);

        $stacks->deleteStackById($stackCreate->content);

        // Trying to fetch it should throw exception
        $this->expectException(\GuzzleHttp\Exception\ClientException::class);
        $stacks->getStackDataById($stackCreate->content);
    }

    /**
     * Test getting Stack items
     *
     * @return void
     */
    public function testGettingStackItems()
    {
        $stacks = new Stacks($this->issuu);

        $body = [
            'accessType' => 'UNLISTED',
            'description' => 'Test stack',
            'title' => 'Test stack',
        ];

        $stackCreate = $stacks->create($body);
        $this->assertIsObject($stackCreate);
        $this->assertIsString($stackCreate->content);

        $stackItems = $stacks->getStackItemsSlug($stackCreate->content);

        $this->assertIsObject($stackItems);
        $this->assertIsArray($stackItems->results);
    }

    /**
     * Test adding Publications to Stack by slug
     *
     * @return void
     */
    public function testAddingPublicationsToStackBySlug()
    {
        // Create Draft and publish it as Publication
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

        // Create Stack
        $stacks = new Stacks($this->issuu);
        $stackCreate = $stacks->create(['accessType' => 'UNLISTED', 'description' => 'Test stack', 'title' => 'Test stack']);

        // Add slug to Stack
        $stacks->addStackItemBySlugToStack($stackCreate->content, ['slug' => $createDraft->slug]);

        // Try few times until publication is added to the Stack
        for ($i=0; $i < 10; $i++) {
            $stackItems = $stacks->getStackItemsSlug($stackCreate->content);

            if (count($stackItems->results) > 0) {
                break;
            }

            sleep(5);
        }

        $this->assertIsObject($stackItems);
        $this->assertIsArray($stackItems->results);
        $this->assertCount(1, $stackItems->results);
    }
}
