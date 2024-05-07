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
        $this->assertSame(16, $stacksList->count);
        $this->assertSame(10, $stacksList->pageSize);

        // Additional checks
        $this->assertIsArray($stacksList->results);
        $this->assertCount(10, $stacksList->results);
        $this->assertIsString($stacksList->results[0]->id);
    }
}
