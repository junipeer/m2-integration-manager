<?php

namespace Junipeer\IntegrationManager\Api\Data;

interface ActionInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID           = 'id';
    const NAME        = 'name';
    const ACTION        = 'action';
    const IS_MANY        = 'is_many';
    const ENTITY_TYPE        = 'entity_type';
    const INTEGRATION_ID        = 'integration_id';
    const FIELDS        = 'fields';
    const CREATED_AT        = 'created_at';
    const UPDATED_AT        = 'updated_at';


    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param $id
     * @return mixed
     */
    public function setId($id);

    /**
     * Get Integration ID
     *
     * @return int|null
     */
    public function getIntegrationId();

    /**
     * Set ID
     *
     * @param $integrationId
     * @return mixed
     */
    public function setIntegrationId($integrationId);

    /**
     * Get Integration Name
     *
     * @return string
     */
    public function getName();

    /**
     * Set Integration Name
     *
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * Get Action
     *
     * @return string
     */
    public function getAction();

    /**
     * Set action
     *
     * @param $action
     * @return mixed
     */
    public function setAction($action);

    /**
     * Get Fields
     *
     * @return string
     */
    public function getFields();

    /**
     * Set $fields
     *
     * @param $fields
     * @return mixed
     */
    public function setFields($fields);


    /**
     * Get is active
     *
     * @return bool|int
     */
    public function getIsMany();

    /**
     * Set is many
     *
     * @param $isMany
     * @return mixed
     */
    public function setIsMany($isMany);

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntityType();

    /**
     * Set actity
     *
     * @param $entityType
     * @return mixed
     */
    public function setEntityType($entityType);


    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * set created at
     *
     * @param $createdAt
     * @return mixed
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * set updated at
     *
     * @param $updatedAt
     * @return mixed
     */
    public function setUpdatedAt($updatedAt);
}
