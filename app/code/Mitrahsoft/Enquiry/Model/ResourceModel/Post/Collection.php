<?php

namespace Mitrahsoft\Enquiry\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = 'id';

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('Mitrahsoft\Enquiry\Model\Post', 'Mitrahsoft\Enquiry\Model\ResourceModel\Post');
    }
}


