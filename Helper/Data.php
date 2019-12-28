<?php
namespace Rugento\CheckoutDisableFields\Helper;

/**
 * Class Data
 * @package Rugento\CheckoutDisableFields\Helper
 */
class Data
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getConfigFields()
    {
        $config = $this->_scopeConfig->getValue('checkout_disable_fields/fields');
        if (isset($config['region_id'])) {
            $config['region'] = $config['region_id'];
        }
        return $config;
    }

    /**
     * @return mixed
     */
    public function getSortFields()
    {
        return $this->_scopeConfig->getValue('checkout_disable_fields/sort_order');
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->_scopeConfig->getValue('checkout_disable_fields/general/enable');
    }
}