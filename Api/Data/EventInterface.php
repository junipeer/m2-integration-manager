<?php

namespace Junipeer\IntegrationManager\Api\Data;

interface EventInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID           = 'id';
    const EVENT        = 'event';
    const ACTION        = 'action_id';
    const INTEGRATION        = 'integration_id';

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
     * Get Action ID
     *
     * @return int|null
     */
    public function getActionId();

    /**
     * Set ID
     *
     * @param $actionId
     * @return mixed
     */
    public function setActionId($actionId);

    /**
     * Get Integration ID
     *
     * @return int|null
     */
    public function getIntegrationId();

    /**
     * Set Integration ID
     *
     * @param $integrationId
     * @return mixed
     */
    public function setIntegrationId($integrationId);

    /**
     * Get Event
     *
     * @return string
     */
    public function getEvent();

    /**
     * Set Event
     *
     * @param $event
     * @return mixed
     */
    public function setEvent($event);

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
