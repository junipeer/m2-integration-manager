<?php

namespace Junipeer\IntegrationManager\Api\Data;

interface IntegrationInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID           = 'id';
    const NAME        = 'name';
    const IS_ACTIVE         = 'is_active';
    const USER_INTEGRATION_ID        = 'user_integration_id';
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
     * @return IntegrationInterface
     */
    public function setId($id);

    /**
     * Get User Integration ID
     *
     * @return int|null
     */
    public function getUserIntegrationId();

    /**
     * Set User Integration ID
     *
     * @param $userIntegrationId
     * @return IntegrationInterface
     */
    public function setUserIntegrationId($userIntegrationId);

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
     * Get is active
     *
     * @return bool|int
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param $isActive
     * @return IntegrationInterface
     */
    public function setIsActive($isActive);

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
     * @return IntegrationInterface
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
     * @return IntegrationInterface
     */
    public function setUpdatedAt($updatedAt);
}
