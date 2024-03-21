<?php

namespace Mitrahsoft\Enquiry\Model;

use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'mitrahsoft_post';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Mitrahsoft\Enquiry\Model\ResourceModel\Post');
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