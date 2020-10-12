<?php
namespace AHT\Featured\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class UpgradeData implements UpgradeDataInterface {
    protected $categorySetupFactory;
    public function __construct(\Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory) {
   	 $this->categorySetupFactory = $categorySetupFactory;
    }
    public function upgrade(
   	 ModuleDataSetupInterface $setup,
   	 ModuleContextInterface $context
    ) {
   	 if (version_compare($context->getVersion(), '2.0.4') < 0) {
   		 $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
   		 $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
   		 $categorySetup->addAttribute($entityTypeId, 'is_featured',
   			 array(
				 'group' => 'Featured',
				 'type' => 'int',
				 'backend' => '',
				 'frontend' => '',
				 'label' => 'Featured Label',
				 'input' => 'boolean',
				 'class' => '',
				 'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
				 'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
				 'visible' => true,
				 'required' => false,
				 'user_defined' => false,
				 'default' => '1',
				 'searchable' => false,
				 'filterable' => false,
				 'comparable' => false,
				 'visible_on_front' => true,
				 'used_in_product_listing' => false,
				 'unique' => false,
				 'apply_to' => 'simple,configurable,virtual,bundle,downloadable'
					

   			 )
   		 );
   	 }
    }
}
