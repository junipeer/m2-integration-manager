<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

use Junipeer\IntegrationManager\Model\Integration;

class MassDisable extends MassAction
{
    /**
     * @param Integration $integration
     * @return $this
     */
    protected function massAction(Integration $integration)
    {
        $integration->setIsActive(false);
        $this->integrationRepository->save($integration);
        return $this;
    }
}
