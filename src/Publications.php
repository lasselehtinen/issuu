<?php

declare(strict_types=1);

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use stdClass;

class Publications
{
    /** @var Issuu */
    private $issuu;

    public function __construct(Issuu $issuu)
    {
        $this->issuu = $issuu;
    }

    /**
     * Get the list of publications from Issuu
     * @see https://api.issuu.com/v2/reference/#get-/publications
     * @param  int     $size        Determines the number of publications to return per page.
     * @param  int     $page        Specifies the page number to return.
     * @param  string  $state       Filters the publications to return based on their state. Allowed: ALL ┃ PUBLISHED ┃ SCHEDULED ┃ UNLISTED
     * @param  string  $q           A regular expression that is applied to the titles and descriptions of the publications and returns only the publications that match the expression.
     * @return stdClass
     */
    public function list(
        int $size = 10,
        int $page = 1,
        string $state = 'ALL',
        string $q = '',
    ): stdClass {
        $queryParameters = [
            'size' => $size,
            'page' => $page,
            'state' => $state,
            'q' => $q,
        ];

        return $this->issuu->getResponse(method: 'GET', endpoint: 'publications', queryParameters: $queryParameters);
    }

    /**
     * Get Publication by slug
     * @see https://api.issuu.com/v2/reference/#get-/publications/-slug-
     * @param  string   $slug           The unique identifier of the publication to retrieve. This should be a string that corresponds to the slug of a publication.
     * @return stdClass
     */
    public function getPublicationBySlug(
        string $slug,
    ): stdClass {
        return $this->issuu->getResponse(method: 'GET', endpoint: 'publications/'.$slug);
    }

    /**
     * Delete a Publication by slug
     * @see https://api.issuu.com/v2/reference/#delete-/publications/-slug-
     * @param  string   $slug        The unique identifier of the publication to delete. This should be a string that corresponds to the slug of a publication.
     * @return null
     */
    public function deletePublicationBySlug(
        string $slug,
    ): null {
        $this->issuu->getResponse(method: 'DELETE', endpoint: 'publications/'.$slug);
        return null;
    }
}
