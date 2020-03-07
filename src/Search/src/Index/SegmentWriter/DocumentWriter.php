<?php
declare(strict_types=1);

namespace Search\Index\SegmentWriter;

use Search;
use Search\Analysis\Analyzer;
use Search\Document;
use Search\Exception as LuceneException;
use Search\Index;
use Search\Search\Similarity\AbstractSimilarity;
use Search\Storage\Directory;

/**
 * @category   Zend
 * @package    Search
 * @subpackage Index
 */
class DocumentWriter extends AbstractSegmentWriter
{
    /**
     * Term Dictionary
     * Array of the Search_Index_Term objects
     * Corresponding Search_Index_TermInfo object stored in the $_termDictionaryInfos
     *
     * @var array
     */
    protected $_termDictionary;

    /**
     * Documents, which contain the term
     *
     * @var array
     */
    protected $_termDocs;

    /**
     * Object constructor.
     *
     * @param Directory\DirectoryInterface $directory
     * @param string $name
     */
    public function __construct(Directory\DirectoryInterface $directory, $name)
    {
        parent::__construct($directory, $name);

        $this->_termDocs       = array();
        $this->_termDictionary = array();
    }


    /**
     * Adds a document to this segment.
     *
     * @param Search\Document $document
     * @throws LuceneException\UnsupportedMethodCallException
     */
    public function addDocument(Document $document)
    {
        $storedFields = [];
        $docNorms     = [];
        $similarity   = AbstractSimilarity::getDefault();

        foreach ($document->getFieldNames() as $fieldName) {
            $field = $document->getField($fieldName);

            if ($field->storeTermVector) {
                /**
                 * @todo term vector storing support
                 */
                throw new LuceneException\UnsupportedMethodCallException('Store term vector functionality is not supported yet.');
            }

            if ($field->isIndexed) {
                if ($field->isTokenized) {
                    $analyzer = Analyzer\Analyzer::getDefault();
                    $analyzer->setInput($field->value, $field->encoding);

                    $position     = 0;
                    $tokenCounter = 0;
                    while (($token = $analyzer->nextToken()) !== null) {
                        $tokenCounter++;

                        $term = new Index\Term($token->getTermText(), $field->name);
                        $termKey = $term->key();

                        if (!isset($this->_termDictionary[$termKey])) {
                            // New term
                            $this->_termDictionary[$termKey] = $term;
                            $this->_termDocs[$termKey] = array();
                            $this->_termDocs[$termKey][$this->_docCount] = array();
                        } elseif (!isset($this->_termDocs[$termKey][$this->_docCount])) {
                            // Existing term, but new term entry
                            $this->_termDocs[$termKey][$this->_docCount] = array();
                        }
                        $position += $token->getPositionIncrement();
                        $this->_termDocs[$termKey][$this->_docCount][] = $position;
                    }

                    if ($tokenCounter == 0) {
                        // Field contains empty value. Treat it as non-indexed and non-tokenized
                        $field = clone($field);
                        $field->isIndexed = $field->isTokenized = false;
                    } else {
                        $docNorms[$field->name] = chr(
                            $similarity->encodeNorm(
                                $similarity->lengthNorm(
                                    $field->name, $tokenCounter
                                )*$document->boost*$field->boost
                            )
                        );
                    }
                } elseif (($fieldUtf8Value = $field->getUtf8Value()) == '') {
                    // Field contains empty value. Treat it as non-indexed and non-tokenized
                    $field = clone($field);
                    $field->isIndexed = $field->isTokenized = false;
                } else {
                    $term = new Index\Term($fieldUtf8Value, $field->name);
                    $termKey = $term->key();

                    if (!isset($this->_termDictionary[$termKey])) {
                        // New term
                        $this->_termDictionary[$termKey] = $term;
                        $this->_termDocs[$termKey] = array();
                        $this->_termDocs[$termKey][$this->_docCount] = array();
                    } elseif (!isset($this->_termDocs[$termKey][$this->_docCount])) {
                        // Existing term, but new term entry
                        $this->_termDocs[$termKey][$this->_docCount] = array();
                    }
                    $this->_termDocs[$termKey][$this->_docCount][] = 0; // position

                    $docNorms[$field->name] = chr($similarity->encodeNorm( $similarity->lengthNorm($field->name, 1)*
                                                                           $document->boost*
                                                                           $field->boost ));
                }
            }

            if ($field->isStored) {
                $storedFields[] = $field;
            }

            $this->addField($field);
        }

        foreach ($this->_fields as $fieldName => $field) {
            if (!$field->isIndexed) {
                continue;
            }

            if (!isset($this->_norms[$fieldName])) {
                $this->_norms[$fieldName] = str_repeat(chr($similarity->encodeNorm( $similarity->lengthNorm($fieldName, 0) )),
                                                       $this->_docCount);
            }

            if (isset($docNorms[$fieldName])){
                $this->_norms[$fieldName] .= $docNorms[$fieldName];
            } else {
                $this->_norms[$fieldName] .= chr($similarity->encodeNorm( $similarity->lengthNorm($fieldName, 0) ));
            }
        }

        $this->addStoredFields($storedFields);
    }


    /**
     * Dump Term Dictionary (.tis) and Term Dictionary Index (.tii) segment files
     */
    protected function _dumpDictionary()
    {
        ksort($this->_termDictionary, SORT_STRING);

        $this->initializeDictionaryFiles();

        foreach ($this->_termDictionary as $termId => $term) {
            $this->addTerm($term, $this->_termDocs[$termId]);
        }

        $this->closeDictionaryFiles();
    }


    /**
     * Close segment, write it to disk and return segment info
     *
     * @return \Search\Index\SegmentInfo
     */
    public function close()
    {
        if ($this->_docCount == 0) {
            return null;
        }

        $this->_dumpFNM();
        $this->_dumpDictionary();

        $this->_generateCFS();

        return new Index\SegmentInfo($this->_directory,
                                     $this->_name,
                                     $this->_docCount,
                                     -1,
                                     null,
                                     true,
                                     true);
    }

}
