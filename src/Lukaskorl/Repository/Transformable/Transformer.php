<?php namespace Lukaskorl\Repository\Transformable;

use ConnorVG\Transform\TransformFacade as Transform;
use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract {

    protected $definitions = [];

    protected $aliases = null;

    public function transform($data)
    {
        return Transform::make($data, $this->definitions, $this->aliases);
    }

}