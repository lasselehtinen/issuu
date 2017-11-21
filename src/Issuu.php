<?php

namespace lasselehtinen\Issuu;

use GuzzleHttp\Client;
use lasselehtinen\Issuu\Exceptions\DocumentFailedConversion;
use lasselehtinen\Issuu\Exceptions\DocumentNotFound;
use lasselehtinen\Issuu\Exceptions\DocumentStillConverting;
use lasselehtinen\Issuu\Exceptions\EmbedNotFound;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForMonthlyUploads;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForUnlistedPublications;
use lasselehtinen\Issuu\Exceptions\FolderAlreadyExist;
use lasselehtinen\Issuu\Exceptions\InvalidApiKey;
use lasselehtinen\Issuu\Exceptions\InvalidFieldFormat;
use lasselehtinen\Issuu\Exceptions\PageNotFound;
use lasselehtinen\Issuu\Exceptions\RequiredFieldIsMissing;

class Issuu
{
    /**
     * Issuu ApiSecret
     * @var string
     */
    private $apiSecret;

    /**
     * Issuu ApiKey
     * @var string
     */
    private $apiKey;

    /**
     * Guzzle instance
     * @var Client
     */
    private $client;

    public function __construct($apiSecret, $apiKey, Client $client = null)
    {
        $this->apiSecret = $apiSecret;
        $this->apiKey = $apiKey;
        $this->client = $client ?: new Client();
    }

    /**
     * Create a Issuu signature for the API requests
     *
     * @see http://developers.issuu.com/signing-requests/
     * @param  array $queryParameters
     * @return string
     */
    private function getSignature($queryParameters)
    {
        // Sort request parameters alphabetically
        ksort($queryParameters);

        // Concatenate in order your API secret key and request name-value pairs (e.g. SECRETbar2baz3foo1)
        $signature = $this->apiSecret;

        foreach ($queryParameters as $key => $value) {
            $signature .= $key . $value;
        }

        return md5($signature);
    }

    public function getResponse($queryParameters)
    {
        // Add Issuu API key
        $queryParameters['apiKey'] = $this->apiKey;

        // Force format to JSON
        $queryParameters['format'] = 'json';

        // Remove null/empty parameters
        $queryParameters = array_filter($queryParameters);

        // Add signature
        $queryParameters['signature'] = $this->getSignature($queryParameters);

        // Get response
        $response = $this->client->post('http://api.issuu.com/1_0', [
            'query' => $queryParameters,
        ]);

        $json = json_decode($response->getBody()->getContents());

        // Check for errors
        if ($this->responseHasErrors($json)) {
            switch ($this->getErrorCode($json)) {
                case '010':
                    throw new InvalidApiKey();
                    break;
                case '200':
                    throw new RequiredFieldIsMissing();
                    break;
                case '201':
                    throw new InvalidFieldFormat();
                    break;
                case '300':
                    throw new DocumentNotFound();
                    break;
                case '311':
                    throw new PageNotFound();
                    break;
                case '294':
                    throw new ExceededQuotaForUnlistedPublications();
                    break;
                case '295':
                    throw new ExceededQuotaForMonthlyUploads();
                    break;
                case '307':
                    throw new DocumentStillConverting();
                    break;
                case '308':
                    throw new DocumentFailedConversion();
                    break;
                case '090':
                    throw new EmbedNotFound();
                    break;
                case '261':
                    throw new FolderAlreadyExist();
                    break;
            }
        }

        return $json;
    }

    public function responseHasErrors($json)
    {
        return isset($json->rsp->_content->error);
    }

    public function getErrorCode($json)
    {
        return $json->rsp->_content->error->code;
    }
}
