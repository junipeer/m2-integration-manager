<?php
namespace Junipeer\IntegrationManager\Model;


use Junipeer\Connector\Model\Client\Request\DTO\RunTasksRequest;
use Junipeer\Connector\Model\Client\Api;

class Manager
{
    /**
     * @var Api $api;
     */
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function getIntegrationActionName($userIntegrationId, $action)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        }

        foreach ($integration->getActions() as $act) {
            if ($act->getAction() === $action) {
                return $act->getTitle();
            }
        }

       throw new \Exception("This action wasn't not found, maybe you must restore your integrations");
    }

    public function getIntegrationFields($userIntegrationId, $action)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        }

        foreach ($integration->getActions() as $act) {
            if ($act->getAction() === $action) {
                $ret = [];
                foreach ($act->getFields() as $field) {
                    $ret[$field->getKey()] = $field->getTitle();
                }
                return $ret;
            }
        }

        return [];
    }

    public function getIntegrationName($userIntegrationId)
    {
        try {
            $integrations = $this->api->loadIntegrationNames();
        } catch (\Exception $e) {
            throw $e;
        }

        foreach ($integrations as $integration) {
            if ($integration->getUserIntegrationId() === $userIntegrationId) {
                return $integration->getName();
            }
        }

        throw new \Exception("This integration wasn't not found.");
    }

    public function loadIntegrations()
    {
        try {
            $integrations = $this->api->loadIntegrationNames();
        } catch (\Exception $e) {
            throw $e;
        }

        $ret = [];
        foreach ($integrations as $integration) {
            $ret[$integration->getUserIntegrationId()] = $integration->getName();
        }

        return $ret;
    }

    public function loadIntegrationActions($userIntegrationId)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        }

        $ret = [];
        foreach ($integration->getActions() as $act) {
            $ret[$act->getAction()] = $act->getTitle();
        }

        return $ret;
    }

    /**
     * @param array $tasks
     * @return bool
     * @throws \Exception
     */
    public function runMultipleTasks(array $tasks)
    {
        $req = new RunTasksRequest();
        $req->setTasks($tasks);
        return $this->api->runTasks($req);
    }

}
