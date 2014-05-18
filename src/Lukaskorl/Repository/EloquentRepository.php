<?php namespace Lukaskorl\Repository;

use App, Eloquent;
use Lukaskorl\Repository\Exceptions\ModelNotSpecifiedException;
use Lukaskorl\Repository\Exceptions\InvalidRepositoryConfigurationException;

abstract class EloquentRepository extends AbstractRepository {

    /**
     * Specifies name of the class used for this model
     *
     * @var string
     */
    protected $model = false;

    /**
     * Retrieves a collection of all entities in this repository
     *
     * @return mixed
     */
    public function all()
    {
        return $this->collection(
            $this->getModel()->all()
        );
    }

    /**
     * Retrieves a single entity of this repository
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->item(
            $this->getModel()->find($id)
        );
    }

    /**
     * Create a new entity in this repository
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->item(
            $this->getModel()->create($attributes)
        );
    }

    /**
     * Update an entity in this repository
     *
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        if ( $this->getModel()->find($id)->update($attributes) ) {
            return $this->find($id);
        }
    }

    /**
     * Remove an entity from this repository
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->getModel()->destroy($id);
    }

    /**
     * Return model used for this repository. Default a model based on the $modelClass member is created.
     *
     * @return Eloquent
     */
    public function getModel()
    {
        // Check if configuration is complete
        if ( ! $this->model ) throw new ModelNotSpecifiedException("Set classname of model in protected member variable \$modelClass of class ".get_class($this));

        // Instantiate the model
        $model = App::make($this->model);

        // Check if model is valid
        if ( ! $model instanceof Eloquent ) throw new InvalidRepositoryConfigurationException("Model for ".get_class($this)." must be subclass of \\Eloquent");

        // Return model if checks are passed
        return $model;
    }

}
