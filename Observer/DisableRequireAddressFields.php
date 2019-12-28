<?php

namespace Rugento\CheckoutDisableFields\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class DisableRequireAddressFields
 * @package Rugento\CheckoutDisableFields\Observer
 */
class DisableRequireAddressFields implements ObserverInterface
{
    /**
     * @var \Rugento\CheckoutDisableFields\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * DisableRequireAddressFields constructor.
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Rugento\CheckoutDisableFields\Helper\Data $helper
     */
    public function __construct(
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        \Magento\Eav\Model\Config $eavConfig,
        \Rugento\CheckoutDisableFields\Helper\Data $helper
    ) {
        $this->helper = $helper;
        $this->eavConfig = $eavConfig;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        foreach ($this->helper->getConfigFields() as $key => $value) {
            try {
                if ($value === '0') {
                    $attribute = $this->eavConfig->getAttribute('customer_address', $key)->setIsRequired(true);
                } else {
                    $attribute = $this->eavConfig->getAttribute('customer_address', $key)->setIsRequired(false);
                }
                $this->attributeRepository->save($attribute);
            } catch (\Exception $exception) {
            }
        }
    }
}
