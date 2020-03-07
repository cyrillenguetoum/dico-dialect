<?php
declare(strict_types=1);

namespace Search\Search;

use Search\SearchIndexInterface;
use Search\Document;

class QueryHit
{
    /**
     * Object handle of the index
     * @var \Search\SearchIndexInterface
     */
    protected $_index = null;

    /**
     * Object handle of the document associated with this hit
     * @var \Search\Document
     */
    protected $_document = null;

    /**
     * Unique hit id
     * @var integer
     */
    public $id;

    /**
     * Number of the document in the index
     * @var integer
     */
    public $document_id;

    /**
     * Score of the hit
     * @var float
     */
    public $score;


    /**
     * Constructor - pass object handle of Zend_Search_Lucene_Interface index that produced
     * the hit so the document can be retrieved easily from the hit.
     *
     * @param \Search\SearchIndexInterface $index
     */

    public function __construct(SearchIndexInterface $index)
    {
        $this->_index = $index;
    }

    /**
     * Magic method for checking the existence of a field
     *
     * @param string $offset
     * @return boolean TRUE if the field exists else FALSE
     */
    public function __isset($offset)
    {
        return isset($this->getDocument()->$offset);
    }


    /**
     * Convenience function for getting fields from the document
     * associated with this hit.
     *
     * @param string $offset
     * @return string
     */
    public function __get($offset)
    {
        return $this->getDocument()->getFieldValue($offset);
    }


    /**
     * Return the document object for this hit
     *
     * @return \Search\Document
     */
    public function getDocument()
    {
        if (!$this->_document instanceof Document) {
            $this->_document = $this->_index->getDocument($this->document_id);
        }

        return $this->_document;
    }


    /**
     * Return the index object for this hit
     *
     * @return Search\SearchIndexInterface
     */
    public function getIndex()
    {
        return $this->_index;
    }
}
