<?php
declare(strict_types=1);

namespace Search\Exception;

use Search\Exception\ExceptionInterface;

class InvalidArgumentException
    extends \InvalidArgumentException
    implements ExceptionInterface
{}
