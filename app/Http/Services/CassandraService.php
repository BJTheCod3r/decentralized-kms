<?php

namespace App\Http\Services;

use App\Http\Services\Contracts\CassandraServiceContract;
use Cassandra\Connection;
use Cassandra\Response\Event;
use Cassandra\Response\Response;
use Exception;
use Illuminate\Support\Facades\Log;
use SplFixedArray;
use Illuminate\Support\Str;

/**
 * Class CassandraService
 * Things have been kept really simple in this class, but in the future
 * we can add more methods to make this class robust and fully ready for
 * more complex usage, do some housekeeping and ultimately for production.
 *
 * @package App\Http\Services
 */
class CassandraService implements CassandraServiceContract
{

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var Event|Response
     */
    private Event|Response $response;

    /**
     * @var string
     */
    private string $primaryKey;

    /**
     * @var string
     */
    private string $primaryKeyType;

    /**
     * CassandraService constructor
     * @throws Exception
     */
    public function __construct()
    {
        $this->connection = New Connection(config('cassandra.nodes'), config('cassandra.key_space'));
        $this->connect();
    }

    /**
     * Connect to Cassandra to start operations
     * @return void
     * @throws Exception
     */
    private function connect(): void
    {
        try {
            $this->connection->connect();
        } catch (Exception $e) {

            //let's log the error message if there is any
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * Select raw query
     *
     * @param string $query
     * @return self
     * @throws Exception
     */
    public function selectRaw(string $query): self
    {
         try {
             $this->response = $this->connection->querySync($query);
         } catch(Exception $e) {
             Log::error($e->getMessage());
             throw $e;
         }

         return $this;
    }

    /**
     * Get all rows in query
     *
     * @return SplFixedArray
     */
    public function get(): SplFixedArray
    {
        return $this->response->fetchAll();
    }

    /**
     * @param string $table
     * @param mixed $id
     * @return SplFixedArray
     * @throws Exception
     */
    public function find(string $table, mixed $id): SplFixedArray
    {
       return $this->findOneBy($table, 'id', $id);
    }

    /**
     * @param string $table
     * @param string $col
     * @param mixed $value
     * @return SplFixedArray
     * @throws Exception
     */
    public function findOneBy(string $table, string $col, mixed $value): SplFixedArray
    {
        try {
            $this->response = $this->connection
                ->querySync("SELECT * FROM '$table' WHERE '$col' = $value");
        } catch(Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return $this->response->fetchRow();
    }

    /**
     * Create a new record
     *
     * @param string $table
     * @param array $data
     * @param string $primaryKey = 'id'
     * @param string $primaryKeyType = 'uuid'
     * @return Response|Event
     * @throws Exception
     */
    public function create(string $table, array $data, string $primaryKey = 'id', string $primaryKeyType = 'uuid'): Response|Event
    {
        $this->primaryKey = $primaryKey;
        $this->primaryKeyType = $primaryKeyType;

        try {
            $this->response = $this->connection->querySync($this->arrayToCqlInsert($table, $data));
        } catch(Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return $this->response;
    }

    /**
     * Change array to cql insert
     *
     * @param string $table
     * @param array $data
     * @return string
     */
    private function arrayToCqlInsert(string $table, array $data): string
    {
        //let's add the primary key to it
        $data = array_merge($data, [
            $this->primaryKey => $this->primaryKeyType === 'uuid' ? Str::uuid() : now().mt_rand(1, 20)
        ]);

        $columns = implode(',', array_keys($data));
        $values = implode(',', array_values($data));

        return "INSERT INTO '$table' ($columns) VALUES ($values)";
    }
}
