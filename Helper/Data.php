<?php
namespace AHT\Featured\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
        parent::__construct($context);
    }

    public function isEnable()
    {
        return $this->scopeConfig->getValue('featured/featuredpage/is_enabled', ScopeInterface::SCOPE_STORE);
    }
}