<?php
declare(strict_types=1);

namespace Search\Analysis\Analyzer\Common\Utf8Num;

use Search\Analysis\Analyzer\Common;
use Search\Analysis\TokenFilter;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
class CaseInsensitive extends Common\Utf8Num
{
    public function __construct()
    {
        parent::__construct();

        $this->addFilter(new TokenFilter\LowerCaseUtf8());
    }
}
