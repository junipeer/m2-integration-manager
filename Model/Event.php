<?php

namespace Junipeer\IntegrationManager\Model;

use Magento\Framework\Model\AbstractModel;
use Junipeer\IntegrationManager\Api\Data\EventInterface;

class Event extends AbstractModel implements EventInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'junipeer_events';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Junipeer\IntegrationManager\Model\ResourceModel\Event');
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
     * @return int|mixed|null
     */
    public function getActionId()
    {
        return $this->getData(EventInterface::ACTION);
    }

    /**
     * @param $actionId
     * @return Event|mixed
     */
    public function setActionId($actionId)
    {
        return $this->setData(EventInterface::ACTION, $actionId);
    }

    /**
     * @return int|mixed|null
     */
    public function getIntegrationId()
    {
        return $this->getData(EventInterface::INTEGRATION);
    }

    /**
     * @param $integrationId
     * @return Event|mixed
     */
    public function setIntegrationId($integrationId)
    {
        return $this->setData(EventInterface::INTEGRATION, $integrationId);
    }

    /**
     * @return mixed|string
     */
    public function getEvent()
    {
        return $this->getData(EventInterface::EVENT);
    }

    /**
     * @param $event
     * @return Event|mixed
     */
    public function setEvent($event)
    {
        return $this->setData(EventInterface::EVENT, $event);
    }


    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(EventInterface::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(EventInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(EventInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(EventInterface::UPDATED_AT, $updatedAt);
    }
}
