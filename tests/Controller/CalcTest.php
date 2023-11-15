<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalcTest extends WebTestCase
{
    public function testInvalidOperands()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '+',
            'operand1' => '2.2.',
            'operand2' => 2
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid operands"}', $client->getResponse()->getContent());
    }

    public function testInvalidOperator()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => ')',
            'operand1' => 1,
            'operand2' => 2
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Invalid operator"}', $client->getResponse()->getContent());
    }

    public function testDivisionByZero()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '/',
            'operand1' => 1,
            'operand2' => 0
        ]));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"error":"Division by zero"}', $client->getResponse()->getContent());
    }

    public function testValidPlus()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '+',
            'operand1' => 1,
            'operand2' => 2
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"result":3}', $client->getResponse()->getContent());
    }

    public function testValidMinus()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '-',
            'operand1' => 1,
            'operand2' => 2
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"result":-1}', $client->getResponse()->getContent());
    }

    public function testValidMultiply()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '*',
            'operand1' => 1.7,
            'operand2' => 2
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"result":3.4}', $client->getResponse()->getContent());
    }

    public function testValidDivide()
    {
        $client = static::createClient();
        $client->request('POST', '/api/calc', [], [], [], json_encode([
            'operator' => '/',
            'operand1' => 1,
            'operand2' => 2
        ]));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"result":0.5}', $client->getResponse()->getContent());
    }
}