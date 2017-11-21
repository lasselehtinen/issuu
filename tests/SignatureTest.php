<?php
namespace lasselehtinen\Issuu\Test;

use lasselehtinen\Issuu\Issuu;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SignatureTest extends TestCase
{
    /**
     * Test signature creation
     * @return void
     */
    public function testSignatureCreation()
    {
        // Create a new instance based on the example in http://developers.issuu.com/signing-requests/
        $issuu = new Issuu('13e3an36eaxjy8nenuepab05yc7j7w5g', 'qyy6ls1qv15uh9xwwlvk853u2uvpfka7');

        $parameters = [
            'action' => 'issuu.documents.list',
            'apiKey' => 'qyy6ls1qv15uh9xwwlvk853u2uvpfka7',
            'access' => 'public',
            'responseParams' => 'title,description',
            'format' => 'json',
        ];

        // Create new ReflectionClass to test private method
        $reflection = new ReflectionClass(get_class($issuu));
        $method = $reflection->getMethod('getSignature');
        $method->setAccessible(true);
        $signature = $method->invokeArgs($issuu, [$parameters]);

        $this->assertSame('7431d31140cf412ab5caa73586d6324a', $signature);
    }
}
