<?php

namespace Mitrahsoft\Helloworld\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        
        /**
         * run this code if the module version stored in database is less than 1.0.1
         * i.e. the code is run while upgrading the module from version 1.0.0 to 1.0.1
         * 
         * you can write the version_compare function in the following way as well:
         * if(version_compare($context->getVersion(), '1.0.1', '<')) { 
         * 
         * the syntax is only different
         * output is the same
         */ 
        if (version_compare($context->getVersion(), '1.0.1') < 0) { 

            $attributeCode = 'phone_number';

            $customerSetup->addAttribute(
                \Magento\Customer\Model\Customer::ENTITY, 
                $attributeCode, 
                [

                    'type' => 'varchar',
     
                    'label' => 'Phone Number',
     
                    'input' => 'text',
     
                    'required' => 1,
     
                    'visible' => 1,
     
                    'user_defined' => 1,
     
                    'sort_order' => 999,
     
                    'position' => 999,
     
                    'system' => 0
     
                ]
            );

            // show the attribute in the following forms
            $attribute = $customerSetup
                            ->getEavConfig()
                            ->getAttribute(
                                \Magento\Customer\Model\Customer::ENTITY, 
                                $attributeCode
                            )
                            ->addData(
                                ['used_in_forms' => [
                                    'adminhtml_customer',
                                    'adminhtml_checkout',
                                    'customer_account_create',
                                    'customer_account_edit'
                                ]
                            ]);

            $attribute->save();
        }

        if(version_compare($context->getVersion(), '1.5.0', '<')) { 
            
            $attributeCode = 'phone_number';
            
            // add/update frontend_model to the attribute
            $customerSetup->updateAttribute(
                \Magento\Customer\Model\Customer::ENTITY, // customer entity code
                $attributeCode,
                'frontend_model',
                \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class
            );

          
        }

        if(version_compare($context->getVersion(), '1.5.0', '<')) { 

            $attributeCode = 'phone_number';

            // remove customer attribute
            $customerSetup->removeAttribute(
                \Magento\Customer\Model\Customer::ENTITY,
                $attributeCode // attribute code to remove
            );
        }

        $setup->endSetup();
    }
}