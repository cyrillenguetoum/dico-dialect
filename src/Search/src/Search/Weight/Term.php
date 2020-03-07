<?php
declare(strict_types=1);

namespace Search\Search\Weight;

use Search\SearchIndexInterface;
use Search\Index;
use Search\Search\Query;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 */
class Term extends AbstractWeight
{
    /**
     * IndexReader.
     *
     * @var \Search\SearchIndexInterface
     */
    private $_reader;

    /**
     * Term
     *
     * @var \Search\Index\Term
     */
    private $_term;

    /**
     * The query that this concerns.
     *
     * @var \Search\Search\Query\AbstractQuery
     */
    private $_query;

    /**
     * Score factor
     *
     * @var float
     */
    private $_idf;

    /**
     * Query weight
     *
     * @var float
     */
    private $_queryWeight;


    /**
     * Zend_Search_Lucene_Search_Weight_Term constructor
     * reader - index reader
     *
     * @param \Search\Index\Term                 $term
     * @param \Search\Search\Query\AbstractQuery $query
     * @param \Search\SearchIndexInterface             $reader
     */
    public function __construct(Index\Term            $term,
                                Query\AbstractQuery   $query,
                                SearchIndexInterface $reader)
    {
        $this->_term   = $term;
        $this->_query  = $query;
        $this->_reader = $reader;
    }


    /**
     * The sum of squared weights of contained query clauses.
     *
     * @return float
     */
    public function sumOfSquaredWeights()
    {
        // compute idf
        $this->_idf = $this->_reader->getSimilarity()->idf($this->_term, $this->_reader);

        // compute query weight
        $this->_queryWeight = $this->_idf * $this->_query->getBoost();

        // square it
        return $this->_queryWeight * $this->_queryWeight;
    }


    /**
     * Assigns the query normalization factor to this.
     *
     * @param float $queryNorm
     */
    public function normalize($queryNorm)
    {
        $this->_queryNorm = $queryNorm;

        // normalize query weight
        $this->_queryWeight *= $queryNorm;

        // idf for documents
        $this->_value = $this->_queryWeight * $this->_idf;
    }
}
