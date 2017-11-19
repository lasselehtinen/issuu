<?php
namespace lasselehtinen\Issuu\Test;

use lasselehtinen\Issuu\Bookmarks;
use lasselehtinen\Issuu\Issuu;
use Tests\TestCase;

class BookmarksTest extends TestCase
{

    /**
     * Test adding a bookmark
     * @return void
     */
    public function testAddingBookmark()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/bookmarks-response.json'));
        $bookmarks = new Bookmarks($issuu);
        $bookmarksAdd = $bookmarks->add('publination', '081024182109-9280632f2866416d97634cdccc66715d');

        // Additional checks
        $this->assertInternalType('object', $bookmarksAdd);
        $this->assertInternalType('object', $bookmarksAdd->bookmark);
        $this->assertSame('Wild Swim: The best outdoor swims across Britain', $bookmarksAdd->bookmark->title);
    }

    /**
     * Test listing bookmarks
     * @return void
     */
    public function testListingBookmarks()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/bookmarks-list.json'));
        $bookmarks = new Bookmarks($issuu);
        $bookmarksList = $bookmarks->list();

        $this->assertInternalType('object', $bookmarksList);

        // Pagination attributes
        $this->assertAttributeEquals(1, 'totalCount', $bookmarksList);
        $this->assertAttributeEquals(0, 'startIndex', $bookmarksList);
        $this->assertAttributeEquals(10, 'pageSize', $bookmarksList);
        $this->assertAttributeEquals(false, 'more', $bookmarksList);

        // Additional checks
        $this->assertInternalType('array', $bookmarksList->_content);
        $this->assertCount(1, $bookmarksList->_content);
        $this->assertInternalType('object', $bookmarksList->_content[0]->bookmark);
    }

    /**
     * Test deleting a bookmark
     * @return void
     */
    public function testDeletingABookmark()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/bookmarks-delete.json'));
        $bookmarks = new Bookmarks($issuu);
        $bookmarksDelete = $bookmarks->delete('11b27cd5-ecdc-4c39-b818-8f3c8eca443c');

        $this->assertSame('ok', $bookmarksDelete);
    }
}
