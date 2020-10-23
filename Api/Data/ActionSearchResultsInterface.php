<?php

namespace Junipeer\IntegrationManager\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ActionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \Junipeer\IntegrationManager\Api\Data\ActionInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \Junipeer\IntegrationManager\Api\Data\ActionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
