<?php

namespace Junipeer\IntegrationManager\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface EventSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \Junipeer\IntegrationManager\Api\Data\EventInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \Junipeer\IntegrationManager\Api\Data\EventInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
