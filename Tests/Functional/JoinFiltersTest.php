<?php

namespace Padam87\SearchBundle\Test\Functional;

use Padam87\SearchBundle\Filter\EntityFilter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Tests\Models\Company;
use Padam87\SearchBundle\Tests\Models\Project;

class JoinFiltersTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testJoinFilters()
    {
        $company = new Company();
        $company->setEmail('info@netlife.hu');
        $companyFilter = new EntityFilter($company, 'c');
        $companyFilter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $project = new Project();
        $project->setName('Test');
        $projectFilter = new EntityFilter($project, 'p');
        $projectFilter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $projectFilter->applyToQueryBuilder(
            $companyFilter->createQueryBuilder('Padam87SearchBundleTest:Company'), 'projects'
        );

        $joins = $qb->getDQLPart('join');
        $join = $joins['c'][0];

        $this->assertEquals(2, count($qb->getDQLPart('where')->getParts()));
        $this->assertEquals(1, count($joins));
        $this->assertEquals(1, count($joins['c']));
        $this->assertEquals('INNER', $join->getJoinType());
    }

    public function testLeftJoinFilters()
    {
        $company = new Company();
        $company->setEmail('info@netlife.hu');
        $companyFilter = new EntityFilter($company, 'c');
        $companyFilter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $project = new Project();
        $project->setName('Test');
        $projectFilter = new EntityFilter($project, 'p');
        $projectFilter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $projectFilter->applyToQueryBuilder(
            $companyFilter->createQueryBuilder('Padam87SearchBundleTest:Company'), 'projects', 'left'
        );

        $joins = $qb->getDQLPart('join');
        $join = $joins['c'][0];

        $this->assertEquals(2, count($qb->getDQLPart('where')->getParts()));
        $this->assertEquals(1, count($joins));
        $this->assertEquals(1, count($joins['c']));
        $this->assertEquals('LEFT', $join->getJoinType());
    }
}