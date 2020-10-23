<?php

namespace Junipeer\IntegrationManager\Model;

use Magento\Framework\Model\AbstractModel;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterface;

class Integration extends AbstractModel implements IntegrationInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'junipeer_integrations';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Junipeer\IntegrationManager\Model\ResourceModel\Integration');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getUserIntegrationId()
    {
        return $this->getData(IntegrationInterface::USER_INTEGRATION_ID);
    }

    /**
     * Set title
     *
     * @param $userIntegrationId
     * @return $this
     */
    public function setUserIntegrationId($userIntegrationId)
    {
        return $this->setData(IntegrationInterface::USER_INTEGRATION_ID, $userIntegrationId);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(IntegrationInterface::NAME);
    }

    /**
     * Set title
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(IntegrationInterface::NAME, $name);
    }

    /**
     * Get is active
     *
     * @return bool|int
     */
    public function getIsActive()
    {
        return $this->getData(IntegrationInterface::IS_ACTIVE);
    }

    /**
     * Set is active
     *
     * @param $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(IntegrationInterface::IS_ACTIVE, $isActive);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(IntegrationInterface::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(IntegrationInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(IntegrationInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(IntegrationInterface::UPDATED_AT, $updatedAt);
    }
}
