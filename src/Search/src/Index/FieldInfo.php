<?php
declare(strict_types=1);

namespace Search\Index;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Index
 */
class FieldInfo
{
    public $name;
    public $isIndexed;
    public $number;
    public $storeTermVector;
    public $normsOmitted;
    public $payloadsStored;

    public function __construct($name, $isIndexed, $number, $storeTermVector, $normsOmitted = false, $payloadsStored = false)
    {
        $this->name            = $name;
        $this->isIndexed       = $isIndexed;
        $this->number          = $number;
        $this->storeTermVector = $storeTermVector;
        $this->normsOmitted    = $normsOmitted;
        $this->payloadsStored  = $payloadsStored;
    }
}
