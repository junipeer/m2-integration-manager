<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

use Junipeer\IntegrationManager\Model\Integration;

class MassEnable extends MassAction
{
    /**
     * @param Integration $integration
     * @return $this
     */
    protected function massAction(Integration $integration)
    {
        $integration->setIsActive(true);
        $this->integrationRepository->save($integration);
        return $this;
    }
}
