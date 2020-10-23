<?php

namespace Junipeer\IntegrationManager\Model\ResourceModel\Integration;

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
        $this->_init('Junipeer\IntegrationManager\Model\Integration', 'Junipeer\IntegrationManager\Model\ResourceModel\Integration');
    }
}
