<?php
/**
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

namespace Aledefreitas\EloquentRepositories\Cassandra\Helpers;

use \Cassandra\Uuid;
use \Cassandra\Timeuuid;
use \Cassandra\Type;

class CassandraHelper
{
    /**
     * Creates a Cassandra UUID type
     *
     * @param  string  $uuid
     *
     * @return \Cassandra\Uuid
     */
    public static function uuid(string $uuid)
    {
        return new \Cassandra\Uuid($uuid);
    }

    /**
     * Creates a Cassandra TIMEUUID type from current time
     *
     * @return \Cassandra\Timeuuid
     */
    public static function timeuuid()
    {
        // Sleeps for 1 microsecond to prevent any chances of timeuuid collisions
        usleep(1);
        return new \Cassandra\Timeuuid(hrtime(true));
    }

    /**
     * Creates a Cassandra Timestamp type
     *
     * @param  mixed  $time
     *
     * @return \Cassandra\Timestamp
     */
    public static function timestamp($time = null)
    {
        if (is_string($time)) {
            $time = strtotime($time);
        }

        if (!isset($time)) {
            $time = strtotime('now');
        }

        return new \Cassandra\Timestamp($time);
    }

    /**
     * Creates a Cassandra Map type
     *
     * @param  string  $keyType
     * @param  string  $valueType
     * @param  array[]  $values
     *
     * @return \Cassandra\Map
     */
    public static function map(string $keyType, string $valueType, array $values = [])
    {
        $parameters = [];

        foreach ($values as $value) {
            if (is_array($value)) {
                $parameters[] = $value[0];
                $parameters[] = $value[1];
            }
        }

        return \Cassandra\Type::map(
            \Cassandra\Type::{$keyType}(),
            \Cassandra\Type::{$valueType}()
        )->create(
            ...$parameters
        );
    }

    /**
     * Creates a Cassandra Set type
     *
     * @param  string  $keyType
     * @param  array  $values
     *
     * @return \Cassandra\Set
     */
    public static function set(string $keyType, array $values = [])
    {
        return \Cassandra\Type::set(\Cassandra\Type::{$keyType}())
            ->create(
                ...$values
            );
    }

    /**
     * Creates a Cassandra Collection type
     *
     * @param  string  $keyType
     * @param  array  $values
     *
     * @return \Cassandra\Collection
     */
    public static function collection(string $keyType, array $values = [])
    {
        return \Cassandra\Type::collection(\Cassandra\Type::{$keyType}())
            ->create(
                ...$values
            );
    }
}
