<?php

namespace Junipeer\IntegrationManager\Model;

use Magento\Framework\Model\AbstractModel;
use Junipeer\IntegrationManager\Api\Data\ActionInterface;

class Action extends AbstractModel implements ActionInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'junipeer_actions';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Junipeer\IntegrationManager\Model\ResourceModel\Action');
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
     * @return int
     */
    public function getIntegrationId()
    {
        return $this->getData(ActionInterface::INTEGRATION_ID);
    }

    /**
     * Set title
     *
     * @param $integrationId
     * @return $this
     */
    public function setIntegrationId($integrationId)
    {
        return $this->setData(ActionInterface::INTEGRATION_ID, $integrationId);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(ActionInterface::NAME);
    }


    /**
     * Set name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(ActionInterface::NAME, $name);
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getData(ActionInterface::ACTION);
    }

    /**
     * Set action
     *
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        return $this->setData(ActionInterface::ACTION, $action);
    }

    /**
     * Get fields
     *
     * @return string
     */
    public function getFields()
    {
        return $this->getData(ActionInterface::FIELDS);
    }

    /**
     * Set fields
     *
     * @param $fields
     * @return $this
     */
    public function setFields($fields)
    {
        return $this->setData(ActionInterface::FIELDS, $fields);
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setDynamicRows($fields)
    {
        return $this->setData("dynamic_rows", $this->getDynamicRows());
    }

    /**
     * @return array
     */
    public function getDynamicRows()
    {
        $fields = $this->getFields();
        if (!$fields || $fields === "") {
            return [];
        }
        return unserialize($fields);
    }


    /**
     * Get entity type
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->getData(ActionInterface::ENTITY_TYPE);
    }

    /**
     * Set entity type
     *
     * @param $entityType
     * @return $this
     */
    public function setEntityType($entityType)
    {
        return $this->setData(ActionInterface::ENTITY_TYPE, $entityType);
    }


    /**
     * Get is many
     *
     * @return bool|int
     */
    public function getIsMany()
    {
        return $this->getData(ActionInterface::IS_MANY);
    }

    /**
     * Set is many
     *
     * @param $isMany
     * @return $this
     */
    public function setIsMany($isMany)
    {
        return $this->setData(ActionInterface::IS_MANY, $isMany);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(ActionInterface::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(ActionInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(ActionInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(ActionInterface::UPDATED_AT, $updatedAt);
    }
}
