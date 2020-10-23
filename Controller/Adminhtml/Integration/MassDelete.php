<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

use Junipeer\IntegrationManager\Model\Integration;

class MassDelete extends MassAction
{
    /**
     * @param Integration $integration
     * @return $this
     */
    protected function massAction(Integration $integration)
    {
        $integrationId = $integration->getId();
        $this->integrationRepository->delete($integration);
        $this->actionRepositoryInterface->deleteByIdIntegrationId($integrationId);
        $this->eventRepository->deleteByIdIntegrationId($integrationId);
        return $this;
    }
}
