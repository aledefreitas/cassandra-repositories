<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

trait BulkDeletion
{
    /**
     * Creates a new record
     *
     * @param   array       $ids        IDs to delete
     *
     * @return int
     */
    public function deleteAll(array $ids = [])
    {
        // It is much faster to execute small individual queries in Cassandra
        // Due to partitioning
        foreach ($ids as $id) {
            return $this->model
                ->where($this->model->primaryKey, $id)
                ->delete();
        }
    }

    /**
     * Deletes all records where column's value matches value sent
     *
     * @param   string      $column
     * @param   mixed       $value
     *
     * @return mixed
     */
    public function deleteAllWhere(string $column, $value)
    {
        return $this->model
            ->where($column, $value)
            ->delete();
    }

    /**
     * Deletes all records where column's value matches values sent
     *
     * @param   string      $column
     * @param   mixed       $values
     *
     * @return mixed
     */
    public function deleteAllWhereIn(string $column, $values)
    {
        return $this->model
            ->whereIn($column, $values)
            ->delete();
    }
}
