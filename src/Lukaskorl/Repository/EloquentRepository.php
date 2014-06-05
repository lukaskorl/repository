<?php namespace Lukaskorl\Repository;

use App, Eloquent, Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Lukaskorl\Repository\Exceptions\EntityNotFoundException;
use Lukaskorl\Repository\Exceptions\UnableToCompleteException;

abstract class EloquentRepository extends AbstractRepository {

    /**
     * Specifies name of the class used for this model
     *
     * @var string
     */
    protected $model = false;

    /**
     * Set the class name of the Eloquent model
     *
     * @param $model string
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get the class name of the model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Retrieves a collection of all entities in this repository
     *
     * @param array $columns
     * @return array
     */
    public function all($columns = array('*'))
    {
        return $this->collection( $this->call( __FUNCTION__, func_get_args() ) );
    }

    /**
     * Retrieves a single entity of this repository
     *
     * @param $id
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->item( $this->call( __FUNCTION__, func_get_args() ) );
    }

    /**
     * Create a new entity in this repository
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes = array())
    {
        // Fire event before creating entity (i.e. validation will hook onto this event)
        Event::fire('api.' . Str::camel(Str::singular($this->model)) . '.creating', [$attributes]);

        // 1. Create the entity in the database
        // 2. Re-fetch entity from database
        // 3. Transform item
        return $this->item( $this->findOrFail( $this->call( __FUNCTION__, func_get_args() )->getKey() ) );
    }

    /**
     * Update an entity in this repository
     *
     * @param $id
     * @param array $attributes
     * @return mixed
     * @throws EntityNotFoundException|UnableToCompleteException
     */
    public function update($id, array $attributes)
    {
        if( $this->call( 'findOrFail', array( $id ) )->fill($attributes)->save() ) {
            return $this->findOrFail($id);
        }

        throw new UnableToCompleteException;
    }

    /**
     * Remove an entity from this repository
     *
     * @param $ids
     * @return mixed|integer
     */
    public function destroy($ids)
    {
        return $this->call( __FUNCTION__, func_get_args() );
    }

    /**
     * Find an entity by its primary key or throw an exception
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, $columns = array('*'))
    {
        try {
            return $this->item( $this->call( __FUNCTION__, func_get_args() ) );
        } catch ( Exception $e ) {
            throw new EntityNotFoundException($e->getMessage());
        }
    }

    /**
     * Call a method on the Eloquent model
     *
     * @param $method
     * @param array $arguments
     * @return mixed
     */
    protected function call($method, $arguments = array())
    {
        return call_user_func_array("{$this->model}::{$method}", $arguments);
    }

}
