<?php
namespace Junipeer\IntegrationManager\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_CONNECTION = 'junipeer_integrationmanager/connection/';

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     *
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }


    public function getApiUsername($store = null) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONNECTION.'public_api_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getApiPassword($store = null) {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONNECTION.'private_api_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }


    public function isEnabled($store = null) {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_CONNECTION.'enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }


}
