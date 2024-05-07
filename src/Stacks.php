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
    public function list(
        int $size = 10,
        int $page = 1,
        bool $showUnlisted = false,
    ): stdClass {
        $queryParameters = [
            'size' => $size,
            'page' => $page,
            'showUnlisted' => $showUnlisted,
        ];

        return $this->issuu->getResponse(method: 'GET', endpoint: 'stacks', queryParameters: $queryParameters);
    }
}
