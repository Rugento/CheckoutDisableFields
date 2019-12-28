<?php

namespace Rugento\CheckoutDisableFields\Plugin\Checkout\Model\Checkout;

/**
 * Class LayoutProcessor
 * @package Rugento\CheckoutDisableFields\Plugin\Checkout\Model\Checkout
 */
class LayoutProcessor
{
    /**
     * @var \Rugento\CheckoutDisableFields\Helper\Data
     */
    protected $helper;

    /**
     * LayoutProcessor constructor.
     * @param \Rugento\CheckoutDisableFields\Helper\Data $helper
     */
    public function __construct(
        \Rugento\CheckoutDisableFields\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $jsLayout
    ) {
        if ($this->helper->isEnabled()) {
            $data = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
            ['shippingAddress']['children']['shipping-address-fieldset']['children'];

            $configFields = $this->helper->getConfigFields();
            $sortFields = $this->helper->getSortFields();

            foreach ($data as $key => &$addressField) {
                if (isset($configFields[$key]) && is_numeric($configFields[$key])) {
                    if ($configFields[$key] === '1') { //set optional
                        if (isset($data[$key]['required'])) {
                            $data[$key]['required'] = false;
                        }
                        if (isset($data[$key]['children'][0]['validation']['required-entry'])) {
                            $data[$key]['children'][0]['validation']['required-entry'] = false;
                        }
                        if (isset($data[$key]['validation']['required-entry'])) {
                            $data[$key]['validation']['required-entry'] = false;
                        }
                    } elseif ($configFields[$key] === '2') { //remove field
                        unset($data[$key]);
                        continue;
                    }
                }

                if (isset($sortFields[$key]) && is_numeric($sortFields[$key])) {
                    $data[$key]['sortOrder'] = $sortFields[$key];
                }
            }
        }
        return $jsLayout;
    }
}
