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
    public function list(int $size = 10, int $page = 1, string $state = 'ALL', string $q = ''): stdClass
    {
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
    public function getPublicationBySlug(string $slug): stdClass
    {
        return $this->issuu->getResponse(method: 'GET', endpoint: 'publications/'.$slug);
    }

    /**
     * Delete a Publication by slug
     * @see https://api.issuu.com/v2/reference/#delete-/publications/-slug-
     * @param  string   $slug        The unique identifier of the publication to delete. This should be a string that corresponds to the slug of a publication.
     * @return null
     */
    public function deletePublicationBySlug(string $slug): null
    {
        $this->issuu->getResponse(method: 'DELETE', endpoint: 'publications/'.$slug);
        return null;
    }

    /**
     * Get Publication Assets by slug
     * @see https://api.issuu.com/v2/reference/#get-/publications/-slug-/assets
     * @param  string       $slug        The unique identifier of the publication to retrieve. This should be a string that corresponds to the slug of a publication.
     * @param  string       $assetType   Specifies the asset type.
     * @param  array        $args {
     *     Optional. The settings used to specify the assets request.
     * 
     *     @type int   $size               Determines the number of publications to return per page. Default 10. Allowed 10, 20, 40, 60, 100.
     *     @type int   $page               Specifies the page number to return. Default 1.
     *     @type float $documentPageNumber Specifies the publication page number to return the assets. If not provided, all assets will be returned from all pages.
     * @return stdClass
     */
    public function getPublicationAssetsBySlug(string $slug, string $assetType, array $args = []): stdClass {
        $args = array_merge([
            'size' => 10,
            'page' => 1,
            'documentPageNumber' => 0.0,
        ], $args);
        $args['assetType'] = $assetType;
        if (!$args['documentPageNumber']) unset($args['documentPageNumber']);

        return $this->issuu->getResponse(method: 'GET', endpoint: "publications/{$slug}/assets", queryParameters: $args);
    }

    /**
     * Get Publication Embed by slug
     * @see https://api.issuu.com/v2/reference/#get-/publications/-slug-/embed
     * @param  string       $slug        The unique identifier of the publication to retrieve. This should be a string that corresponds to the slug of a publication.
     * @param  array        $args {
     *     Optional. The settings used to customize the embed code.
     * 
     *     @type bool   $responsive            Determines if the embed code should be responsive. Default true.
     *     @type string $width                 The width of the embed code. Default '100%'.
     *     @type string $height                The height of the embed code. Default '100%'.
     *     @type bool   hideIssuuLogo          Determines if the Issuu logo should be hidden. Default false.
     *     @type bool   hideShareButton        Determines if the share button should be hidden. Default false.
     *     @type bool   showOtherPulications   Determines if other publications should be shown. Default false.
     *     @type string bgColor                Determines the background color (hex) of the embed. Default ''.
     *     @type string fullScreenShareBgColor Determines the background color (hex) of the fullscreen share. Default ''.
     * }
     * @return stdClass
     */
    public function getPublicationEmbedBySlug(string $slug, array $args = []): stdClass {
        $args = array_merge([
            'responsive' => true,
            'width' => '100%',
            'height' => '100%',
            'hideIssuuLogo' => false,
            'hideShareButton' => false,
            'showOtherPublications' => false,
            'bgColor' => '',
            'fullScreenShareBgColor' => '',
        ], $args);

        return $this->issuu->getResponse(method: 'GET', endpoint: "publications/{$slug}/embed", queryParameters: $args);
    }
}
