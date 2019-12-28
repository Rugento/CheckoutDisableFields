<?php

namespace Rugento\CheckoutDisableFields\Model\Address\Validator;

use Magento\Customer\Model\Address\AbstractAddress;
use Magento\Customer\Model\Address\ValidatorInterface;
use Rugento\CheckoutDisableFields\Helper\Data;

/**
 * Address general fields validator.
 */
class General implements ValidatorInterface
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var \Magento\Directory\Helper\Data
     */
    private $directoryData;

    /**
     * @var array
     */
    private $fieldsConfig;

    /**
     * @param Data $helper
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Directory\Helper\Data $directoryData
     */
    public function __construct(
        \Rugento\CheckoutDisableFields\Helper\Data $helper,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Directory\Helper\Data $directoryData
    ) {
        $this->eavConfig = $eavConfig;
        $this->directoryData = $directoryData;
        $this->helper = $helper;
        $this->fieldsConfig = $helper->getConfigFields();
    }

    /**
     * @param AbstractAddress $address
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function validate(AbstractAddress $address)
    {
        if ($this->helper->isEnabled()) {
            $errors = array_merge(
                $this->checkRequredFieldsDisabled($address),
                $this->checkOptionalFieldsDisabled($address)
            );
        } else {
            $errors = array_merge(
                $this->checkRequredFields($address),
                $this->checkOptionalFields($address)
            );
        }
        return $errors;
    }

    /**
     * @param AbstractAddress $address
     * @return array
     * @throws \Zend_Validate_Exception
     */
    private function checkRequredFieldsDisabled(AbstractAddress $address): array
    {
        $errors = [];
        if ($this->isRequired('firstname') && !\Zend_Validate::is($address->getFirstname(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'firstname']);
        }

        if ($this->isRequired('lastname') && !\Zend_Validate::is($address->getLastname(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'lastname']);
        }

        if ($this->isRequired('street') && !\Zend_Validate::is($address->getStreetLine(1), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'street']);
        }

        if ($this->isRequired('city') && !\Zend_Validate::is($address->getCity(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'city']);
        }

        return $errors;
    }

    /**
     * @param AbstractAddress $address
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    private function checkOptionalFieldsDisabled(AbstractAddress $address): array
    {
        $errors = [];
        if ($this->isRequired('telephone')
            && !\Zend_Validate::is($address->getTelephone(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'telephone']);
        }

        if ($this->isFaxRequired()
            && !\Zend_Validate::is($address->getFax(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'fax']);
        }

        if ($this->isCompanyRequired()
            && !\Zend_Validate::is($address->getCompany(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'company']);
        }

        if ($this->isRequired('postcode')
            && !\Zend_Validate::is($address->getPostcode(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'postcode']);
        }

        return $errors;
    }

    /**
     * @param AbstractAddress $address
     * @return array
     * @throws \Zend_Validate_Exception
     */
    private function checkRequredFields(AbstractAddress $address): array
    {
        $errors = [];
        if (!\Zend_Validate::is($address->getFirstname(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'firstname']);
        }

        if (!\Zend_Validate::is($address->getLastname(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'lastname']);
        }

        if (!\Zend_Validate::is($address->getStreetLine(1), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'street']);
        }

        if (!\Zend_Validate::is($address->getCity(), 'NotEmpty')) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'city']);
        }

        return $errors;
    }

    /**
     * @param AbstractAddress $address
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    private function checkOptionalFields(AbstractAddress $address): array
    {
        $errors = [];
        if ($this->isTelephoneRequired()
            && !\Zend_Validate::is($address->getTelephone(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'telephone']);
        }

        if ($this->isFaxRequired()
            && !\Zend_Validate::is($address->getFax(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'fax']);
        }

        if ($this->isCompanyRequired()
            && !\Zend_Validate::is($address->getCompany(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'company']);
        }

        $havingOptionalZip = $this->directoryData->getCountriesWithOptionalZip();
        if (!in_array($address->getCountryId(), $havingOptionalZip)
            && !\Zend_Validate::is($address->getPostcode(), 'NotEmpty')
        ) {
            $errors[] = __('"%fieldName" is required. Enter and try again.', ['fieldName' => 'postcode']);
        }

        return $errors;
    }

    /**
     * Check if company field required in configuration.
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isCompanyRequired()
    {
        return $this->eavConfig->getAttribute('customer_address', 'company')->getIsRequired();
    }

    /**
     * Check if telephone field required in configuration.
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isTelephoneRequired()
    {
        return $this->eavConfig->getAttribute('customer_address', 'telephone')->getIsRequired();
    }

    /**
     * Check if fax field required in configuration.
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isFaxRequired()
    {
        return $this->eavConfig->getAttribute('customer_address', 'fax')->getIsRequired();
    }

    /**
     * @param $field
     * @return bool
     */
    private function isRequired($field)
    {
        return isset($this->fieldsConfig[$field]) && $this->fieldsConfig[$field] == '0';
    }
}
