<?php

namespace Junipeer\IntegrationManager\Ui\Component\Form\Element;


use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;

class ParentInput extends \Magento\Ui\Component\Form\Element\Hidden
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;


    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentInterface[] $components
     * @param Registry $registry,
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        Registry $registry,
        array $components = [],
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $components, $data);
    }

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();

        $integrationId = $this->coreRegistry->registry("integration_id");
        $integrationId = $integrationId ? $integrationId : '';

        $config = $this->getData('config');
        if(isset($config['dataScope']) && $config['dataScope'] === 'integration_id'){
            $config['default']= $integrationId;
            $this->setData('config', (array)$config);
        }
    }

}
