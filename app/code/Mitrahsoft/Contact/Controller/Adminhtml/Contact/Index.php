<?php

namespace Mitrahsoft\Contact\Controller\Adminhtml\Contact;

use Mitrahsoft\Contact\Controller\Adminhtml\Contact;

class Index extends Contact
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
	    $resultPage->getConfig()->getTitle()->prepend(__("Contact Us"));
	    return $resultPage;
    }
}
