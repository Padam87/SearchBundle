<?php

namespace Padam87\SearchBundle\Test\Converter;

use Doctrine\Common\Collections\ArrayCollection;
use Padam87\SearchBundle\Filter\Converter\ArrayConverter;
use Padam87\SearchBundle\Filter\Filter;
use Padam87\SearchBundle\Tests\Models\Company;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CollectionConverterTest extends WebTestCase
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var ArrayConverter
     */
    protected $converter;

    public function setUp()
    {
        self::createClient();

        $company1 = new Company();
        $company1->setName('Netlife');

        $company2 = new Company();
        $company2->setHomepage('www.netlife.hu');

        $collection = new ArrayCollection();
        $collection->add($company1);
        $collection->add($company2);

        $this->filter = new Filter($collection, null, 'alias');

        $this->converter = self::$kernel->getContainer()->get('padam87_search.converter.collection');
    }

    public function testToArray()
    {
        $this->assertEquals(2, count($this->converter->toArray($this->filter)));
    }

    public function testToExpr()
    {
        $expr = $this->converter->toExpr($this->filter);

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Orx', $expr);
        $this->assertEquals(2, count($expr->getParts()));
    }

    public function testToParameters()
    {
        $this->assertEquals(2, count($this->converter->toParameters($this->filter)));
    }
}