<?php
declare(strict_types=1);

namespace Search;

use Search\Exception\UnsupportedMethodCallException;

class Lucene
{
    /**
     * Default field name for search
     *
     * Null means search through all fields
     *
     * @var string
     */
    private static $defaultSearchField = null;

    /**
     * Result set limit
     *
     * 0 means no limit
     *
     * @var integer
     */
    private static $resultSetLimit = 0;

    /**
     * Terms per query limit
     *
     * 0 means no limit
     *
     * @var integer
     */
    private static $termsPerQueryLimit = 1024;

    /**
     * Create index
     *
     * @param mixed $directory
     * @return Search\SearchIndexInterface
     */
    public static function create($directory)
    {
        return new Index($directory, true);
    }

    /**
     * Open index
     *
     * @param mixed $directory
     * @return Search\SearchIndexInterface
     */
    public static function open($directory)
    {
        return new Index($directory, false);
    }

    /**
     * @throws Search\Exception\UnsupportedMethodCallException
     */
    public function __construct()
    {
        throw new UnsupportedMethodCallException(
            'Search\Lucene class is the only container for static methods.
             Use Lucene::open() or Lucene::create() methods.'
        );
    }

    /**
     * Set default search field.
     *
     * Null means, that search is performed through all fields by default
     *
     * Default value is null
     *
     * @param string $fieldName
     */
    public static function setDefaultSearchField($fieldName)
    {
        self::$defaultSearchField = $fieldName;
    }

    /**
     * Get default search field.
     *
     * Null means, that search is performed through all fields by default
     *
     * @return string
     */
    public static function getDefaultSearchField()
    {
        return self::$defaultSearchField;
    }

    /**
     * Set result set limit.
     *
     * 0 (default) means no limit
     *
     * @param integer $limit
    */
    public static function setResultSetLimit($limit)
    {
        self::$resultSetLimit = $limit;
    }

    /**
     * Get result set limit.
     *
     * 0 means no limit
     *
     * @return integer
     */
    public static function getResultSetLimit()
    {
        return self::$resultSetLimit;
    }

    /**
     * Set terms per query limit.
     *
     * 0 means no limit
     *
     * @param integer $limit
    */
    public static function setTermsPerQueryLimit($limit)
    {
        self::$termsPerQueryLimit = $limit;
    }

    /**
     * Get result set limit.
     *
     * 0 (default) means no limit
     *
     * @return integer
    */
    public static function getTermsPerQueryLimit()
    {
        return self::$termsPerQueryLimit;
    }
}
