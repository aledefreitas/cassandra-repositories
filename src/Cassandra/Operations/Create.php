<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

trait Create
{
    /**
     * Creates a new record and returns it
     *
     * @return \Illuminate\Database\Cassandra\Model
     */
    public function create(array $data = [])
    {
        return $this->model->create($data);
    }
}
