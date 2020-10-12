<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace AHT\Featured\Controller\Adminhtml\Index;


use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Builder;

/**
 * Class \Magento\Catalog\Controller\Adminhtml\Product\MassDelete
 */
class Delete extends \Magento\Catalog\Controller\Adminhtml\Product implements HttpPostActionInterface
{
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param Builder $productBuilder
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Builder $productBuilder,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ProductRepositoryInterface $productRepository = null,
        LoggerInterface $logger = null
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository ?:
            \Magento\Framework\App\ObjectManager::getInstance()->create(ProductRepositoryInterface::class);
        $this->logger = $logger ?:
            \Magento\Framework\App\ObjectManager::getInstance()->create(LoggerInterface::class);
        parent::__construct($context, $productBuilder);
    }

    /**
     * Mass Delete Action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productDeleted = 0;
        $productDeletedError = 0;
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection->getItems() as $product) {
            try {
                $product_test = $this->productRepository->getById($product["entity_id"]);
                $productDeleted++;
                $featuredAttributeCode = "is_featured";
                $featuredAttributeValue = 0;
                $product_test->addAttributeUpdate($featuredAttributeCode, $featuredAttributeValue , 0);
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
                $productDeletedError++;
            }
        }
        if ($productDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $productDeleted)
            );
        }

        if ($productDeletedError) {
            $this->messageManager->addErrorMessage(
                __(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $productDeletedError
                )
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('featured/*/index');
    }
}