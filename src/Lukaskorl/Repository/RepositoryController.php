<?php namespace Lukaskorl\Repository;

use Input;
use Lukaskorl\Repository\Validation\ValidationException;
use Lukaskorl\Restful\Facades\Restful;

abstract class RepositoryController extends \BaseController {

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Public setter for repository
     *
     * @param Repository $repository
     * @return $this
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Public getter for repository
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Restful::collection($this->getRepository()->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        try {
            return Restful::created($this->getRepository()->create(Input::all()));
        }
        catch (ValidationException $exception) {
            return Restful::validationFailed($exception->getErrors()->toArray());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Restful::entity($this->getRepository()->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        return Restful::updated($this->getRepository()->update($id, Input::all()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // Delete the entity
        $this->getRepository()->destroy($id);

        // Respond restfully
        return Restful::deleted();
    }

} 