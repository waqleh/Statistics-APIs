<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetHotelAvgReviewScore()
    {

        $client = static::createClient();
        $client->request('GET', '/api/getAvgScore/150/2019-01-18/2019-01-18');
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent(), 'asdasddsaasd');
    }
}