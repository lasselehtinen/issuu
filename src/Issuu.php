<?php
declare(strict_types=1);

namespace lasselehtinen\Issuu;

use GuzzleHttp\Client;
use lasselehtinen\Issuu\Exceptions\AuthenticationRequired;
use lasselehtinen\Issuu\Exceptions\DocumentFailedConversion;
use lasselehtinen\Issuu\Exceptions\DocumentNotFound;
use lasselehtinen\Issuu\Exceptions\DocumentStillConverting;
use lasselehtinen\Issuu\Exceptions\RequestThrottled;
use lasselehtinen\Issuu\Exceptions\EmbedNotFound;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForMonthlyUploads;
use lasselehtinen\Issuu\Exceptions\ExceededQuotaForUnlistedPublications;
use lasselehtinen\Issuu\Exceptions\FolderAlreadyExist;
use lasselehtinen\Issuu\Exceptions\InvalidApiKey;
use lasselehtinen\Issuu\Exceptions\InvalidFieldFormat;
use lasselehtinen\Issuu\Exceptions\PageNotFound;
use lasselehtinen\Issuu\Exceptions\RequiredFieldIsMissing;
use stdClass;

class Issuu
{
    /** @var string */
    private $apiSecret;

    /** @var string */
    private $apiKey;

    /** @var Client */
    private $client;

    public function __construct(string $apiSecret, string $apiKey, Client $client = null)
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
    private function getSignature(array $queryParameters): string
    {
        // Sort request parameters alphabetically
        ksort($queryParameters);

        $signature = $this->apiSecret;

        // Concatenate in order your API secret key and request name-value pairs (e.g. SECRETbar2baz3foo1)
        foreach ($queryParameters as $key => $value) {
            $signature .= $key . $value;
        }

        return md5($signature);
    }

    public function getResponse(array $queryParameters): stdClass
    {
        $queryParameters['apiKey'] = $this->apiKey;

        // Force format to JSON
        $queryParameters['format'] = 'json';

        // Remove null/empty parameters
        $queryParameters = array_filter($queryParameters);
        $queryParameters['signature'] = $this->getSignature($queryParameters);
        $response = $this->client->post('http://api.issuu.com/1_0', [
            'query' => $queryParameters,
        ]);
        $json = json_decode($response->getBody()->getContents());

        // Check for errors
        if ($this->responseHasErrors($json)) {
            switch ($this->getErrorCode($json)) {
                case '009':
                    throw new AuthenticationRequired();
                    break;
                case '010':
                    throw new InvalidApiKey();
                    break;
                case '012':
                    throw new RequestThrottled();
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

    public function getResponseHTML(array $queryParameters): string
    {
        $queryParameters['apiKey'] = $this->apiKey;

        // Remove null/empty parameters
        $queryParameters = array_filter($queryParameters);
        $queryParameters['signature'] = $this->getSignature($queryParameters);
        $response = $this->client->post('http://api.issuu.com/1_0', [
            'query' => $queryParameters,
        ]);
        $html = $response->getBody()->getContents();

        // Check for errors
        $json = json_decode($html);
        if ($json && $this->responseHasErrors($json)) {
            switch ($this->getErrorCode($json)) {
                case '009':
                    throw new AuthenticationRequired();
                    break;
                case '010':
                    throw new InvalidApiKey();
                    break;
                case '012':
                    throw new RequestThrottled();
                    break;
                case '200':
                    throw new RequiredFieldIsMissing();
                    break;
                case '201':
                    throw new InvalidFieldFormat();
                    break;
            }
        }

        return $html;
    }

    public function responseHasErrors(stdClass $json): bool
    {
        // If the response was invalid JSON, no worries
        if (!$json) return false;

        return isset($json->rsp->_content->error);
    }

    public function getErrorCode(stdClass $json): string
    {
        // If the response was invalid JSON, no worries
        if (!$json) return false;

        return $json->rsp->_content->error->code;
    }
}
