<?php
declare(strict_types=1);

namespace Search\Search\QueryEntry;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 */
class Subquery extends AbstractQueryEntry
{
    /**
     * Query
     *
     * @var \Search\Search\Query\AbstractQuery
     */
    private $_query;

    /**
     * Object constractor
     *
     * @param \Search\Search\Query\AbstractQuery $query
     */
    public function __construct(\Search\Search\Query\AbstractQuery $query)
    {
        $this->_query = $query;
    }

    /**
     * Process modifier ('~')
     *
     * @param mixed $parameter
     * @throws \Search\Search\Exception\QueryParserException
     */
    public function processFuzzyProximityModifier($parameter = null)
    {
        throw new \Search\Search\Exception\QueryParserException(
            '\'~\' sign must follow term or phrase'
        );
    }


    /**
     * Transform entry to a subquery
     *
     * @param string $encoding
     * @return \Search\Search\Query\AbstractQuery
     */
    public function getQuery($encoding)
    {
        $this->_query->setBoost($this->_boost);

        return $this->_query;
    }
}
