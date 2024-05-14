<?php

declare(strict_types=1);

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use stdClass;

class Stacks
{
    /** @var Issuu */
    private $issuu;

    public function __construct(Issuu $issuu)
    {
        $this->issuu = $issuu;
    }

    /**
     * Get the list of Stacks from Issuu
     * @see https://api.issuu.com/v2/reference/#get-/stacks
     * @param  int     $size            Determines the number of stacks to return per page.
     * @param  int     $page            Specifies the page number to return.
     * @param  bool    $showUnlisted    Include unlisted stacks in the list of stacks.
     * @return stdClass
     */
    public function list(int $size = 10, int $page = 1, bool $showUnlisted = false): stdClass
    {
        $queryParameters = [
            'size' => $size,
            'page' => $page,
            'showUnlisted' => $showUnlisted,
        ];

        return $this->issuu->getResponse(method: 'GET', endpoint: 'stacks', queryParameters: $queryParameters);
    }

    /**
     * Create a new Stack
     * @see https://api.issuu.com/v2/reference/#post-/stacks
     * @param array<mixed>    $body        Stack data to create.
     * @return stdClass
     */
    public function create(array $body = []): stdClass
    {
        return $this->issuu->getResponse(method: 'POST', endpoint: 'stacks', body: $body);
    }

    /**
     * Get Stack data by ID
     * @see https://api.issuu.com/v2/reference/#get-/stacks/-stackId-
     * @param string    $id         The unique identifier of the stack to retrieve.
     * @return stdClass
     */
    public function getStackDataById(string $id): stdClass
    {
        return $this->issuu->getResponse(method: 'GET', endpoint: 'stacks/'.$id);
    }

    /**
     * Delete a Stack by ID
     * @see https://api.issuu.com/v2/reference/#delete-/stacks/-stackId-
     * @param string    $id         The unique identifier of the stack to delete.
     * @return stdClass
     */
    public function deleteStackById(string $id): stdClass
    {
        return $this->issuu->getResponse(method: 'DELETE', endpoint: 'stacks/'.$id);
    }

    /**
     * Get Stack Items slug
     * @see https://api.issuu.com/v2/reference/#get-/stacks/-stackId-/items
     * @param  string   $id         The unique identifier of the stack to retrieve.
     * @param  int      $size            Determines the number of stacks to return per page.
     * @param  int      $page            Specifies the page number to return.
     * @param  bool     $includeUnlisted    Include unlisted stacks in the list of stacks.
     * @return stdClass
     */
    public function getStackItemsSlug(string $id, bool $includeUnlisted = true, int $size = 10, int $page = 1): stdClass
    {
        return $this->issuu->getResponse(method: 'GET', endpoint: 'stacks/'.$id.'/items');
    }
}
