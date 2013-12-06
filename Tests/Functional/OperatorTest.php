<?php

namespace Padam87\SearchBundle\Test\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Filter\ArrayFilter;

class OperatorTest extends WebTestCase
{
    public function setUp()
    {
        self::createClient();
    }

    public function testGteOperator()
    {
        $filter = new ArrayFilter(array(
            'numberOfEmployees>=' => 10,
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals(">=", $part->getOperator());
    }

    public function testGtOperator()
    {
        $filter = new ArrayFilter(array(
            'numberOfEmployees>' => 10,
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals(">", $part->getOperator());
    }

    public function testLtOperator()
    {
        $filter = new ArrayFilter(array(
            'numberOfEmployees<' => 10,
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals("<", $part->getOperator());
    }

    public function testLteOperator()
    {
        $filter = new ArrayFilter(array(
            'numberOfEmployees<=' => 10,
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals("<=", $part->getOperator());
    }

    public function testNeqOperator()
    {
        $filter = new ArrayFilter(array(
            'numberOfEmployees!=' => 10,
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals("<>", $part->getOperator());
    }

    public function testLikeOperator()
    {
        $filter = new ArrayFilter(array(
            'email' => '*netlife.hu',
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertEquals("LOWER(c.email) LIKE :c_email", $part);
    }

    public function testIsNullOperator()
    {
        $filter = new ArrayFilter(array(
            'email' => 'NULL',
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertEquals(0, count($qb->getParameters()));
        $this->assertEquals('c.email IS NULL', $part);
    }

    public function testIsNotNullOperator()
    {
        $filter = new ArrayFilter(array(
            'email!=' => 'NULL',
        ), 'c');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertEquals(0, count($qb->getParameters()));
        $this->assertEquals('c.email IS NOT NULL', $part);
    }

    public function testDefaultOperator()
    {
        $filter = new ArrayFilter(array(
            'email' => 'info@netlife.hu',
        ), 'c');

        $filter->setDefaultOperator('email', '!=');

        $filter->setEntityManager(self::$kernel->getContainer()->get('doctrine')->getManager());

        $qb = $filter->createQueryBuilder('Padam87SearchBundleTest:Company');

        $where = $qb->getDQLPart('where');
        $parts = $where->getParts();
        $part = $parts[0];

        $this->assertEquals(1, count($where));
        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Comparison', $part);
        $this->assertEquals("<>", $part->getOperator());
    }
}