<?php
declare(strict_types=1);

namespace Search\Analysis\Analyzer;

use Search\Analysis\Analyzer\AnalyzerInterface as LuceneAnalyzer;

/**
 * AnalyzerInterface manager.
 */
class Analyzer
{
    /**
     * The AnalyzerInterface implementation used by default.
     *
     * @var \Search\Analysis\Analyzer\AnalyzerInterface
     */
    private static $_defaultImpl = null;

    /**
     * Set the default AnalyzerInterface implementation used by indexing code.
     *
     * @param \Search\Analysis\Analyzer\AnalyzerInterface $analyzer
     */
    public static function setDefault(LuceneAnalyzer $analyzer)
    {
        self::$_defaultImpl = $analyzer;
    }

    /**
     * Return the default AnalyzerInterface implementation used by indexing code.
     *
     * @return \Search\Analysis\Analyzer\AnalyzerInterface
     */
    public static function getDefault()
    {
        if (self::$_defaultImpl === null) {
            self::$_defaultImpl = new Common\Text\CaseInsensitive();
        }

        return self::$_defaultImpl;
    }
}
