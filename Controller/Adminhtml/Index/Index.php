<?php
namespace AHT\Featured\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
   	 parent::__construct($context);
   	 $this->resultPageFactory = $resultPageFactory;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('AHT_Featured::featured');
    }

    public function execute()
    { 
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('AHT_Featured::featured');
        $resultPage->getConfig()->getTitle()->prepend(__('Product Featured'));
        return $resultPage;
    }
}