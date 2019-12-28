<?php

namespace Rugento\CheckoutDisableFields\Model\Adminhtml\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Rugento\CheckoutDisableFields\Model\Adminhtml\Source
 */
class Status implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '0', 'label' => __('Show')],
            ['value' => '1', 'label' => __('Show, Optional')],
            ['value' => '2', 'label' => __('Hidden')],
        ];
    }
}
