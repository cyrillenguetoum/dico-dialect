<?php
declare(strict_types=1);

namespace App\Entity;

interface ArraySerializableInterface
{
    /**
     * Transform an entity object into an array and return it
     * @return []
     */
    public function getArrayCopy();

    /**
     * Takes an array and transform it into an entity object
     * @param []
     * @return Object
     */
    public function exchangeArray(array $data);
}
