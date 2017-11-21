<?php

namespace lasselehtinen\Issuu;

class Bookmarks
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
     * Adding a bookmark
     * @see http://developers.issuu.com/managing-your-publications/bookmarks/add/
     * @param string  $documentUsername Owner of the document
     * @param string  $name             Name of the document
     * @param integer $page             Page in document to bookmark. Default is page 1
     * @param string  $folderIds        Folder to add this bookmark to. If no value is submitted the bookmark will not be added to any folder
     * @return object
     */
    public function add($documentUsername, $name, $page = 1, $folderIds = null)
    {
        $query = [
            'action' => 'issuu.bookmarks.list',
            'documentUsername' => $documentUsername,
            'name' => $name,
            'page' => $page,
            'folderIds' => $folderIds,
        ];

        $bookmark = $this->issuu->getResponse($query);

        return $bookmark->rsp->_content;
    }

    /**
     * List bookmarks
     * @see  http://developers.issuu.com/managing-your-publications/bookmarks/list/
     * @see  http://developers.issuu.com/managing-your-publications/bookmarks/list/#resParam Response parameters list
     * @param  string  $folderId       Folder containing bookmarks to be listed
     * @param  string  $resultOrder    "asc" or "desc". Default value is "asc"
     * @param  integer $startIndex     Zero based index to start pagination
     * @param  integer $pageIndex      Maximum number of documents to be returned. Value must be between 0 - 30. Default is 10.
     * @param  string  $bookmarkSortBy Response parameter to sort the result by. Sorting can only be done on a single parameter. Default is no particular sort order.
     * @param  string  $responseParams Comma-separated list of Response parameter to be returned. If no value is submitted all parameters will be returned
     * @return object
     */
    public function list($folderId = null, $resultOrder = 'asc', $startIndex = 0, $pageIndex = 10, $bookmarkSortBy = null, $responseParams = null)
    {
        $query = [
            'action' => 'issuu.bookmarks.list',
            'folderId' => $folderId,
            'resultOrder' => $resultOrder,
            'startIndex' => $startIndex,
            'pageIndex' => $pageIndex,
            'bookmarkSortBy' => $bookmarkSortBy,
            'responseParams' => $responseParams,
        ];

        $bookmark = $this->issuu->getResponse($query);

        return $bookmark->rsp->_content->result;
    }

    /**
     * Deleting a bookmark
     * @see https://developers.issuu.com/managing-your-publications/bookmarks/delete/
     * @param  string $bookmarkIds
     * @return string
     */
    public function delete($bookmarkIds)
    {
        $query = [
            'action' => 'issuu.bookmarks.delete',
            'bookmarkIds' => $bookmarkIds,
        ];

        // Perform query
        $bookmarks = $this->issuu->getResponse($query);

        return $bookmarks->rsp->stat;
    }
}
