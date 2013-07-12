<?php

namespace Padam87\SearchBundle\Test\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Padam87\SearchBundle\Filter\ArrayFilter;

class ArrayFilterTest extends WebTestCase
{
    public function testToArray()
    {
        $filter = new ArrayFilter(array(
            'condition1' => 'value1',
            'condition2' => 'value2',
            'condition3' => null,
        ), 'alias');

        $this->assertEquals(2, count($filter->toArray()));
    }

    public function testToExpr()
    {
        $filter = new ArrayFilter(array(
            'condition1' => 'value1',
            'condition2' => 'value2',
            'condition3' => null,
        ), 'alias');

        $expr = $filter->toExpr();

        $this->assertInstanceOf('Doctrine\ORM\Query\Expr\Andx', $expr);
        $this->assertEquals(2, count($expr->getParts()));
    }

    public function testToParameters()
    {
        $filter = new ArrayFilter(array(
            'condition1' => 'value1',
            'condition2' => 'value2',
            'condition3' => null,
        ), 'alias');

        $this->assertEquals(2, count($filter->toParameters()));
    }
}