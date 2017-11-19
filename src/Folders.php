<?php

namespace lasselehtinen\Issuu;

class Folders extends Issuu
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
     */
    public function add($folderName, $folderDescription = null)
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
     * @param  string  $folderSortBy Reponse parameter to sort the result by. Sorting can only be done on a single parameter. Default is no particular sort order
     * @param  string  $responseParams Comma-separated list of response parameters to be returned.
     * @return object
     */
    public function list(
        $resultOrder = 'asc',
        $startIndex = 0,
        $pageSize = 10,
        $folderSortBy = null,
        $responseParams = null
    ) {
        $query = [
            'action' => 'issuu.folders.list',
            'resultOrder' => $resultOrder,
            'startIndex' => $startIndex,
            'pageSize' => $pageSize,
            'folderSortBy' => $folderSortBy,
            'responseParams' => $responseParams,
        ];

        // Perform query
        $folders = $this->issuu->getResponse($query);

        return $folders->rsp->_content->result;
    }

    /**
     * Update a folder
     * @see https://developers.issuu.com/managing-your-publications/folders/update/
     * @param  string $folderId          The folder to be updated
     * @param  string $folderName        New name of the folder
     * @param  string $folderDescription New description of the folder
     * @return object
     */
    public function update($folderId, $folderName = null, $folderDescription = null)
    {
        $query = [
            'action' => 'issuu.folders.update',
            'folderId' => $folderId,
            'folderName' => $folderName,
            'folderDescription' => $folderDescription,
        ];

        // Perform query
        $folder = $this->issuu->getResponse($query);

        return $folder->rsp->_content;
    }

    /**
     * Deleting a folder
     * @see https://developers.issuu.com/managing-your-publications/folders/delete/
     * @param  string $folderIds
     * @return string
     */
    public function delete($folderIds)
    {
        $query = [
            'action' => 'issuu.folders.delete',
            'folderIds' => $folderIds,
        ];

        // Perform query
        $folders = $this->issuu->getResponse($query);

        return $folders->rsp->stat;
    }
}
