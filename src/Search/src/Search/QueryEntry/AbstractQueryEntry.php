<?php
declare(strict_types=1);

namespace Search\Search\QueryEntry;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 */
abstract class AbstractQueryEntry
{
    /**
     * Query entry boost factor
     *
     * @var float
     */
    protected $_boost = 1.0;


    /**
     * Process modifier ('~')
     *
     * @param mixed $parameter
     */
    abstract public function processFuzzyProximityModifier($parameter = null);


    /**
     * Transform entry to a subquery
     *
     * @param string $encoding
     * @return \Search\Search\Query\AbstractQuery
     */
    abstract public function getQuery($encoding);

    /**
     * Boost query entry
     *
     * @param float $boostFactor
     */
    public function boost($boostFactor)
    {
        $this->_boost *= $boostFactor;
    }
}
