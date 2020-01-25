<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Operations;

trait TriggersEvents
{
    /**
     * Triggers an event for Eloquent Observers on a given model
     *
     * @param  string  $eventName
     * @param  mixed  $modelInstance
     *
     * @return void
     */
    protected function triggerEloquentEvent(string $eventName, $modelInstance)
    {
        $modelClass = get_class($this->model);

        if ($modelInstance) {
            event("eloquent.{$eventName}: {$modelClass}", $modelInstance);
        }
    }
}
