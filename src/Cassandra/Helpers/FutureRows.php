<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Helpers;

use Illuminate\Support\LazyCollection;

class FutureRows
{
    /**
     * FutureRows list
     *
     * @var \Cassandra\FutureRows[]
     */
    private $rows = [];

    /**
     * Adds a row to FutureRows list
     *
     * @param  \Cassandra\FutureRows  $row
     */
    public function addRow(\Cassandra\FutureRows $row)
    {
        $this->rows[] = $row;
    }

    /**
     * Collects the results and flattens them into a Lazy Collection
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function collectResults()
    {
        return LazyCollection::make(function () {
            foreach ($this->rows as $row) {
                $results = $row->get();

                do {
                    foreach ($results as $result) {
                        yield ($result);
                    }
                } while (!$results->isLastPage() and $results = $results->nextPage());
            }
        });
    }
}
