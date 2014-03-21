<?php

namespace Padam87\SearchBundle\Test\Repository;

use Padam87\SearchBundle\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Tests\Models\Company;

class SearchableRepositoryTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDiExtraException()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('Padam87SearchBundleTest:Company');

        $this->assertInstanceOf('Padam87\SearchBundle\Entity\Repository\SearchableRepository', $repo);

        $company = new Company();
        $filter = new Filter($company, null, 'c');

        $repo->createSearchQueryBuilder($filter);
    }

    public function testQueryBuilder()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('Padam87SearchBundleTest:Company');
        $repo->setFilterManager(self::$kernel->getContainer()->get('padam87_search.filter.manager'));

        $company = new Company();
        $filter = new Filter($company, null, 'c');
        $qb = $repo->createSearchQueryBuilder($filter);

        $this->assertInstanceOf('Padam87\SearchBundle\Entity\Repository\SearchableRepository', $repo);
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qb);
    }
}