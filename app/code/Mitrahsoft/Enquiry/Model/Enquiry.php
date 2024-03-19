<?php

namespace Mitrahsoft\Enquiry\Model;

use Magento\Framework\Model\AbstractModel;

class Enquiry extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'customer_contactus';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Mitrahsoft\Enquiry\Model\ResourceModel\Enquiry');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

} 