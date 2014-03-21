<?php

namespace Padam87\SearchBundle\Test\Converter;

use Padam87\SearchBundle\Filter\Converter\ArrayConverter;
use Padam87\SearchBundle\Filter\Converter\Util\ExprBuilder;
use Padam87\SearchBundle\Filter\Converter\Util\OperatorHandler;
use Padam87\SearchBundle\Filter\Converter\Util\ParameterBuilder;
use Padam87\SearchBundle\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArrayConverterTest extends WebTestCase
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
        $this->filter = new Filter(array(
            'condition1' => 'value1',
            'condition2' => 'value2',
            'condition3' => null,
        ), null, 'alias');

        $this->converter = new ArrayConverter(new ParameterBuilder(), new ExprBuilder(), new OperatorHandler());
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
}