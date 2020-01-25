<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

trait Update
{
    /**
     * Updates a record
     *
     * @param   mixed         $id
     * @param   array       $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        $primaryKey = $this->model->primaryKey;

        $this->model->where($primaryKey, $id)->update($data);

        return $this->model->where($primaryKey, $id)->get()->first();
    }

    /**
     * Adds entries to the collection in given column
     *
     * @param  mixed  $id
     * @param  string  $column
     * @param  \Cassandra\Value  $collection
     *
     * @return mixed
     */
    public function addToCollection($id, string $column, \Cassandra\Value $collection)
    {
        $primaryKey = $this->model->primaryKey;
        return $this->model->where($primaryKey, $id)
            ->updateCollection($column, '+', $collection)
            ->update([]);
    }

    /**
     * Deletes given keys inside a collection in a column
     *
     * @param  mixed  $id
     * @param  string  $column
     * @param  \Cassandra\Value  $collection
     *
     * @return mixed
     */
    public function removeFromCollection($id, string $column, \Cassandra\Value $collection)
    {
        $primaryKey = $this->model->primaryKey;

        return $this->model->where($primaryKey, $id)
            ->updateCollection($column, '-', $collection)
            ->update([]);
    }
}
