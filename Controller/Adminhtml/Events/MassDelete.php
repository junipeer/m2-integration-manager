<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

use Junipeer\IntegrationManager\Model\Event;

class MassDelete extends MassAction
{

    /**
     * @param Event $event
     * @return $this
     */
    protected function massAction(Event $event)
    {
        $this->eventRepository->delete($event);
        return $this;
    }
}
