<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

use Illuminate\Database\QueryException;

trait Delete
{
    /**
     * Deletes a record
     *
     * @param   int     $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $entity = $this->model::findOrFail($id);

        if ($entity->delete()) {
            return '';
        }

        throw new QueryException('Could not delete the record.');
    }
}
