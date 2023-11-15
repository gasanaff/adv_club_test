<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TravelTest extends WebTestCase
{
    public function testInvalidBirthDate()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => 'some string',
            'travel_date' => '2021-01-01',
            'base_cost' => 2
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid birth date"}', $client->getResponse()->getContent());
    }

    public function testInvalidTravelDate()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => '2020-01-01',
            'travel_date' => 'some string',
            'base_cost' => 2
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid travel date"}', $client->getResponse()->getContent());
    }

    public function testInvalidBaseCost()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => '2020-01-01',
            'travel_date' => '2021-01-01',
            'base_cost' => 'some string'
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid base cost"}', $client->getResponse()->getContent());
    }

    public function testForBirthDateGreaterThanTravelDate()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => '2021-01-01',
            'travel_date' => '2020-01-01',
            'base_cost' => 2
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid birth date"}', $client->getResponse()->getContent());
    }

    public function testForYoungerThan3Years()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => date('Y-m-d', strtotime('-2 years', strtotime('2021-01-01'))),
            'travel_date' => '2021-01-01',
            'base_cost' => 100
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"cost":20}', $client->getResponse()->getContent());
    }

    public function testForOlderThan6AndYoungerThan12Years()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => date('Y-m-d', strtotime('-7 years', strtotime('2021-01-01'))),
            'travel_date' => '2021-01-01',
            'base_cost' => 100
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"cost":70}', $client->getResponse()->getContent());
    }

    public function testForOlderThan6AndYoungerThan12Years4500Limit()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => date('Y-m-d', strtotime('-7 years', strtotime('2021-01-01'))),
            'travel_date' => '2021-01-01',
            'base_cost' => 10000
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"cost":4500}', $client->getResponse()->getContent());
    }

    public function testForOlderThan12AndYoungerThan18Years()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => date('Y-m-d', strtotime('-13 years', strtotime('2021-01-01'))),
            'travel_date' => '2021-01-01',
            'base_cost' => 100
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"cost":90}', $client->getResponse()->getContent());
    }

    public function testForOlderThan18Years()
    {
        $client = static::createClient();
        $client->request('POST', '/api/travel_cost', [], [], [], json_encode([
            'birth_date' => date('Y-m-d', strtotime('-19 years', strtotime('2021-01-01'))),
            'travel_date' => '2021-01-01',
            'base_cost' => 100
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"cost":100}', $client->getResponse()->getContent());
    }
}