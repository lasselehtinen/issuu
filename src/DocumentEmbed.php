<?php
declare(strict_types=1);

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use stdClass;

class DocumentEmbed
{
    /** @var Issuu */
    private $issuu;

    public function __construct(Issuu $issuu)
    {
        $this->issuu = $issuu;
    }

    /**
     * Get the list of document embeds from Issuu
     * @see https://developer.issuu.com/managing-your-publications/document-embed/list/
     * @param  string  $documentId      Optional filtering by documentId to only retrieve embeds of a specific document
     * @param  string  $resultOrder     "asc" or "desc"
     * @param  integer $startIndex      Zero based index to start pagination from
     * @param  integer $pageSize        Maximum number of documents to be returned. Value must be between 0 - 30. Default is 10
     * @param  string  $embedSortBy     Reponse parameter to sort the result by. Sorting can only be done on a single parameter. Default is no particular sort order
     * @param  string  $responseParams  Comma-separated list of response parameters to be returned.
     * @return stdClass
     */
    public function list(
        string $documentId = null,
        string $resultOrder = 'asc',
        int $startIndex = 0,
        int $pageSize = 10,
        string $embedSortBy = null,
        string $responseParams = null
    ): stdClass {
        $query = [
            'action' => 'issuu.document_embeds.list',
            'documentId' => $documentId,
            'resultOrder' => $resultOrder,
            'startIndex' => $startIndex,
            'pageSize' => $pageSize,
            'embedSortBy' => $embedSortBy,
            'responseParams' => $responseParams,
        ];

        $documentEmbeds = $this->issuu->getResponse($query);

        return $documentEmbeds->rsp->_content->result;
    }


    /**
     * Get the HTML code for the embed
     * @see https://developer.issuu.com/managing-your-publications/document-embed/get-html-code
     * @param  string  $embedId      Optional filtering by embedId to only retrieve embeds of a specific document
     * @return stdClass
     */
    public function get_html_code(
        string $embedId
    ): string {
        $query = [
            'action' => 'issuu.document_embed.get_html_code',
            'embedId' => $embedId,
        ];

        $documentEmbed = $this->issuu->getResponseHTML($query);

        return $documentEmbed;
    }


    /**
     * Add a Document Embed with the given information.
     * @see https://developer.issuu.com/managing-your-publications/document-embed/add/
     * @param  string  $documentId      documentId of the publication
     * @param  int  $readerStartPage The page in the document that should initially be displayed
     * @param  int  $width           Width in pixels of embed widget. If width/height is changed the embed code should be generated again (alternatively the HTML style parameter in the embed code can be manually updated)
     * @param  int  $height          Height in pixels of embed widget
     * @return stdClass
     */
    public function add(
        string $documentId = null,
        int $readerStartPage = 1,
        int $width = 525,
        int $height = 340
    ): stdClass {
        $query = [
            'action' => 'issuu.document_embed.add',
            'documentId' => $documentId,
            'readerStartPage' => $readerStartPage,
            'width' => $width,
            'height' => $height,
        ];

        $embeds = $this->issuu->getResponse($query);

        return $embeds->rsp->_content;
    }

    /**
     * Update a document with the given information.
     * @see https://developer.issuu.com/managing-your-publications/document-embed/update/
     * @param  string  $embedId         embedId of the publication
     * @param  string  $documentId      documentId of the publication
     * @param  string  $readerStartPage The page in the document that should initially be displayed
     * @param  string  $description     Description of the document. Max length: 1000 characters
     * @param  string  $width           Width in pixels of embed widget. If width/height is changed the embed code should be generated again (alternatively the HTML style parameter in the embed code can be manually updated)
     * @param  string  $height          Height in pixels of embed widget
     * @return stdClass
     */
    public function update(
        string $embedId = null,
        string $documentId = null,
        string $readerStartPage = null,
        string $description = null,
        string $width = null,
        string $height = null
    ): stdClass {
        $query = [
            'action' => 'issuu.document_embed.update',
            'embedId' => $embedId,
            'documentId' => $documentId,
            'readerStartPage' => $readerStartPage,
            'description' => $description,
            'width' => $width,
            'height' => $height,
        ];

        $embeds = $this->issuu->getResponse($query);

        return $embeds->rsp->_content;
    }

    /**
     * Delete one or more documents including all comments and ratings.
     * @param  string $names Id number of embed as returned by Add or List calls
     * @return string
     */
    public function delete(string $embedId): string
    {
        $query = [
            'action' => 'issuu.document_embed.delete',
            'embedId' => $embedId,
        ];

        $embeds = $this->issuu->getResponse($query);

        return $embeds->rsp->stat;
    }
}
