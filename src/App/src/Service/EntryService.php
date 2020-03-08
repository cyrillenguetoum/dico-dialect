<?php

declare(strict_types=1);

namespace App\Service;

use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\ArrayAdapter;
use Doctrine\ORM\EntityManager;
use App\Entity\EntryCollection;
use App\Filter\EntryInputFilter;
use App\Entity\Entry;
use App\Exception;

class EntryService
{
    /** @var EntityManager **/
    private $em;

    /** @var InputFilter **/
    private $inputFilter;

    public function __construct(
        EntityManager $entityManager,
        EntryInputFilter $inputFilter
    ) {
        $this->em = $entityManager;
        $this->inputFilter = $inputFilter;
    }

    /**
     * Get all entries in a page form inside a Paginator SplObjectStorage
     * @return Paginator
     */
    public function findAll() : Paginator
    {
        // return $this->em->getRepository(Post::class)->findPublishedPosts();
        $entries = $this->em->getRepository(Entry::class)->findAll();
        $adapter = new ArrayAdapter($entries);
        $paginator = new EntryCollection($adapter);
        return $paginator;
    }

    /**
     * Get a Entry by $id
     *
     * @return Entry
     * @throws Exception\NoResourceFoundException
     */
    public function find(int $id): Entry
    {
        $entry = $this->em->find(Entry::class, $id);
        if (! $entry instanceof Entry) {
            throw Exception\NoResourceFoundException::create('Entry not found');
        }
        return $entry;
    }


    /**
     * Add Entry with $data values
     *
     * @throws Exception\InvalidParameterException if the data is not valid.
     * @throws Exception\RuntimeException if an error occurs during insert.
     * @return Entry the newly create Blog entry object
     */
    public function addEntry(array $data) : Entry {
        $this->inputFilter->setData($data);
        if (! $this->inputFilter->isValid()) {
            throw Exception\InvalidParameterException::create(
                'Invalid parameter',
                $this->inputFilter->getMessages()
            );
        }

        $entry = new Entry();
        $entry->exchangeArray($data);

        try {
            $this->em->persist($entry);
            $this->em->flush();
        } catch (PDOException $e) {
            throw Exception\RuntimeException::create(
                'Oops, something went wrong. Please contact the administrator'
            );
        }

        // Retrieve the newly created entry. We want the id
        $criteria = ['word' => $entry->getWord()];
        $entry = $this->em->getRepository(Entry::class)->findBy($criteria);
        return $entry[0];
    }

    /**
     * Update the entry with $id and $data
     *
     * @throws Exception\InvalidParameterException if the data is not valid
     * @throws Exception\NoResourceFoundException if no rows are returned by The
     *      updated resource
     */
    public function updateEntry(int $id, array $data) : Entry {
        $this->inputFilter->setData($data);

        if (! $this->inputFilter->isValid()) {
            throw Exception\InvalidParameterException::create(
                'Invalid parameter',
                $this->inputFilter->getMessages()
            );
        }

        $entry = $this->em->getRepository(Entry::class)->find($id);
        $entry->exchangeArray($data);

        // Apply changes to database
        try {
            $this->em->persist($entry);
            $this->em->flush();
        } catch (PDOException $e) {
            throw Exception\RuntimeException::create(
                'Oops, something went wrong. Please contact the administrator'
            );
        }

        // Retrieve the newly modified entry. We want the id
        return $this->find($id);
    }

    /**
     * Remove the Entry with $id
     *
     * @throws Exception\NoResourceFoundException if no rows are returned by
     *  the delete operation.
     */
    public function deleteEntry($id): bool
    {
        $entry = $this->em->getRepository(Entry::class)->find($id);
        if (! $entry instanceof Entry){
            throw Exception\NoResourceFoundException::create('Entry not found');
        }

        try {
            $this->em->remove($entry);
            $this->em->flush();
        } catch(Exception\RuntimeException $e){
            throw Exception\RuntimeException::create(
                'Oops, something went wrong. Please contact the administrator'
            );
        }

        return true;
    }
}
