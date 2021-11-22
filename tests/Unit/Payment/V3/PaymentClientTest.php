<?php

namespace Paygreen\Tests\Unit\Payment\V3;

use Http\Mock\Client;
use Paygreen\Sdk\Core\Environment;
use Paygreen\Sdk\Payment\Model\Customer;
use Paygreen\Sdk\Payment\Model\Order;
use Paygreen\Sdk\Payment\V3\Model\PaymentOrder;
use Paygreen\Sdk\Payment\V3\PaymentClient;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

final class PaymentClientTest extends TestCase
{
    /**
     * @var PaymentClient
     */
    private $client;

    public function setUp()
    {
        $client = new Client();

        $environment = new Environment(
            'public_key',
            'private_key',
            'SANDBOX',
            3
        );

        $logger = new NullLogger();

        $this->client = new PaymentClient($client, $environment, $logger);
    }

    public function testRequestAuthenticate()
    {
        $this->client->authenticate();
        $request = $this->client->getLastRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/auth/authentication/public_key/secret-key', $request->getUri()->getPath());
    }

    public function testRequestCreateBuyer()
    {
        $customer = new Customer();
        $customer->setId(uniqid());
        $customer->setFirstname('John');
        $customer->setLastname('Doe');
        $customer->setEmail('dev-module@paygreen.fr');
        $customer->setCountryCode('FR');

        $this->client->createBuyer($customer);
        $request = $this->client->getLastRequest();
        
        $content = json_decode($request->getBody()->getContents());

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/payment/shops/public_key/buyers', $request->getUri()->getPath());
        $this->assertEquals($customer->getEmail(), $content->email);
        $this->assertEquals($customer->getFirstname(), $content->first_name);
        $this->assertEquals($customer->getLastname(), $content->last_name);
        $this->assertEquals($customer->getId(), $content->reference);
        $this->assertEquals($customer->getCountryCode(), $content->country);
    }

    public function testRequestGetBuyer()
    {
        $customer = new Customer();
        $customer->setReference("buyerReference");

        $this->client->getBuyer($customer);
        $request = $this->client->getLastRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/payment/shops/public_key/buyers/buyerReference', $request->getUri()->getPath());
    }

    public function testRequestUpdateBuyer()
    {
        $customer = new Customer();
        $customer->setId("buyerId");
        $customer->setReference("buyerReference");
        $customer->setFirstname('John');
        $customer->setLastname('Doe');
        $customer->setEmail('dev-module@paygreen.fr');
        $customer->setCountryCode('FR');

        $this->client->updateBuyer($customer);
        $request = $this->client->getLastRequest();

        $content = json_decode($request->getBody()->getContents());

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/payment/shops/public_key/buyers/buyerReference', $request->getUri()->getPath());
        $this->assertEquals($customer->getEmail(), $content->email);
        $this->assertEquals($customer->getFirstname(), $content->first_name);
        $this->assertEquals($customer->getLastname(), $content->last_name);
        $this->assertEquals($customer->getId(), $content->reference);
        $this->assertEquals($customer->getCountryCode(), $content->country);
    }

    public function testRequestCreateOrder()
    {
        $customer = new Customer();
        $customer->setId("buyerId");
        $customer->setFirstname('John');
        $customer->setLastname('Doe');
        $customer->setEmail('dev-module@paygreen.fr');
        $customer->setCountryCode('FR');

        $order = new Order();
        $order->setCustomer($customer);
        $order->setReference('SDK-ORDER-123');
        $order->setAmount(1000);
        $order->setCurrency('EUR');

        $paymentOrder = new PaymentOrder();
        $paymentOrder->setPaymentMode("instant");
        $paymentOrder->setAutoCapture(true);
        $paymentOrder->setIntegrationMode("hosted_fields");
        $paymentOrder->setOrder($order);
        
        $this->client->createOrder($paymentOrder);
        $request = $this->client->getLastRequest();
        
        $content = json_decode($request->getBody()->getContents());

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/payment/payment-orders', $request->getUri()->getPath());
        $this->assertEquals($customer->getEmail(), $content->buyer->email);
        $this->assertEquals($customer->getFirstname(), $content->buyer->firstName);
        $this->assertEquals($customer->getLastname(), $content->buyer->lastName);
        $this->assertEquals($customer->getId(), $content->buyer->reference);
        $this->assertEquals($customer->getCountryCode(), $content->buyer->country);

        $this->assertEquals($order->getReference(), $content->reference);
        $this->assertEquals($order->getAmount(), $content->amount);
        $this->assertEquals($order->getCurrency(), $content->currency);

        $this->assertEquals($paymentOrder->getPaymentMode(), $content->paymentMode);
        $this->assertEquals($paymentOrder->getAutoCapture(), $content->auto_capture);
        $this->assertEquals($paymentOrder->getIntegrationMode(), $content->integration_mode);
    }
}