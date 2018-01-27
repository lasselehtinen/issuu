<?php
declare(strict_types=1);

namespace lasselehtinen\Issuu;

use stdClass;

class Folders
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
     * Adding a folder
     * @see https://developers.issuu.com/managing-your-publications/folders/add/
     * @param string $folderName        Name of the folder. Must be different from other folder names
     * @param string $folderDescription Description of folder content
     * @return stdClass
     */
    public function add(string $folderName, string $folderDescription = null): stdClass
    {
        $query = [
            'action' => 'issuu.folders.add',
            'folderName' => $folderName,
            'folderDescription' => $folderDescription,
        ];

        $folder = $this->issuu->getResponse($query);

        return $folder->rsp->_content;
    }

    /**
     * List folders
     * @see https://developers.issuu.com/managing-your-publications/folders/list/
     * @param  string  $resultOrder    "asc" or "desc"
     * @param  integer $startIndex     Zero based index to start pagination from
     * @param  integer $pageSize       Maximum number of documents to be returned. Value must be between 0 - 30. Default is 10
     * @param  string  $folderSortBy   Response parameter to sort the result by. Sorting can only be done on a single parameter. Default is no particular sort order
     * @param  string  $responseParams Comma-separated list of response parameters to be returned.
     * @return stdClass
     */
    public function list(
        string $resultOrder = 'asc',
        int $startIndex = 0,
        int $pageSize = 10,
        string $folderSortBy = null,
        string $responseParams = null
    ): stdClass {
        $query = [
            'action' => 'issuu.folders.list',
            'resultOrder' => $resultOrder,
            'startIndex' => $startIndex,
            'pageSize' => $pageSize,
            'folderSortBy' => $folderSortBy,
            'responseParams' => $responseParams,
        ];

        $folders = $this->issuu->getResponse($query);

        return $folders->rsp->_content->result;
    }

    /**
     * Update a folder
     * @see https://developers.issuu.com/managing-your-publications/folders/update/
     * @param  string $folderId          The folder to be updated
     * @param  string $folderName        New name of the folder
     * @param  string $folderDescription New description of the folder
     * @return stdClass
     */
    public function update(string $folderId, string $folderName = null, string $folderDescription = null): stdClass
    {
        $query = [
            'action' => 'issuu.folders.update',
            'folderId' => $folderId,
            'folderName' => $folderName,
            'folderDescription' => $folderDescription,
        ];

        $folder = $this->issuu->getResponse($query);

        return $folder->rsp->_content;
    }

    /**
     * Deleting a folder
     * @see https://developers.issuu.com/managing-your-publications/folders/delete/
     * @param  string $folderIds
     * @return string
     */
    public function delete(string $folderIds): string
    {
        $query = [
            'action' => 'issuu.folders.delete',
            'folderIds' => $folderIds,
        ];

        $folders = $this->issuu->getResponse($query);

        return $folders->rsp->stat;
    }
}
