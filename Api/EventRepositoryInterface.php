<?php

namespace Junipeer\IntegrationManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Junipeer\IntegrationManager\Api\Data\EventInterface;

interface EventRepositoryInterface
{

    /**
     * @param EventInterface $data
     * @return mixed
     */
    public function save(EventInterface $data);


    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Junipeer\IntegrationManager\Api\Data\EventSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param EventInterface $data
     * @return mixed
     */
    public function delete(EventInterface $data);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);


    /**
     * @param $integrationId
     * @return mixed
     */
    public function deleteByIdActionId($integrationId);

    /**
     * @param $integrationId
     * @return mixed
     */
    public function deleteByIdIntegrationId($integrationId);
}
