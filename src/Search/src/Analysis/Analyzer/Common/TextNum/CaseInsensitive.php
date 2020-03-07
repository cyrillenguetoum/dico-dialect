<?php
declare(strict_types=1);

namespace Search\Analysis\Analyzer\Common\TextNum;

use Search\Analysis\Analyzer\Common;
use Search\Analysis\TokenFilter;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
class CaseInsensitive extends Common\TextNum
{
    public function __construct()
    {
        $this->addFilter(new TokenFilter\LowerCase());
    }
}
