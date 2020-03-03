<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Entry implements EntryInterface, ArraySerializableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;

    /** @ORM\Column(name="word", type="string", length=32) */
    private $word;

    /** @ORM\Column(name="typology", type="string", length=32) */
    private $typology;

    /** @ORM\Column(name="definition", type="string", length=255) */
    private $definition;

    /**
     * {@inheritdoc}
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getWord(): string {
        return $this->word;
    }

    /**
     * {@inheritdoc}
     */
    public function setWord(int $word) {
        $this->word = $word;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypology(): string {
        return $this->typology;
    }

    /**
     * {@inheritdoc}
     */
    public function setTypology(string $typology) {
        $this->typology = $typology;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): string {
        return $this->definition;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefinition(string $definition) {
        $this->definition = $definition;
    }

    /**
     * transforming to Array
     */
    public function getArrayCopy()
    {
        $data = get_object_vars($this);
        $data['id'] = $this->getId();
        $data['word'] = $this->getWord();
        $data['typology'] = $this->getTypology();
        $data['definition'] = $this->getDefinition();

        return $data;
    }

    /**
     * tranforming array to object
     */
    public function exchangeArray(array $data)
    {
        $this->id = (null != $data['id']) ? $data['id'] : $this->id;
        $this->word = (null != $data['word']) ? $data['word'] : $this->word;
        $this->typology = (null != $data['typology']) ? $data['typology'] : $this->typology;
        $this->definition = (null != $data['definition']) ? $data['definition'] : $this->definition;

    }
}
