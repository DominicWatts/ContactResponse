<?php


namespace Xigen\ContactResponse\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Xigen ContactResponse Email helper class
 */
class Email extends AbstractHelper
{
    const XML_PATH_EMAIL_CONFIRM = 'contact/email/contact_confirmation';
    const XML_PATH_EMAIL_CONFIRM_TEMPLATE = 'contact/email/contact_confirmation_template';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Email constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfirmation()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_CONFIRM, $this->storeManager->getStore()->getId());
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfirmationTemplate()
    {
        return $this->getConfigValue(self::XML_PATH_EMAIL_CONFIRM_TEMPLATE, $this->storeManager->getStore()->getId());
    }

    /**
     * @param $xmlPath
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($xmlPath, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $xmlPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
