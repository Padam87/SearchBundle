<?php

namespace Padam87\SearchBundle\Test\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Tests\Models\Company;

class SearchableRepositoryTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testDiExtraException()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('Padam87SearchBundleTest:Company');

        $company = new Company();

        $this->assertInstanceOf('Padam87\SearchBundle\Entity\Repository\SearchableRepository', $repo);

        try {
            $repo->createSearchQueryBuilder($company, 'c');
        } catch(\Exception $e) {
            // Throws an exception every time since JMSDiExtra is not required by default
            $this->assertInstanceOf('\InvalidArgumentException', $e);
        }
    }

    public function testQueryBuilder()
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();
        $service = self::$kernel->getContainer()->get('search');
        $repo = $em->getRepository('Padam87SearchBundleTest:Company');
        $repo->setSearchService($service);

        $company = new Company();
        $qb = $repo->createSearchQueryBuilder($company, 'c');

        $this->assertInstanceOf('Padam87\SearchBundle\Entity\Repository\SearchableRepository', $repo);
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $qb);
    }
}