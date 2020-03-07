<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Search\Lucene;
use Search\Document;
use Search\Document\Field;
use Search\Multisearcher;
use App\Repository\EntryRepository;

class SearchHandler implements RequestHandlerInterface
{
    const INDEX_DIR = 'data/search';

    private $repository;

    public function __construct(EntryRepository $repository)
    {
        $this->repository = $repository;
    }
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $toSearch = $request->getQueryParams()['word'] ?? 1;

        if ($toSearch === 1) {
            return $this->generateIndex();
        }

        $search = Lucene::open($this::INDEX_DIR);
        $results = $search->find($toSearch);

        $finalResults=[];

        foreach($results as $result) {
            $test = [];
            $test['entry_id'] = $result->entry_id;
            $test['word'] = $result->word;
            $test['typology'] = $result->typology;
            $test['definition'] = $result->definition;

            $finalResults[] = $test;
        }

        return new JsonResponse([
            'results' => $finalResults,
        ]);
    }

    private function generateIndex()
    {
        $index = Lucene::create($this::INDEX_DIR);
        $entries = $this->repository->findAll(true);
        foreach ($entries as $entry) {
            $entryId = Field::unIndexed('entry_id', $entry->getId());
            $word = Field::text('word', $entry->getWord());
            $typology = Field::text('typology', $entry->getTypology());
            $definition = Field::text('definition', $entry->getDefinition());

            $indexDoc = new Document();
            $indexDoc->addField($entryId);
            $indexDoc->addField($word);
            $indexDoc->addField($typology);
            $indexDoc->addField($definition);
            $index->addDocument($indexDoc);
        }
        $index->commit();

        return new JsonResponse([
            'Message' => 'The search index was successfully updated',
        ]);
    }
}
