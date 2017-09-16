<?php
namespace HackTheNorth\CockroachNest\Repositories;

interface RepositoryInterface
{
    /**
     * Creates new entity and persists it
     *
     * @param $obj
     */
    public function create($obj);

    /**
     * Updates an existing entity
     *
     * @param $obj
     */
    public function save($obj);

    /**
     * Removes an entity
     *
     * @param $obj
     */
    public function remove($obj);
}