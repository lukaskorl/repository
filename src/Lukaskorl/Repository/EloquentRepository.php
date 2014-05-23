<?php namespace Lukaskorl\Repository;

use App, Eloquent;
use Lukaskorl\Repository\Exceptions\EntityNotFoundException;
use Lukaskorl\Repository\Exceptions\ModelNotSpecifiedException;
use Lukaskorl\Repository\Exceptions\InvalidRepositoryConfigurationException;
use Exception;

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
        return $this->item( $this->call( __FUNCTION__, func_get_args() ) );
    }

    /**
     * Update an entity in this repository
     *
     * @param $id
     * @param array $attributes
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function update($id, array $attributes)
    {
        return $this->item( $this->findOrFail($id)->update($attributes) );
    }

    /**
     * Remove an entity from this repository
     *
     * @param $ids
     * @return mixed|integer
     */
    public function delete($ids)
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
