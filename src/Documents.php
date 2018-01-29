<?php
declare(strict_types=1);

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use stdClass;

class Documents
{
    /** @var Issuu */
    private $issuu;

    public function __construct(Issuu $issuu)
    {
        $this->issuu = $issuu;
    }

    /**
     * Add a document to a user’s profile by specifying its location on the web.
     * @see http://developers.issuu.com/managing-your-publications/documents/url-upload/
     * @param  string  $file            Full path to the file
     * @param  string  $name            Value determining the URL address of the publication http://issuu.com//docs/
     * @param  string  $title           Title of the publication
     * @param  string  $tags            List of keywords describing the content
     * @param  boolean $commentsAllowed Can other people comment on this document?
     * @param  string  $description     Description of the document
     * @param  boolean $downloadable    Can other people download the original document?
     * @param  string  $infoLink        URL linking to more information about this document
     * @param  string  $language        2 char language code. See http://developers.issuu.com/managing-your-publications/documents/language-codes/ for allowed values
     * @param  string  $access          Must be “public” or “private” - default is “public”.
     * @param  boolean $explicit        If the publication contains explicit content this should be set to “true”
     * @param  string  $category        6 digit code indicating Document Category - See http://developers.issuu.com/managing-your-publications/documents/category/
     * @param  string  $type            6 digit code indicating Document Type - See http://developers.issuu.com/managing-your-publications/documents/type/
     * @param  boolean $ratingAllowed   Can other people rate this document?
     * @param  string  $publishDate     Datetime when this document was originally published. Default is at the time of upload. See http://developers.issuu.com/managing-your-publications/date-and-time-formats/ for formatting rules
     * @param  string  $folderIds       Folders to copy the document to when processing is done. Use method Folders list to find the id of a specific folder - See http://developers.issuu.com/managing-your-publications/folders/list/
     * @return stdClass
     */
    public function upload(
        string $file,
        string $name = null,
        string $title = null,
        string $tags = null,
        bool $commentsAllowed = true,
        string $description = null,
        bool $downloadable = false,
        string $infoLink = null,
        string $language = null,
        string $access = 'public',
        bool $explicit = false,
        string $category = null,
        string $type = null,
        bool $ratingAllowed = true,
        string $publishDate = null,
        string $folderIds = null
    ): stdClass {
        // Check that file exists
        if (!file_exists($file)) {
            throw new FileDoesNotExist();
        }

        $query = [
            'action' => 'issuu.document.upload',
            'file' => $file,
            'name' => $name,
            'title' => $title,
            'tags' => $tags,
            'commentsAllowed' => $commentsAllowed,
            'description' => $description,
            'downloadable' => $downloadable,
            'infoLink' => $infoLink,
            'language' => $language,
            'access' => $access,
            'explicit' => $explicit,
            'category' => $category,
            'type' => $type,
            'ratingAllowed' => $ratingAllowed,
            'publishDate' => $publishDate,
            'folderIds' => $folderIds,
        ];

        // Perform query
        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->_content;
    }

    /**
     * Add a document to a user’s profile by specifying its location on the web.
     * @see http://developers.issuu.com/managing-your-publications/documents/url-upload/
     * @param  string  $slurpUrl        URL of document to be uploaded.
     * @param  string  $name            Value determining the URL address of the publication http://issuu.com//docs/
     * @param  string  $title           Title of the publication
     * @param  string  $tags            List of keywords describing the content
     * @param  boolean $commentsAllowed Can other people comment on this document?
     * @param  string  $description     Description of the document
     * @param  boolean $downloadable    Can other people download the original document?
     * @param  string  $infoLink        URL linking to more information about this document
     * @param  string  $language        2 char language code. See http://developers.issuu.com/managing-your-publications/documents/language-codes/ for allowed values
     * @param  string  $access          Must be “public” or “private” - default is “public”.
     * @param  boolean $explicit        If the publication contains explicit content this should be set to “true”
     * @param  string  $category        6 digit code indicating Document Category - See http://developers.issuu.com/managing-your-publications/documents/category/
     * @param  string  $type            6 digit code indicating Document Type - See http://developers.issuu.com/managing-your-publications/documents/type/
     * @param  boolean $ratingAllowed   Can other people rate this document?
     * @param  string  $publishDate     Datetime when this document was originally published. Default is at the time of upload. See http://developers.issuu.com/managing-your-publications/date-and-time-formats/ for formatting rules
     * @param  string  $folderIds       Folders to copy the document to when processing is done. Use method Folders list to find the id of a specific folder - See http://developers.issuu.com/managing-your-publications/folders/list/
     * @return stdClass
     */
    public function urlUpload(
        string $slurpUrl,
        string $name = null,
        string $title = null,
        string $tags = null,
        bool $commentsAllowed = true,
        string $description = null,
        bool $downloadable = false,
        string $infoLink = null,
        string $language = null,
        string $access = 'public',
        bool $explicit = false,
        string $category = null,
        string $type = null,
        bool $ratingAllowed = true,
        string $publishDate = null,
        string $folderIds = null
    ): stdClass {
        $query = [
            'action' => 'issuu.document.url_upload',
            'slurpUrl' => $slurpUrl,
            'name' => $name,
            'title' => $title,
            'tags' => $tags,
            'commentsAllowed' => $commentsAllowed,
            'description' => $description,
            'downloadable' => $downloadable,
            'infoLink' => $infoLink,
            'language' => $language,
            'access' => $access,
            'explicit' => $explicit,
            'category' => $category,
            'type' => $type,
            'ratingAllowed' => $ratingAllowed,
            'publishDate' => $publishDate,
            'folderIds' => $folderIds,
        ];

        // Perform query
        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->_content;
    }

    /**
     * Get the list of documents from Issuu
     * @see http://developers.issuu.com/managing-your-publications/documents/list/
     * @param  string  $documentStates Comma-separated list document states indicated by a single char
     * @param  string  $access         "public" or "private"
     * @param  string  $origins        Comma-separated list of document origins.
     * @param  string  $orgDocTypes    Comma-separated list of original document formats
     * @param  string  $orgDocName     Original filename of document
     * @param  string  $resultOrder    "asc" or "desc"
     * @param  integer $startIndex     Zero based index to start pagination from
     * @param  integer $pageSize       Maximum number of documents to be returned. Value must be between 0 - 30. Default is 10
     * @param  string  $documentSortBy Reponse parameter to sort the result by. Sorting can only be done on a single parameter. Default is no particular sort order
     * @param  string  $responseParams Comma-separated list of response parameters to be returned.
     * @return stdClass
     */
    public function list(
        string $documentStates = null,
        string $access = null,
        string $origins = null,
        string $orgDocTypes = null,
        string $orgDocName = null,
        string $resultOrder = 'asc',
        int $startIndex = 0,
        int $pageSize = 10,
        string $documentSortBy = null,
        string $responseParams = null
    ): stdClass {
        $query = [
            'action' => 'issuu.documents.list',
            'documentStates' => $documentStates,
            'access' => $access,
            'origins' => $origins,
            'orgDocTypes' => $orgDocTypes,
            'orgDocName' => $orgDocName,
            'resultOrder' => $resultOrder,
            'startIndex' => $startIndex,
            'pageSize' => $pageSize,
            'documentSortBy' => $documentSortBy,
            'responseParams' => $responseParams,
        ];

        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->_content->result;
    }

    /**
     * Update a document with the given information.
     * @see http://developers.issuu.com/managing-your-publications/documents/update/
     * @param  string  $name            Value determining the URL address of the publication http://issuu.com//docs/
     * @param  string  $title           Title of the publication
     * @param  string  $tags            List of keywords describing the content
     * @param  string  $description     Description of the document
     * @param  string  $language        2 char language code. See http://developers.issuu.com/managing-your-publications/documents/language-codes/ for allowed values
     * @param  string  $category        6 digit code indicating Document Category - See http://developers.issuu.com/managing-your-publications/documents/category/
     * @param  string  $type            6 digit code indicating Document Type - See http://developers.issuu.com/managing-your-publications/documents/type/
     * @param  string  $publishDate     Datetime when this document was originally published. Default is at the time of upload. See http://developers.issuu.com/managing-your-publications/date-and-time-formats/ for formatting rules
     * @return stdClass
     */
    public function update(
        string $name,
        string $title = null,
        string $tags = null,
        string $description = null,
        string $language = null,
        string $category = null,
        string $type = null,
        string $publishDate = null
    ): stdClass {
        $query = [
            'action' => 'issuu.document.update',
            'name' => $name,
            'title' => $title,
            'tags' => $tags,
            'description' => $description,
            'language' => $language,
            'category' => $category,
            'type' => $type,
            'publishDate' > $publishDate,
        ];

        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->_content;
    }

    /**
     * Delete one or more documents including all comments and ratings.
     * @param  string $names Comma-separated list of document names
     * @return string
     */
    public function delete(string $names): string
    {
        $query = [
            'action' => 'issuu.document.delete',
            'names' => $names,
        ];

        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->stat;
    }
}
