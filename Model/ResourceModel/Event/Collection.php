<?php

namespace Junipeer\IntegrationManager\Model\ResourceModel\Event;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = 'id';

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Junipeer\IntegrationManager\Model\Event', 'Junipeer\IntegrationManager\Model\ResourceModel\Event');
    }
}
