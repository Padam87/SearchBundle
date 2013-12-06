<?php

namespace Padam87\SearchBundle\Test\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Filter\EntityFilter;
use Padam87\SearchBundle\Tests\Models\Company;
use Padam87\SearchBundle\Tests\Models\Project;

class EntityFilterTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testToArray()
    {
        $company = new Company();
        $company->setName('Netlife');
        $company->setHomepage('www.netlife.hu');

        $filter = new EntityFilter($company, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $this->assertEquals(2, count($filter->toArray()));
    }

    public function testToExpr()
    {
        $company = new Company();
        $company->setName('Netlife');
        $company->setHomepage('www.netlife.hu');

        $filter = new EntityFilter($company, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $expr = $filter->toExpr();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $expr);
        $this->assertEquals(2, count($expr->getParts()));
    }

    public function testToParameters()
    {
        $company = new Company();
        $company->setName('Netlife');
        $company->setHomepage('www.netlife.hu');

        $filter = new EntityFilter($company, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $this->assertEquals(2, count($filter->toParameters()));
    }

    public function testCollectionValuedAssociation()
    {
        $project = new Project();
        $project->setName('Test');

        $company = new Company();
        $company->addProject($project);

        $filter = new EntityFilter($company, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $this->assertEquals(0, count($filter->toArray()));

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $joins = $qb->getDQLPart('join');

        $this->assertEquals(1, count($joins));
        $this->assertEquals(1, count($joins['c']));
    }

    public function testCollectionHandling()
    {
        $project1 = new Project();
        $project1->setName('Test1');

        $project2 = new Project();
        $project2->setName('Test2');

        $company = new Company();
        $company->addProject($project1);
        $company->addProject($project2);

        $filter = new EntityFilter($company, 'c');
        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');
        $where = $qb->getDQLPart('where')->getParts();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Orx', $where[0]);

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company', array(
            'projects' => 'AND'
        ));
        $where = $qb->getDQLPart('where')->getParts();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $where[0]);
    }
}