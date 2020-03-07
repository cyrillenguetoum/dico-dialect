<?php
declare(strict_types=1);

namespace Search\Search\Weight;

use Search\SearchIndexInterface;
use Search\Search\Query;

class Phrase extends AbstractWeight
{
    /**
     * IndexReader.
     *
     * @var \Search\SearchIndexInterface
     */
    private $_reader;

    /**
     * The query that this concerns.
     *
     * @var \Search\Search\Query\Phrase
     */
    private $_query;

    /**
     * Score factor
     *
     * @var float
     */
    private $_idf;

    /**
     * Zend_Search_Lucene_Search_Weight_Phrase constructor
     *
     * @param \Search\Search\Query\Phrase $query
     * @param \Search\SearchIndexInterface      $reader
     */
    public function __construct(Query\Phrase $query, SearchIndexInterface $reader)
    {
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
        $this->_idf = $this->_reader->getSimilarity()->idf($this->_query->getTerms(), $this->_reader);

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
