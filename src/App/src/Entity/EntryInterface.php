<?php
declare(strict_types=1);

namespace App\Entity;

Interface EntryInterface
{
    /**
     * Return the id of the word
     * @return int
     */
    public function getId(): int;
    /**
     * Sets the id of the word
     * @param int
     */
    public function setId(int $id);

    /**
     * Return the word
     * @return string
     */
    public function getWord(): string;
    /**
     * Sets the word
     * @param string
     */
    public function setWord(int $word);

    /**
     * Return the typology of the word
     * @return string
     */
    public function getTypology(): string;

    /**
     * Sets the typology of the word
     * @param string
     */
    public function setTypology(string $typology);

    /**
     * Return the definition of the word
     * @return string
     */
    public function getDefinition(): string;

    /**
     * Sets the definition of the word
     * @param string
     */
    public function setDefinition(string $definition);
}
