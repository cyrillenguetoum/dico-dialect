<?php
declare(strict_types=1);

namespace Search\Index;

use Search\AbstractPriorityQueue;

/** @todo !!!!!! convert to SPL class usage */

class TermsPriorityQueue extends AbstractPriorityQueue
{
    /**
     * Compare elements
     *
     * Returns true, if $termsStream1 is "less" than $termsStream2; else otherwise
     *
     * @param mixed $termsStream1
     * @param mixed $termsStream2
     * @return boolean
     */
    protected function _less($termsStream1, $termsStream2)
    {
        return strcmp($termsStream1->currentTerm()->key(), $termsStream2->currentTerm()->key()) < 0;
    }
}
