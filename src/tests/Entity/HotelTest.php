<?php
/**
 * @author: Walid Aqleh <w.aqleh@sportradar.com>
 * Date: 01.09.20
 */

namespace App\Tests\Entity;

use App\Entity\Hotel;
use PHPUnit\Framework\TestCase;


class HotelTest extends TestCase
{
    public function testSettingHotelName()
    {
        $hotel = new Hotel();
        $name = "The Beverly Hills Hotel, Los Angeles";
        $hotel->setName($name);
        $this->assertEquals($name, $hotel->getName());
    }
}