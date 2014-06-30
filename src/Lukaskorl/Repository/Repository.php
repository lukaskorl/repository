<?php namespace Lukaskorl\Repository;

use Lukaskorl\Repository\Exceptions\EntityNotFoundException;

interface Repository
{

    /**
     * Return all entities in this repository. Include
     *
     * @param array $columns
     * @return array
     */
    public function all($columns = array('*'));

    /**
     * Find an entity by it's primary key
     *
     * @param $id
     * @param array $columns
     * @return array
     */
    public function find($id, $columns = array('*'));

    /**
     * Create an entity with the given attributes. Return the newly created entity including the new primary key.
     *
     * @param array $attributes
     * @param array $forceAttributes This attributes will be forced onto the entity (circumventing i.e. mass assignment)
     * @return array
     */
    public function create(array $attributes = array(), array $forceAttributes = array());

    /**
     * Update an existing entity identified by it's primary key. Returns the updated entity.
     *
     * @param $id
     * @param array $attributes
     * @return array
     */
    public function update($id, array $attributes);

    /**
     * Delete entities from the repository by id.
     *
     * @param $ids array|integer
     * @return mixed
     */
    public function destroy($ids);

    /**
     * Find an entity by its primary key or throw an exception.
     *
     * @param $id
     * @param array $columns
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function findOrFail($id, $columns = array('*'));

} 