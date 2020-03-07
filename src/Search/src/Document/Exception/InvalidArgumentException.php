<?php
declare(strict_types=1);

namespace Search\Document\Exception;

use Search\Exception;

class InvalidArgumentException
    extends Exception\InvalidArgumentException
    implements ExceptionInterface
{}
