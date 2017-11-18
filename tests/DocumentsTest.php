<?php
namespace lasselehtinen\Issuu\Test;

use lasselehtinen\Issuu\Documents;
use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use lasselehtinen\Issuu\Issuu;
use Tests\TestCase;

class DocumentsTest extends TestCase
{
    /**
     * Test signature creation
     * @return void
     */
    public function testListingDocuments()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-list.json'));
        $documents = new Documents($issuu);
        $documentsList = $documents->list();

        $this->assertInternalType('object', $documentsList);

        // Pagination attributes
        $this->assertAttributeEquals(1349, 'totalCount', $documentsList);
        $this->assertAttributeEquals(0, 'startIndex', $documentsList);
        $this->assertAttributeEquals(10, 'pageSize', $documentsList);
        $this->assertAttributeEquals(true, 'more', $documentsList);

        // Additional checks
        $this->assertInternalType('array', $documentsList->_content);
        $this->assertCount(10, $documentsList->_content);
        $this->assertInternalType('object', $documentsList->_content[0]->document);
    }

    /**
     * Test document upload through URL
     * @return void
     */
    public function testUrlUploadingADocument()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-response.json'));
        $documents = new Documents($issuu);
        $documentsUpload = $documents->urlUpload('http://www.example.com/sample.pdf');

        $this->assertInternalType('object', $documentsUpload);

        // Additional checks
        $this->assertInternalType('object', $documentsUpload);
        $this->assertInternalType('object', $documentsUpload->document);
        $this->assertSame('public', $documentsUpload->document->access);
    }

    /**
     * Test document upload
     * @return void
     */
    public function testUploadingADocument()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-response.json'));
        $documents = new Documents($issuu);
        $documentsUpload = $documents->upload(__DIR__ . '/sample.pdf');

        $this->assertInternalType('object', $documentsUpload);

        // Additional checks
        $this->assertInternalType('object', $documentsUpload);
        $this->assertInternalType('object', $documentsUpload->document);
        $this->assertSame('public', $documentsUpload->document->access);
    }

    /**
     * Non existing file should throw an exception
     * @return void
     */
    public function testUploadingNonExistantFileThrowsException()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-response.json'));
        $documents = new Documents($issuu);

        $this->expectException(FileDoesNotExist::class);
        $documentsUpload = $documents->upload('nonexistant.pdf');
    }

    /**
     * Test updating a document
     * @return void
     */
    public function testUpdatingADocument()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-response.json'));
        $documents = new Documents($issuu);
        $documentsUpload = $documents->update('racing');

        $this->assertInternalType('object', $documentsUpload);

        // Additional checks
        $this->assertInternalType('object', $documentsUpload);
        $this->assertInternalType('object', $documentsUpload->document);
        $this->assertSame('public', $documentsUpload->document->access);
    }

    /**
     * Test deleting a document
     * @return void
     */
    public function testDeletingADocument()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documents-delete.json'));
        $documents = new Documents($issuu);
        $documentsUpload = $documents->delete('racing');

        $this->assertSame('ok', $documentsUpload);
    }
}
