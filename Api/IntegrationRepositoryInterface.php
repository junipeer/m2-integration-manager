<?php

namespace Junipeer\IntegrationManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterface;

interface IntegrationRepositoryInterface
{

    /**
     * @param IntegrationInterface $data
     * @return mixed
     */
    public function save(IntegrationInterface $data);


    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Junipeer\IntegrationManager\Api\Data\IntegrationSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param IntegrationInterface $data
     * @return mixed
     */
    public function delete(IntegrationInterface $data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);
}
