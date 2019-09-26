<?php
namespace lasselehtinen\Issuu\Test;

use lasselehtinen\Issuu\DocumentEmbed;
use lasselehtinen\Issuu\Issuu;
use Tests\TestCase;

class DocumentEmbedTest extends TestCase
{
    /**
     * Test signature creation
     * @return void
     */
    public function testListingDocumentEmbed()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documentembed-list.json'));
        $documentEmbed = new DocumentEmbed($issuu);
        $documentEmbedsList = $documentEmbed->list();

        $this->assertInternalType('object', $documentEmbedsList);

        // Pagination attributes
        $this->assertAttributeEquals(1, 'totalCount', $documentEmbedsList);
        $this->assertAttributeEquals(0, 'startIndex', $documentEmbedsList);
        $this->assertAttributeEquals(10, 'pageSize', $documentEmbedsList);
        $this->assertAttributeEquals(false, 'more', $documentEmbedsList);

        // Additional checks
        $this->assertInternalType('array', $documentEmbedsList->_content);
        $this->assertCount(1, $documentEmbedsList->_content);
        $this->assertInternalType('object', $documentEmbedsList->_content[0]->documentEmbed);
    }

    /**
     * Test signature creation
     * @return void
     */
    public function testGettingDocumentEmbedHTMLCode()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documentembed-get.html'));
        $documentEmbed = new DocumentEmbed($issuu);
        $documentEmbedHTMLCode = $documentEmbed->get_html_code('1000974/1000068');

        $this->assertInternalType('string', $documentEmbedHTMLCode);
    }

    /**
     * Test updating a document
     * @return void
     */
    public function testUpdatingADocumentEmbed()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documentembed-response.json'));
        $documentEmbed = new DocumentEmbed($issuu);
        $documentEmbedUpdate = $documentEmbed->update('1000974/1000068');

        $this->assertInternalType('object', $documentEmbedUpdate);

        // Additional checks
        $this->assertInternalType('object', $documentEmbedUpdate);
        $this->assertInternalType('object', $documentEmbedUpdate->documentEmbed);
        $this->assertSame(320, $documentEmbedUpdate->documentEmbed->width);
    }

    /**
     * Test deleting a document
     * @return void
     */
    public function testDeletingADocumentEmbed()
    {
        $issuu = $this->createMockedInstance(file_get_contents(__DIR__ . '/SampleResponses/documentembed-delete.json'));
        $documentEmbed = new DocumentEmbed($issuu);
        $documentEmbedUpdate = $documentEmbed->delete('1000974/1000068');

        $this->assertSame('ok', $documentEmbedUpdate);
    }
}
