<?php
namespace Junipeer\IntegrationManager\Model;

use GuzzleHttp\Exception\GuzzleException;
use Junipeer\AuthInterface;
use Junipeer\BasicAuth;
use Junipeer\IntegrationManager\Helper\Data;
use Junipeer\Request\RunTasksRequest;
use Junipeer\Api;
use Junipeer\TokenAuth;

class Manager
{
    /**
     * @var Api $api;
     */
    protected $api;

    /**
     * @var $helper Data
     */
    protected $helper;

    /**
     * Manager constructor.
     * @param Api $api
     * @param Data $helper
     */
    public function __construct(
        Api $api,
        Data $helper
    ) {
        $this->api = $api;
        $this->helper = $helper;

        // our default flow
        $auth = new BasicAuth();
        $auth->setBasicAuth($helper->getApiUsername(), $helper->getApiPassword());

        $this->setAuth($auth);
    }

    public function setAuth(AuthInterface $auth) {
        $this->api->setAuthentication($auth);
        return $this;
    }

    /**
     * @param $userIntegrationId
     * @param $action
     * @return string
     * @throws \Exception
     */
    public function getIntegrationActionName($userIntegrationId, $action)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        foreach ($integration->getActions() as $act) {
            if ($act->getAction() === $action) {
                return $act->getTitle();
            }
        }

       throw new \Exception("This action wasn't not found, maybe you must restore your integrations");
    }

    /**
     * @param $userIntegrationId
     * @param $action
     * @return array
     * @throws \Exception
     */
    public function getIntegrationFields($userIntegrationId, $action)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
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

    /**
     * @param $userIntegrationId
     * @return string
     * @throws \Exception
     */
    public function getIntegrationName($userIntegrationId)
    {
        try {
            $integrations = $this->api->loadIntegrationNames();
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        foreach ($integrations as $integration) {
            if ($integration->getUserIntegrationId() === $userIntegrationId) {
                return $integration->getName();
            }
        }

        throw new \Exception("This integration wasn't not found.");
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function loadIntegrations()
    {
        try {
            $integrations = $this->api->loadIntegrationNames();
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        $ret = [];
        foreach ($integrations as $integration) {
            $ret[$integration->getUserIntegrationId()] = $integration->getName();
        }

        return $ret;
    }

    /**
     * @param $userIntegrationId
     * @return array
     * @throws \Exception
     */
    public function loadIntegrationActions($userIntegrationId)
    {
        try {
            $integration = $this->api->loadIntegrationById($userIntegrationId);
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        $ret = [];
        foreach ($integration->getActions() as $act) {
            $ret[$act->getAction()] = $act->getTitle();
        }

        return $ret;
    }

    /**
     * @param array $tasks
     * @return \Junipeer\Response\TaskAction[]
     * @throws \Exception
     */
    public function runMultipleTasks(array $tasks)
    {
        $req = new RunTasksRequest();
        $req->setTasks($tasks);
        try {
            return $this->api->runTasks($req);
        } catch (\Exception $e) {
            throw $e;
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }

}
