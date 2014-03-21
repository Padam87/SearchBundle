<?php

namespace Padam87\SearchBundle\Filter\Converter;

use Padam87\SearchBundle\Filter\FilterInterface;

class ConverterManager
{
    /**
     * @var array
     */
    protected $converters = array();

    /**
     * @param ConverterInterface $converter
     */
    public function addConverter(ConverterInterface $converter)
    {
        $this->converters[] = $converter;
    }

    /**
     * @param FilterInterface $filter
     *
     * @return ConverterInterface|null
     */
    public function getConverter(FilterInterface $filter)
    {
        foreach ($this->converters as $converter) {
            if ($converter->supports($filter)) {
                return $converter;
            }
        }

        return null;
    }
}
