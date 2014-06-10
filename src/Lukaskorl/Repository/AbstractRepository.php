<?php namespace Lukaskorl\Repository;

use App;
use League\Fractal;

abstract class AbstractRepository implements Repository {

    /**
     * If a transformer is set for this model output will be transformed.
     *
     * @var \Lukaskorl\Repository\Transformable\Transformer
     */
    protected $transformer = false;

    /**
     * Apply repository specific tasks to a collection of items
     *
     * @param $collection
     * @return mixed
     */
    protected function collection($collection)
    {
        // Check if transformer is configured
        if ($collection && $this->transformer) {
            $collection = $this->getTransformationManager()
                ->createData(new Fractal\Resource\Collection($collection, $this->transformer))
                ->toArray();
        }

        return $collection;
    }

    /**
     * Apply repository specific tasks to a single item
     *
     * @param $item
     * @return mixed
     */
    protected function item($item)
    {
        // Check if transformer is configured
        if ($item && $this->transformer) {
            $item = $this->getTransformationManager()
                ->createData(new Fractal\Resource\Item($item, $this->transformer))
                ->toArray();
        }

        return $item;
    }

    /**
     * @return \League\Fractal\Manager
     */
    protected function getTransformationManager()
    {
        return App::make('League\Fractal\Manager')
            ->setSerializer(new Fractal\Serializer\ArraySerializer);
    }

}
