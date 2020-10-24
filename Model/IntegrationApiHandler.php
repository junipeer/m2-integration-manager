<?php
namespace Junipeer\IntegrationManager\Model;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\ActionInterface;
use Junipeer\IntegrationManager\Api\Data\EventInterface;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterface;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Junipeer\Request\CreateTask;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;

class IntegrationApiHandler
{
    /**
     * @var \Junipeer\IntegrationManager\Model\Manager $manager
     */
    protected $manager;

    /**
     * Data repository
     *
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepository;

    /**
     * Data repository
     *
     * @var ActionRepositoryInterface
     */
    protected $actionRepositoryInterface;

    /**
     * Data repository
     *
     * @var EventRepositoryInterface
     */
    protected $eventRepository;

    /**
     * @var FilterGroupFactory $filterGroupFactory
     */
    protected $filterGroupFactory;

    /**
     * @var FilterBuilder $filterBuilder
     */
    protected $filterBuilder;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * IntegrationApiHandler constructor.
     * @param Manager $manager
     * @param IntegrationRepositoryInterface $integrationRepository
     * @param ActionRepositoryInterface $actionRepositoryInterface
     * @param EventRepositoryInterface $eventRepository
     * @param FilterGroupFactory $filterGroupFactory
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Junipeer\IntegrationManager\Model\Manager $manager,
        IntegrationRepositoryInterface $integrationRepository,
        ActionRepositoryInterface $actionRepositoryInterface,
        EventRepositoryInterface $eventRepository,
        FilterGroupFactory $filterGroupFactory,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->manager = $manager;

        $this->integrationRepository       = $integrationRepository;
        $this->actionRepositoryInterface       = $actionRepositoryInterface;
        $this->eventRepository = $eventRepository;

        $this->filterGroupFactory = $filterGroupFactory;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }


    /**
     * @param $event string
     * @param $orderId int
     * @return bool
     */
    public function handleOrderEvent($event, $orderId) {
        $data = ['order_id' => (int) $orderId];
        $actionEventType = "Order";

        try {
            $this->handleEvent($event, $data, $actionEventType);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * @param string $event
     * @param $customerId
     * @param $email
     * @return bool
     */
    public function handleCustomerEvent($event, $customerId, $email) {
        $data = ['customer_id' => (int) $customerId, 'email' => $email];
        $actionEventType = "Customer";

        try {
            $this->handleEvent($event, $data, $actionEventType);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * @param $event
     * @param $magentoData
     * @param $actionEventType
     * @return bool|\Junipeer\Response\TaskAction[]
     */
    protected function handleEvent($event, $magentoData, $actionEventType)
    {
        try {
            $eventCollection = $this->loadAvailableEvents($event);
        } catch (\Exception $e) {
            return false;
        }

        /**
         * @var $events EventInterface[]
         */
        list($events, $actionsCache,$integrationsCache) = $this->filterOutActions($eventCollection->getItems(), $actionEventType);

        $actionsToRun = [];
        foreach ($events as $event) {

            /** @var ActionInterface $action */
            $action = $actionsCache[$event->getActionId()];

            /** @var IntegrationInterface $integration */
            $integration = $integrationsCache[$event->getIntegrationId()];

            $data = $this->mapActionFields(unserialize($action->getFields()), $magentoData);
            $actionsToRun[] = $this->toJunipeerTask($action->getAction(), $integration->getUserIntegrationId(), $data);
        }

        // Nothing to run...
        if (count($actionsToRun) == 0) {
            return false;
        }

        try {
            $res = $this->manager->runMultipleTasks($actionsToRun);
        } catch (\Exception $e) {
            return false;
        }

        return $res;
    }

    /**
     * @param $action
     * @param $userIntegrationId
     * @param $data
     * @return CreateTask
     */
    protected function toJunipeerTask($action, $userIntegrationId, $data)
    {
        $task = new CreateTask();
        $task->setData((object) $data);
        $task->setAction($action);
        $task->setUserIntegrationId($userIntegrationId);
        return $task;
    }

    /**
     * @param array $fields
     * @param array $data
     * @return array
     */
    protected function mapActionFields(array $fields, array $data) {
        $ret = [];

        foreach ($fields as $field) {
            if (!isset($field['map_type']) || !isset($field['junipeer_field'])) {
                continue;
            }

            if (!in_array($field['map_type'],['from_entity','custom','bool'])) {
                continue;
            }

            $converted = null;
            switch ($field['map_type']) {
                case "from_entity":
                    $converted = $this->convertFromEntityField($field, $data);
                    break;
                case "custom":
                    $converted = $this->convertFromCustomValue($field);
                    break;
                case "bool":
                    $converted = $this->convertFromYesNoValue($field);
                    break;
            }

            if ($converted !== null) {
                // TODO in the future we need to have the fields from junipeer so we can CAST $converted into correct type
                $ret[$field['junipeer_field']] = $converted;
            }
        }

        return $ret;
    }

    /**
     * @param array $field
     * @param array $data
     * @return string|null
     */
    protected function convertFromEntityField(array $field, array $data)
    {
        if (empty($field['map_value'])) {
            return "";
        }

        if (isset($data[$field['map_value']])) {
            return $data[$field['map_value']];
        }

        return "";
    }

    /**
     * @param array $field
     * @return string|null
     */
    protected function convertFromCustomValue(array $field) {
        if (!isset($field['custom_value'])) {
            return "";
        }

        return $field['custom_value'];
    }


    /**
     * @param array $field
     * @return string|null
     */
    protected function convertFromYesNoValue(array $field) {
        if (!isset($field['bool_value'])) {
            return "0";
        }

        return (bool) $field['bool_value'];
    }



    /**
     * @param $events
     * @param $actionEventType
     * @return array
     */
    protected function filterOutActions($events, $actionEventType)
    {
        $integrationCache = [];
        $actionCache = [];
        $eventsToUse = [];

        foreach ($events as $item) {
            if (!isset($integrationCache[$item->getIntegrationId()])) {
                $integration = $this->integrationRepository->getById($item->getIntegrationId());
                if ($integration->getId()) {
                    $integrationCache[$item->getIntegrationId()] = $integration;
                } else {

                    // we skip if we find no integration!
                    continue;
                }
            }

            /** @var IntegrationInterface $integration */
            $integration = $integrationCache[$item->getIntegrationId()];

            // Not active! We don't perform action if integration isn't active!
            if (!$integration->getIsActive()) {
                continue;
            }

            // Now lets check actions

            if (!isset($actionCache[$item->getActionId()])) {
                $action = $this->actionRepositoryInterface->getById($item->getActionId());
                if ($action->getId()) {
                    $actionCache[$item->getActionId()] = $action;
                } else {

                    // we skip if we find no action!
                    continue;
                }

                /** @var ActionInterface $action */
                $action = $actionCache[$item->getActionId()];
                if ($action->getEntityType() !== $actionEventType) {
                    continue;
                }
            }

            $eventsToUse[] = $item;
        }

        return [$eventsToUse, $actionCache, $integrationCache];
    }

    /**
     * @param $event
     * @return \Junipeer\IntegrationManager\Api\Data\EventSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function loadAvailableEvents($event)
    {
        $filters = [$this->filterBuilder->create()->setField("event")->setValue($event)];
        $filterGroup = $this->filterGroupFactory->create();
        $filterGroup->setFilters($filters);


        $search = $this->searchCriteriaBuilder->create();
        $search->setFilterGroups([$filterGroup]);

        return $this->eventRepository->getList($search);
    }
}
