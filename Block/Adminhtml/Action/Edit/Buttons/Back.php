<?php

namespace Junipeer\IntegrationManager\Block\Adminhtml\Action\Edit\Buttons;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Back extends Generic implements ButtonProviderInterface
{

    /** @var ActionRepositoryInterface $integrationRepository */
    protected $actionRepository;

    public function __construct(
        Context $context,
        ActionRepositoryInterface $actionRepository
    ) {
        $this->actionRepository = $actionRepository;

        parent::__construct($context);
    }

    /**
     * Get button attributes
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back button
     *
     * @return string
     */
    public function getBackUrl()
    {
        // in add action we have integration_id
        if ($intId = $this->getIntegrationId()) {
            return $this->getUrl('*/integration/edit', ['id' => $intId, '_current' => true]);
        }


        $id = $this->getDataId();
        if (!$id) {
            return $this->getUrl('*/integration');
        }

        $entity = $this->actionRepository->getById($id);
        if (!$entity->getId() || !$entity->getIntegrationId()) {
            return $this->getUrl('*/integration');
        }

        return $this->getUrl('*/integration/edit', ['id' => $entity->getIntegrationId(), '_current' => true]);
    }
}
