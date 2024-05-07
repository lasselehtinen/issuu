<?php

declare(strict_types=1);

namespace lasselehtinen\Issuu;

use lasselehtinen\Issuu\Exceptions\FileDoesNotExist;
use stdClass;

class Drafts
{
    /** @var Issuu */
    private $issuu;

    public function __construct(Issuu $issuu)
    {
        $this->issuu = $issuu;
    }

    /**
     * Get the list of drafts from Issuu
     * @see https://api.issuu.com/v2/reference/#get-/publications
     * @param  int     $size        Determines the number of publications to return per page.
     * @param  int     $page        Specifies the page number to return.
     * @param  string  $q           A regular expression that is applied to the titles and descriptions of the drafts and returns only the drafts that match the expression
     * @return stdClass
     */
    public function list(
        int $size = 10,
        int $page = 1,
        string $q = '',
    ): stdClass {
        $queryParameters = [
            'size' => $size,
            'page' => $page,
            'q' => $q,
        ];

        return $this->issuu->getResponse(method: 'GET', endpoint: 'drafts', queryParameters: $queryParameters);
    }

    /**
     * Create a new Draft
     * @see https://api.issuu.com/v2/reference/#post-/drafts
     * @param array<mixed>    $body        The data to create the draft with.
     * @return stdClass
     */
    public function create(
        array $body = [],
    ): stdClass {
        return $this->issuu->getResponse(method: 'POST', endpoint: 'drafts', body: $body);
    }

    /**
     * Get Draft by slug
     * @see https://api.issuu.com/v2/reference/#get-/drafts/-slug-
     * @param  string   $slug        The unique identifier of the draft to retrieve. This should be a string that corresponds to the slug of a draft.
     * @return stdClass
     */
    public function getDraftBySlug(
        string $slug,
    ): stdClass {
        return $this->issuu->getResponse(method: 'GET', endpoint: 'drafts/'.$slug);
    }

    /**
     * Get Draft by slug
     * @see https://api.issuu.com/v2/reference/#post-/drafts/-slug-/publish
     * @param  string       $slug        The unique identifier of the draft to publish. This should be a string that corresponds to the slug of a draft.
     * @param  array<mixed> $body        The data to publish the draft with.
     * @return stdClass
     */
    public function publishDraftBySlug(
        string $slug,
        array $body,
    ): stdClass {
        return $this->issuu->getResponse(method: 'POST', endpoint: 'drafts/'.$slug.'/publish', body: $body);
    }

    /**
     * Delete a Draft by slug
     * @see https://api.issuu.com/v2/reference/#delete-/drafts/-slug-
     * @param  string   $slug        The unique identifier of the draft to delete. This should be a string that corresponds to the slug of a draft.
     * @return null
     */
    public function deleteDraftBySlug(
        string $slug,
    ): null {
        $this->issuu->getResponse(method: 'DELETE', endpoint: 'drafts/'.$slug);
        return null;
    }

    /**
     * Update a Draft by slug
     * @see https://api.issuu.com/v2/reference/#patch-/drafts/-slug-
     * @param  string           $slug       The unique identifier of the draft to update. This should be a string that corresponds to the slug of a draft.
     * @param  array<mixed>     $body        The data to update the draft with.
     * @return stdClass
     */
    public function updateDraftBySlug(
        string $slug,
        array $body = [],
    ): stdClass {
        return $this->issuu->getResponse(method: 'PATCH', endpoint: 'drafts/'.$slug, body: $body);
    }
}
