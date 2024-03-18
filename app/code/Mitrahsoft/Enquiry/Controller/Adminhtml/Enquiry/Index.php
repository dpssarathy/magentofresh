<?php

namespace Mitrahsoft\Enquiry\Controller\Adminhtml\Enquiry;

use Mitrahsoft\Enquiry\Controller\Adminhtml\Enquiry;

class Index extends Enquiry
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
	    $resultPage->getConfig()->getTitle()->prepend(__("Enquiry Items"));
	    return $resultPage;
    }
}
