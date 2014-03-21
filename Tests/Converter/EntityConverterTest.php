<?php

namespace Padam87\SearchBundle\Test\Converter;

use Padam87\SearchBundle\Filter\Converter\ArrayConverter;
use Padam87\SearchBundle\Filter\Converter\EntityConverter;
use Padam87\SearchBundle\Filter\Converter\Util\ExprBuilder;
use Padam87\SearchBundle\Filter\Converter\Util\OperatorHandler;
use Padam87\SearchBundle\Filter\Converter\Util\ParameterBuilder;
use Padam87\SearchBundle\Filter\Filter;
use Padam87\SearchBundle\Tests\Models\Company;
use Padam87\SearchBundle\Tests\Models\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EntityConverterTest extends WebTestCase
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

        $company = new Company();
        $company->setName('Netlife');
        $company->setHomepage('www.netlife.hu');

        $this->filter = new Filter($company, null, 'alias');

        $this->converter = new EntityConverter(
            new ParameterBuilder(), new ExprBuilder(), new OperatorHandler(),
            self::$kernel->getContainer()->get('doctrine')->getManager()
        );
    }

    public function testToArray()
    {
        $this->assertEquals(2, count($this->converter->toArray($this->filter)));
    }

    public function testToExpr()
    {
        $expr = $this->converter->toExpr($this->filter);

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $expr);
        $this->assertEquals(2, count($expr->getParts()));
    }

    public function testToParameters()
    {
        $this->assertEquals(2, count($this->converter->toParameters($this->filter)));
    }

    public function testCollectionValuedAssociation()
    {
        $project = new Project();
        $project->setName('Test');

        $company = new Company();
        $company->addProject($project);

        $filter = new Filter($company, 'Padam87SearchBundleTest:Company', 'c');

        $this->assertEquals(0, count($this->converter->toArray($filter)));

        $fm = self::$kernel->getContainer()->get('padam87_search.filter.manager');

        $qb = $fm->createQueryBuilder($filter);

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

        $filter = new Filter($company, 'Padam87SearchBundleTest:Company', 'c');
        $fm = self::$kernel->getContainer()->get('padam87_search.filter.manager');

        $qb = $fm->createQueryBuilder($filter);
        $where = $qb->getDQLPart('where')->getParts();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Orx', $where[0]);

        $qb = $fm->createQueryBuilder($filter, array(
            'projects' => 'AND'
        ));
        $where = $qb->getDQLPart('where')->getParts();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $where[0]);
    }
}