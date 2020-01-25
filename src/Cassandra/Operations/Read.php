<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

use \Closure;
use Aledefreitas\EloquentRepositories\Cassandra\Helpers\FutureRows;
use Illuminate\Pagination\Paginator;

trait Read
{
    /**
     * Gets all records
     *
     * @return null|array
     */
    public function all(array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Paginates with the results from a given query function
     *
     * @param  int  $results_per_page
     * @param  null|Closure  $query  The query to retrieve data
     * @param  array  $columns
     *
     * @return null|array
     */
    public function paginate(
        int $results_per_page = 10,
        Closure $query = null,
        array $columns = [ '*' ]
    ) : ?array {
        if (!is_null($query)) {
            $query = call_user_func($query, $this);
        } else {
            $query = $this->model;
        }

        $pagination_state_token = request()->query('page') ?: null;
        $currentStateToken = base64_decode(
            str_replace(
                [ '_', '-' ],
                [ '/', '+' ],
                $pagination_state_token
            )
        );

        /** @var \Cassandra\Rows **/
        $paginatedResults = $query->paginate($results_per_page, $columns, 'page', $currentStateToken);
        $next_page_state_token = $paginatedResults->pagingStateToken();

        $nextPageToken = str_replace(
            [ '=', '+', '/' ],
            [ '', '-', '_' ],
            base64_encode($next_page_state_token)
        );

        $nextPageUrl = isset($next_page_state_token) ?
            (string) Paginator::resolveCurrentPath() . '?page=' . $nextPageToken :
            null;

        $currentPageUrl = isset($pagination_state_token) ?
            (string) Paginator::resolveCurrentPath() . '?page=' . $pagination_state_token :
            (string) Paginator::resolveCurrentPath();

        $results = [];

        foreach ($paginatedResults as $row) {
            $results[] = $this->model->newInstance($row);
        }

        return [
            'current_page_url' => $currentPageUrl,
            'next_page_url' => $nextPageUrl,
            'data' => collect($results),
        ];
    }

    /**
     * Gets a specific record, by its primary key
     *
     * @param   int         $id
     * @param   bool        $fail       Whether to fail if not found or not
     *
     * @return  null|array
     */
    public function findById($id, $fail = true) : ?array
    {
        if ($fail) {
            return $this->model::findOrFail($id)->toArray();
        }

        return $this->model::find($id)->toArray();
    }

    /**
     * Gets many results asynchronously from Cassandra and returns an Iterator with Future Rows
     *
     * @param  array  $search_array
     * @param  null|string  $column
     * @param  array  $resultColumns
     *
     * @return \Illuminate\Support\LazyCollection
     */
    protected function getMany(
        array $search_array,
        ?string $column = null,
        array $resultColumns = [ '*' ]
    ) {
        if (!isset($column)) {
            $column = $this->model->primaryKey;
        }

        $resultIterator = new FutureRows($this->model);

        foreach ($search_array as $search_item) {
            $resultIterator->addRow(
                $this->model
                    ->where($column, $search_item)
                    ->getAsync($resultColumns)
            );
        }

        return $resultIterator->collectResults();
    }
}
