<?php

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;

class Documents extends Issuu
{
    /**
     * Issuu instance
     * @var Issuu
     */
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
     * @return object
     */
    public function upload(
        $file,
        $name = null,
        $title = null,
        $tags = null,
        $commentsAllowed = true,
        $description = null,
        $downloadable = false,
        $infoLink = null,
        $language = null,
        $access = 'public',
        $explicit = false,
        $category = null,
        $type = null,
        $ratingAllowed = true,
        $publishDate = null,
        $folderIds = null
    ) {
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
     * @return object
     */
    public function urlUpload(
        $slurpUrl,
        $name = null,
        $title = null,
        $tags = null,
        $commentsAllowed = true,
        $description = null,
        $downloadable = false,
        $infoLink = null,
        $language = null,
        $access = 'public',
        $explicit = false,
        $category = null,
        $type = null,
        $ratingAllowed = true,
        $publishDate = null,
        $folderIds = null
    ) {
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
     * @return object
     */
    public function list(
        $documentStates = null,
        $access = null,
        $origins = null,
        $orgDocTypes = null,
        $orgDocName = null,
        $resultOrder = 'asc',
        $startIndex = 0,
        $pageSize = 10,
        $documentSortBy = null,
        $responseParams = null
    ) {
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

        // Perform query
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
     * @return object
     */
    public function update(
        $name,
        $title = null,
        $tags = null,
        $description = null,
        $language = null,
        $category = null,
        $type = null,
        $publishDate = null
    ) {
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

        // Perform query
        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->_content;
    }

    /**
     * Delete one or more documents including all comments and ratings.
     * @param  string $names Comma-separated list of document names
     * @return string
     */
    public function delete($names)
    {
        $query = [
            'action' => 'issuu.document.delete',
            'names' => $names,
        ];

        // Perform query
        $documents = $this->issuu->getResponse($query);

        return $documents->rsp->stat;
    }
}
