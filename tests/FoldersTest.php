<?php
namespace lasselehtinen\Issuu\Test;

use lasselehtinen\Issuu\Folders;
use lasselehtinen\Issuu\Issuu;
use Tests\TestCase;

class FoldersTest extends TestCase
{

    /**
     * Test adding a folder
     * @return void
     */
    public function testAddingFolder()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/folders-response.json'));
        $folders = new Folders($issuu);
        $foldersAdd = $folders->add('Cool stuff');

        // Additional checks
        $this->assertInternalType('object', $foldersAdd);
        $this->assertInternalType('object', $foldersAdd->folder);
        $this->assertSame('Stuff I have collected', $foldersAdd->folder->description);
    }

    /**
     * Test listing folders
     * @return void
     */
    public function testListingFolders()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/folders-list.json'));
        $folders = new Folders($issuu);
        $foldersList = $folders->list();

        $this->assertInternalType('object', $foldersList);

        // Pagination attributes
        $this->assertAttributeEquals(1, 'totalCount', $foldersList);
        $this->assertAttributeEquals(0, 'startIndex', $foldersList);
        $this->assertAttributeEquals(10, 'pageSize', $foldersList);
        $this->assertAttributeEquals(false, 'more', $foldersList);

        // Additional checks
        $this->assertInternalType('array', $foldersList->_content);
        $this->assertCount(1, $foldersList->_content);
        $this->assertInternalType('object', $foldersList->_content[0]->folder);
    }

    /**
     * Test updating a folder
     * @return void
     */
    public function testUpdatingAFolder()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/folders-response.json'));
        $folders = new Folders($issuu);
        $foldersUpdate = $folders->update('4c3ba964-60c3-4349-94d0-ff86db2d47c9');

        $this->assertInternalType('object', $foldersUpdate);

        // Additional checks
        $this->assertInternalType('object', $foldersUpdate);
        $this->assertInternalType('object', $foldersUpdate->folder);
        $this->assertSame('Stuff I have collected', $foldersUpdate->folder->description);
    }

    /**
     * Test deleting a document
     * @return void
     */
    public function testDeletingADocument()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/folders-delete.json'));
        $folders = new Folders($issuu);
        $foldersDelete = $folders->delete('4c3ba964-60c3-4349-94d0-ff86db2d47c9');

        $this->assertSame('ok', $foldersDelete);
    }
}
