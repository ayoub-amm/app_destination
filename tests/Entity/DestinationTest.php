<?php
namespace App\Tests\Entity;

use App\Entity\Destination;
use PHPUnit\Framework\TestCase;

class DestinationTest extends TestCase
{
    public function testSetName()
    {
        $destination = new Destination();
        $destination->setName('Paris');
        $this->assertEquals('Paris', $destination->getName());
    }

    public function testSetDescription()
    {
        $destination = new Destination();
        $destination->setDescription('City of lights.');
        $this->assertEquals('City of lights.', $destination->getDescription());
    }

    public function testSetPrice()
    {
        $destination = new Destination();
        $destination->setPrice('199.99');
        $this->assertEquals('199.99', $destination->getPrice());
    }

    public function testSetDuration()
    {
        $destination = new Destination();
        $destination->setDuration(5);
        $this->assertEquals(5, $destination->getDuration());
    }

    public function testSetImage()
    {
        $destination = new Destination();
        $destination->setImage('paris.jpg');
        $this->assertEquals('paris.jpg', $destination->getImage());
    }
}
