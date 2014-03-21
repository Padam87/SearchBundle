<?php

namespace Padam87\SearchBundle\Test\Filter;

use Padam87\SearchBundle\Filter\EntityFilter;
use Padam87\SearchBundle\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Tests\Models\Company;
use Padam87\SearchBundle\Tests\Models\Project;

class FilterManagerTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();

        $company = new Company();
        $company->setEmail('info@netlife.hu');

        $this->companyFilter = new Filter($company, 'Padam87SearchBundleTest:Company', 'c');

        $project = new Project();
        $project->setName('Test');

        $this->projectFilter = new Filter($project, 'Padam87SearchBundleTest:Project', 'p');

        $this->fm = self::$kernel->getContainer()->get('padam87_search.filter.manager');
    }

    public function testJoinFilters()
    {
        $qb = $this->fm->joinToQueryBuilder(
            $this->projectFilter,
            $this->fm->createQueryBuilder($this->companyFilter),
            'projects'
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
        $qb = $this->fm->joinToQueryBuilder(
            $this->projectFilter,
            $this->fm->createQueryBuilder($this->companyFilter),
            'projects',
            'left'
        );

        $joins = $qb->getDQLPart('join');
        $join = $joins['c'][0];

        $this->assertEquals(2, count($qb->getDQLPart('where')->getParts()));
        $this->assertEquals(1, count($joins));
        $this->assertEquals(1, count($joins['c']));
        $this->assertEquals('LEFT', $join->getJoinType());
    }
}