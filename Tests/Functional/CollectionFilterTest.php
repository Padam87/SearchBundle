<?php

namespace Padam87\SearchBundle\Test\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Filter\CollectionFilter;
use Padam87\SearchBundle\Tests\Models\Company;
use Doctrine\Common\Collections\ArrayCollection;

class CollectionFilterTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testToArray()
    {
        $company1 = new Company();
        $company1->setName('Netlife');

        $company2 = new Company();
        $company2->setHomepage('www.netlife.hu');

        $collection = new ArrayCollection();
        $collection->add($company1);
        $collection->add($company2);

        $filter = new CollectionFilter($collection, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $this->assertEquals(2, count($filter->toArray()));
    }

    public function testToExpr()
    {
        $company1 = new Company();
        $company1->setName('Netlife');

        $company2 = new Company();
        $company2->setHomepage('www.netlife.hu');

        $collection = new ArrayCollection();
        $collection->add($company1);
        $collection->add($company2);

        $filter = new CollectionFilter($collection, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $expr = $filter->toExpr();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Orx', $expr);
        $this->assertEquals(2, count($expr->getParts()));
    }

    public function testToParameters()
    {
        $company1 = new Company();
        $company1->setName('Netlife');

        $company2 = new Company();
        $company2->setHomepage('www.netlife.hu');

        $collection = new ArrayCollection();
        $collection->add($company1);
        $collection->add($company2);

        $filter = new CollectionFilter($collection, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $this->assertEquals(2, count($filter->toParameters()));
    }
}