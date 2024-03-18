<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mitrahsoft\Contact\Controller\Index;
use Magento\Contact\Model\ConfigInterface;
use Magento\Contact\Model\MailInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;

class Post extends \Magento\Contact\Controller\Index\Post
{  
    const ADMIN_CONTACT_CONFIG = 'contactus/admin_notification_email/enabled';
    const ADMIN_CONTACT_SENDER = 'contactus/admin_notification_email/sender';
    const ADMIN_CONTACT_RECIPIENT = 'contactus/admin_notification_email/sendto';
    const ADMIN_CONTACT_TEMPLATE = 'contactus/admin_notification_email/template';

    const CUSTOMER_CONTACT_CONFIG = 'contactus/customer_notification_email/enabled';
    const CUSTOMER_CONTACT_SENDER = 'contactus/customer_notification_email/sender';
    const CUSTOMER_CONTACT_TEMPLATE = 'contactus/customer_notification_email/template';


    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null,
        \Mitrahsoft\Contact\Model\ContactFactory $contactFactory,
        TransportBuilder $transportBuilder, 
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
        
    ) {
        parent::__construct($context, $contactsConfig, $mail, $dataPersistor, $logger); 
        $this->contactFactory = $contactFactory;      
        $this->transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->dataPersistor = $dataPersistor;
        $this->_storeManager = $storeManager;
    }

    public function execute()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        try {
            $post = $this->getRequest()->getPost(); 
            $input = array( 'name' => $post['name'],                           
                            'email' => $post['email'],
                            'phone' => $post['telephone'],
                            'message' => $post['comment'],
                            'status' => 1,
                            'createdon' => time());  
 
            $postData = $this->contactFactory->create();
            $postData->addData($input)->save();
            $mail['name'] = $post['name'];
            $mail['email'] = $post['email'];
            $mail['phone'] = $post['telephone'];
            $mail['message'] = $post['comment'];
            // sending notification emails to admin and customer
            $this->adminNotify($mail);
            $this->customerNotify($mail);
           
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        }
        return $this->resultRedirectFactory->create()->setPath('contact/index');
    }
    public function getModuleConfig($path) {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue($path, $storeScope);
    }

    public function adminNotify($data){
        if($this->getModuleConfig(self::ADMIN_CONTACT_CONFIG)){
            $emailTemplate = $this->getModuleConfig(self::ADMIN_CONTACT_TEMPLATE);
            $sender = $this->getModuleConfig(self::ADMIN_CONTACT_SENDER);
            $sendTo = $this->getModuleConfig(self::ADMIN_CONTACT_RECIPIENT);
        
            $this->sendEmail($data, $emailTemplate, $sender, $sendTo);
            }
        
    }

    public function customerNotify($data){
        if($this->getModuleConfig(self::CUSTOMER_CONTACT_CONFIG)){
            $emailTemplate = $this->getModuleConfig(self::CUSTOMER_CONTACT_TEMPLATE);
            $sender = $this->getModuleConfig(self::CUSTOMER_CONTACT_SENDER);
            $sendTo = $data['email'];   
       
            $this->sendEmail($data, $emailTemplate, $sender, $sendTo);
            }
    }

    public function sendEmail($data, $emailTemplate, $sender, $sendTo){
        
        try {
            $storeId = $this->_storeManager->getStore()->getId();
            $transport = $this->transportBuilder
                              ->setTemplateIdentifier($emailTemplate)
                              ->setTemplateOptions([
                                    'area' => Area::AREA_FRONTEND,
                                    'store' => $storeId,
                                ])
                              ->setTemplateVars($data)                
                              ->setFrom($sender)
                              ->addTo($sendTo)
                              ->getTransport();
            $transport->sendMessage();

            return true;
        } catch (Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

        return false;
    }
    
    
}
