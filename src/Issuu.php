<?php

declare(strict_types=1);

namespace lasselehtinen\Issuu;

use stdClass;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;
use lasselehtinen\Issuu\Exceptions\PageNotFound;
use lasselehtinen\Issuu\Exceptions\EmbedNotFound;
use lasselehtinen\Issuu\Exceptions\InvalidApiKey;
use lasselehtinen\Issuu\Exceptions\DocumentNotFound;
use lasselehtinen\Issuu\Exceptions\FolderAlreadyExist;
use lasselehtinen\Issuu\Exceptions\InvalidFieldFormat;
use lasselehtinen\Issuu\Exceptions\InvalidTokenException;
use lasselehtinen\Issuu\Exceptions\RequiredFieldIsMissing;
use lasselehtinen\Issuu\Exceptions\DocumentStillConverting;
use lasselehtinen\Issuu\Exceptions\DocumentFailedConversion;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForMonthlyUploads;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForUnlistedPublications;

class Issuu
{
    /** @var string */
    private $apiKey;

    /** @var Client */
    private $client;

    public function __construct(string $apiKey, Client $client = null)
    {
        $this->apiKey = $apiKey;
        $this->client = $client ?: new Client(['base_uri' => 'https://api.issuu.com/v2/']);
    }

    /**
     * Make a request to Issuu API
     *
     * @param string $method
     * @param string $endpoint
     * @param array<mixed> $queryParameters
     * @param array<mixed> $body
     * @return stdClass
     */
    public function getResponse(string $method, string $endpoint, array $queryParameters = [], array $body = []): stdClass
    {
        // Convert booleans to string
        foreach ($queryParameters as $key => $queryParameter) {
            if (is_bool($queryParameter)) {
                $queryParameters[$key] = var_export($queryParameter, true);
            }
        }

        $response = $this->client->request($method, $endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
            'query' => $queryParameters,
            RequestOptions::JSON => $body,
        ]);

        $json = json_decode($response->getBody()->getContents(), false);

        // Some endpoints return empty response, return empty stdClass in those cases
        if ($json instanceof stdClass === false) {
            return new stdClass();
        }

        if ($this->responseHasErrors($json)) {
            $this->throwException($json);
        }

        return $json;
    }

    public function responseHasErrors(stdClass $json): bool
    {
        return isset($json->message);
    }

    public function throwException(stdClass $json): bool
    {
        switch ($json->message) {
            case 'Invalid token':
                throw new InvalidTokenException('The given token is invalid.');
            default:
                throw new Exception($json->message);
        }
    }
}
