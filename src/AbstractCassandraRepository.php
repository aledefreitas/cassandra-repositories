<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories;

use Aledefreitas\EloquentRepositories\AbstractRepository as BaseRepository;

use Aledefreitas\EloquentRepositories\Contracts\Operations\ReadInterface;
use Aledefreitas\EloquentRepositories\Cassandra\Operations\Read;
use Aledefreitas\EloquentRepositories\Cassandra\Operations\TriggersEvents;

abstract class AbstractCassandraRepository extends BaseRepository implements ReadInterface
{
    use Read;
    use TriggersEvents;
}
