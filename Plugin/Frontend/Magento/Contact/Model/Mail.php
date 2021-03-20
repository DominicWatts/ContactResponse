<?php

declare(strict_types=1);

namespace Xigen\ContactResponse\Plugin\Frontend\Magento\Contact\Model;

use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Xigen\ContactResponse\Helper\Email;

class Mail
{
    /**
     * @var Email
     */
    private $helper;

    /**
     * @var ConfigInterface
     */
    private $contactsConfig;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Xigen\ContactResponse\Helper\Email $helper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $contactsConfig
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Email $helper,
        ConfigInterface $contactsConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager
    ) {
        $this->helper = $helper;
        $this->contactsConfig = $contactsConfig;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
    }

    /**
     * @param \Magento\Contact\Model\Mail $subject
     * @param [type] $result
     * @param array $variables
     * @param string $replyTo
     * @return void
     */
    public function afterSend(
        \Magento\Contact\Model\Mail $subject,
        $result,
        $variables,
        $replyTo
    ) {
        if (!$this->helper->getConfirmation()) {
            return;
        }

        /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
        $replyToName = !empty($replyTo['data']['name']) ? $replyTo['data']['name'] : null;
        $replyToEmail = !empty($replyTo['data']['email']) ? $replyTo['data']['email'] : null;

        // $this->contactsConfig->emailSender(); string
        // $this->contactsConfig->emailRecipient(); email
        
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->helper->getConfirmationTemplate())
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($replyTo)
                ->setFromByScope($this->contactsConfig->emailSender())
                ->addTo($replyToEmail)
                ->setReplyTo(
                    $this->contactsConfig->emailRecipient(),
                    $this->contactsConfig->emailSender()
                )
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
        return $result;
    }
}
