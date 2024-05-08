<?php
namespace lasselehtinen\Issuu\Test;

use Tests\TestCase;
use lasselehtinen\Issuu\Issuu;
use lasselehtinen\Issuu\Stacks;

class StacksTest extends TestCase
{
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
        $this->assertObjectHasProperty('id', $stackData);
        $this->assertSame($stacksList->results[0]->id, $stackData->id);
        $this->assertObjectHasProperty('title', $stackData);
        $this->assertObjectHasProperty('description', $stackData);
        $this->assertObjectHasProperty('accessType', $stackData);
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
}
