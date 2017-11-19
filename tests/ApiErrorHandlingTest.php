<?php
namespace lasselehtinen\Issuu\Test;

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
use lasselehtinen\Issuu\Issuu;
use Tests\TestCase;

class ApiErrorHandlingTest extends TestCase
{
    /**
     * Test that invalid API key throws an exception
     * @return void
     */
    public function testUsingInvalidApiKeyThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"010","message":"Invalid API key"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(InvalidApiKey::class);
        $issuu->getResponse([]);
    }

    /**
     * Test that missing a required field throws an exception
     * @return void
     */
    public function testMissingRequiredFieldThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"200","message":"Required field is missing"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(RequiredFieldIsMissing::class);
        $issuu->getResponse([]);
    }

    /**
     * Test using invalid format in a field throws an exception
     * @return void
     */
    public function testUsingInvalidFormatInParameterThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"201","message":"Invalid field format"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(InvalidFieldFormat::class);
        $issuu->getResponse([]);
    }

    /**
     * Trying to request a missing document throws an exception
     * @return void
     */
    public function testRequestingNonExistantDocumentThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"300","message":"Document not found"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(DocumentNotFound::class);
        $issuu->getResponse([]);
    }

    /**
     * Trying to request a non-existant page throws an expection
     * @return void
     */
    public function testRequestingNonExistantPageThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"311","message":"Page not found"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(PageNotFound::class);
        $issuu->getResponse([]);
    }

    /**
     * Exceeding allowed quota for unlisted publications throws an exception
     * @return void
     */
    public function testExceedingQuotaForUnlistedPublicationsThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"294","message":"Exceeding allowed amount of unlisted publications"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(ExceededQuotaForUnlistedPublications::class);
        $issuu->getResponse([]);
    }

    /**
     * Exceeding allowed quota for monthly uploades throws an exception
     * @return void
     */
    public function testExceedingQuotaForMonthlyUploadsThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"295","message":"Exceeding allowed amount of monthly uploads"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(ExceededQuotaForMonthlyUploads::class);
        $issuu->getResponse([]);
    }

    /**
     * Trying to access a document that is still being converted
     * @return void
     */
    public function testTryingToAccessDocumentStillBeingConvertedThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"307","message":"Document still converting"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(DocumentStillConverting::class);
        $issuu->getResponse([]);
    }

    /**
     * Document failed conversion
     * @return void
     */
    public function testDocumentThatFailedConversionThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"308","message":"Document failed conversion."}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(DocumentFailedConversion::class);
        $issuu->getResponse([]);
    }

    /**
     * Trying to access non-existant embed throws an exception
     * @return [type] [description]
     */
    public function testRequestingNonExistantEmbedThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"090","message":"Embed not found"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(EmbedNotFound::class);
        $issuu->getResponse([]);
    }

    /**
     * Trying to create a folder that already exists throws an exception
     * @return void
     */
    public function testCreatingAnExistingFolderThrowsAnException()
    {
        $response = '{"rsp":{"_content":{"error":{"code":"261","message":"Folder name exists for user"}},"stat":"fail"}}';
        $issuu = $this->createMockedInstance($response);

        $this->expectException(FolderAlreadyExist::class);
        $issuu->getResponse([]);
    }
}
