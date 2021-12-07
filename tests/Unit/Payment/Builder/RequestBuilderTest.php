<?php

namespace Paygreen\Tests\Unit\Payment\Builder;

use Paygreen\Sdk\Core\PaymentEnvironment;
use Paygreen\Sdk\Payment\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;

final class RequestBuilderTest extends TestCase
{
    public function testCanBeCreatedFromValidParameters()
    {
        $environment = new PaymentEnvironment(
            'public_key',
            'private_key',
            'sandbox',
            2
        );

        $this->assertInstanceOf(
            RequestFactory::class,
            (new RequestFactory($environment))
        );
    }
}