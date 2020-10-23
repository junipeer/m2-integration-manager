<?php

namespace Junipeer\IntegrationManager\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface IntegrationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \Junipeer\IntegrationManager\Api\Data\IntegrationInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \Junipeer\IntegrationManager\Api\Data\IntegrationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
