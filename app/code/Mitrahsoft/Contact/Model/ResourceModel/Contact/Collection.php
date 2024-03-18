<?php

namespace Mitrahsoft\Contact\Model\ResourceModel\Contact;

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
        $this->_init('Mitrahsoft\Contact\Model\Contact', 'Mitrahsoft\Contact\Model\ResourceModel\Contact');
    }
}


