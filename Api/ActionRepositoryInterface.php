<?php

namespace Junipeer\IntegrationManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Junipeer\IntegrationManager\Api\Data\ActionInterface;

interface ActionRepositoryInterface
{

    /**
     * @param ActionInterface $data
     * @return mixed
     */
    public function save(ActionInterface $data);


    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Junipeer\IntegrationManager\Api\Data\ActionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param ActionInterface $data
     * @return mixed
     */
    public function delete(ActionInterface $data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);


    /**
     * @param $integrationId
     * @return mixed
     */
    public function deleteByIdIntegrationId($integrationId);
}
