<?php

namespace Junipeer\IntegrationManager\Model;

use Exception;
use Junipeer\IntegrationManager\Api\Data\EventInterface;
use Junipeer\IntegrationManager\Api\Data\EventSearchResultsInterface;
use Junipeer\IntegrationManager\Api\Data\EventSearchResultsInterfaceFactory;
use Junipeer\IntegrationManager\Model\ResourceModel\Event\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Exception\NoSuchEntityException;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\EventInterfaceFactory;
use Junipeer\IntegrationManager\Model\ResourceModel\Event as ResourceData;
use Junipeer\IntegrationManager\Model\ResourceModel\Event\CollectionFactory as EventCollectionFactory;
use Magento\Framework\Model\AbstractModel;

class EventRepository implements EventRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    /**
     * @var ResourceData
     */
    protected $resource;

    /**
     * @var EventCollectionFactory
     */
    protected $eventCollectionFactory;

    /**
     * @var EventSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var EventInterfaceFactory
     */
    protected $dataInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        ResourceData $resource,
        EventCollectionFactory $eventCollectionFactory,
        EventSearchResultsInterfaceFactory $searchResultsInterfaceFactory,
        EventInterfaceFactory $dataInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->eventCollectionFactory = $eventCollectionFactory;
        $this->searchResultsFactory = $searchResultsInterfaceFactory;
        $this->dataInterfaceFactory = $dataInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param EventInterface $data
     * @return EventInterface
     * @throws CouldNotSaveException
     */
    public function save(EventInterface $data)
    {
        try {
            /** @var EventInterface|AbstractModel $data */
            $this->resource->save($data);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $data;
    }

    /**
     * Get data record
     *
     * @param $dataId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($dataId)
    {
        if (!isset($this->instances[$dataId])) {
            /** @var EventInterface|AbstractModel $data */
            $data = $this->dataInterfaceFactory->create();
            $this->resource->load($data, $dataId);
            if (!$data->getId()) {
                throw new NoSuchEntityException(__('Requested data doesn\'t exist'));
            }
            $this->instances[$dataId] = $data;
        }
        return $this->instances[$dataId];
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return EventSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var EventSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var Collection $collection */
        $collection = $this->eventCollectionFactory->create();

        //Add filters from root filter group to the collection
        /** @var FilterGroup $group */
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }
        $sortOrders = $searchCriteria->getSortOrders();
        /** @var SortOrder $sortOrder */
        if ($sortOrders) {
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $field = $sortOrder->getField();
                if (!$field) {
                   continue;
                }
                $collection->addOrder(
                    $field,
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        } else {
            $field = 'id';
            $collection->addOrder($field, 'ASC');
        }


        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $data = [];
        foreach ($collection as $datum) {
            $dataDataObject = $this->dataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $datum->getData(), EventInterface::class);
            $data[] = $dataDataObject;
        }

        $searchResults->setTotalCount($collection->getSize());
        return $searchResults->setItems($data);
    }

    protected function addFilterGroupToCollection(FilterGroup $filterGroup, \Junipeer\IntegrationManager\Model\ResourceModel\Event\Collection $collection)
    {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $fields[] = $filter->getField();
            $conditions[] = [$condition => $filter->getValue()];
        }
        if ($fields) {
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param EventInterface $data
     * @return bool
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function delete(EventInterface $data)
    {
        /** @var EventInterface|AbstractModel $data */
        $id = $data->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($data);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * @param $dataId
     * @return bool
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($dataId)
    {
        $data = $this->getById($dataId);
        return $this->delete($data);
    }

    /**
     * @param $integrationId
     * @return bool|mixed
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function deleteByIdIntegrationId($integrationId)
    {
        $collection = $this->eventCollectionFactory->create();
        $collection->addFieldToFilter(['integration_id'], [['eq' => $integrationId]]);
        foreach ($collection->getItems() as $item) {
            $dataDataObject = $this->dataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $item->getData(), EventInterface::class);
            $this->delete($dataDataObject);
        }

        return true;
    }

    /**
     * @param $actionId
     * @return bool|mixed
     * @throws CouldNotSaveException
     * @throws StateException
     */
    public function deleteByIdActionId($actionId)
    {
        $collection = $this->eventCollectionFactory->create();
        $collection->addFieldToFilter(['action_id'], [['eq' => $actionId]]);
        foreach ($collection->getItems() as $item) {
            $dataDataObject = $this->dataInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($dataDataObject, $item->getData(), EventInterface::class);
            $this->delete($dataDataObject);
        }

        return true;
    }
}
