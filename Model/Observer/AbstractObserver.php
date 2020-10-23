<?php
namespace Junipeer\IntegrationManager\Model\Observer;

use Junipeer\IntegrationManager\Model\IntegrationApiHandlerFactory;

abstract class AbstractObserver
{
    /**
     * @var $integrationApiHandlerFactory IntegrationApiHandlerFactory
     */
    protected $integrationApiHandlerFactory;

    /**
     * AbstractObserver constructor.
     * @param IntegrationApiHandlerFactory $integrationApiHandlerFactory
     */
    public function __construct(
        IntegrationApiHandlerFactory $integrationApiHandlerFactory
    )
    {
        $this->integrationApiHandlerFactory = $integrationApiHandlerFactory;
    }

}
