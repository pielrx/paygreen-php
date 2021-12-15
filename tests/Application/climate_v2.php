<?php

use Http\Client\Curl\Client;
use Paygreen\Sdk\Climate\V2\Model\DeliveryData;
use Paygreen\Sdk\Climate\V2\Model\Address;

$curl = new Client();

$environment = new \Paygreen\Sdk\Climate\V2\Environment(
    getenv('PG_CLIMATE_CLIENT_ID'),
    getenv('PG_CLIMATE_API_SERVER'),
    getenv('PG_CLIMATE_API_VERSION')
);

$client = new \Paygreen\Sdk\Climate\V2\Client($curl, $environment);

$response = $client->login('moduleTree', 'moduleTree', 'moduleTree');
$responseData = json_decode($response->getBody()->getContents());
dump($responseData);

$client->setBearer($responseData->access_token);

$response = $client->refresh('moduleTree', $responseData->refresh_token);
$responseData = json_decode($response->getBody()->getContents());
dump($responseData);

$client->setBearer($responseData->access_token);

// $response = $client->getAccountInfos('moduleTree');
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);
// 
// $response = $client->getUserInfos('moduleTree', 'moduleTree');
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

// $response = $client->createEmptyFootprint('my-footprint-id');
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

// $response = $client->getFootprint('my-footprint-id', true);
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

// $response = $client->closeFootprint('my-footprint-id', 'CLOSED');
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

// $webBrowsingData = new WebBrowsingData();
// $webBrowsingData->setUserAgent('Application:my-application/1.0.0 sdk:1.0.0 php:5.6;');
// $webBrowsingData->setPageCount(85);
// $webBrowsingData->setImageCount(15);
// $webBrowsingData->setDevice('Laptop');
// $webBrowsingData->setBrowser('Firefox');
// $webBrowsingData->setTime(4789);
// $webBrowsingData->setExternalId('my-external-id');
// 
// $response = $client->addWebBrowsingData('my-footprint-id', $webBrowsingData);
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

// $shippedFrom = new Address();
// $shippedFrom->setAddress('1 rue de Paris');
// $shippedFrom->setZipCode('75000');
// $shippedFrom->setCity('Paris');
// $shippedFrom->setCountry('France');

// $shippedTo = new Address();
// $shippedTo->setAddress('1 rue de Paris');
// $shippedTo->setZipCode('75000');
// $shippedTo->setCity('Paris');
// $shippedTo->setCountry('France');

// $deliveryData = new DeliveryData();
// $deliveryData->setTotalWeightInKg(45.5);
// $deliveryData->setShippedFrom($shippedFrom);
// $deliveryData->setShippedTo($shippedTo);
// $deliveryData->setTransportationExternalId('my-transportation-external-id');
// $deliveryData->setDeliveryService('Colissimo');

// $response = $client->addDeliveryData('my-footprint-id', $deliveryData);
// $responseData = json_decode($response->getBody()->getContents());
// dump($responseData);

$response = $client->addProductData(
    'my-footprint-id',
    'AZ1234',
    1
);

$responseData = json_decode($response->getBody()->getContents());
dump($responseData);
