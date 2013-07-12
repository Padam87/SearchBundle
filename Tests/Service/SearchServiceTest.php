<?php

namespace Padam87\SearchBundle\Test\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Filter\ArrayFilter;

class SearchServiceTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testGetService()
    {
        $service = self::$kernel->getContainer()->get('search');

        $this->assertInstanceOf('Padam87\SearchBundle\Service\SearchService', $service);
    }

    public function testGetFactory()
    {
        $service = self::$kernel->getContainer()->get('search');

        $this->assertInstanceOf('Padam87\SearchBundle\Filter\FilterFactory', $service->getFactory());
    }

    public function testCreateFilter()
    {
        $service = self::$kernel->getContainer()->get('search');

        $this->assertInstanceOf('Padam87\SearchBundle\Filter\FilterInterface', $service->createFilter(array(), 'alias'));
    }
}