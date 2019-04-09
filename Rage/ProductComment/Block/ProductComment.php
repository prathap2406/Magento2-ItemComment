<?php
namespace Rage\ProductComment\Block;

use Magento\Framework\View\Element\Template;

class ProductComment extends Template
{
    private $quoteItemFactory;
    protected $checkoutSession;
    private $objectManager;

    public function __construct(
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectmanager
    ) {
        $this->_isScopePrivate = true;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->checkoutSession = $checkoutSession;
        $this->objectManager = $objectmanager;
    }

    public function getCheckoutSession($productId = '')
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $quoteId = $objectManager->create('Magento\Checkout\Model\Session')->getQuoteId();
        $comments = '';
        $quote = $this->quoteItemFactory->create()->getCollection()->addFieldToFilter('quote_id', $quoteId)->addFieldToFilter('product_id', $productId);
        foreach ($quote as $item) {
            $comments = $item->getProductComment();
        }
        return $comments;

        //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$quoteId = $objectManager->create('Magento\Checkout\Model\Session')->getQuoteId();
        //$quoteItem = $this->_checkoutSession->getQuote()->getAllVisibleItems();
        //var_dump($quoteItem);
    }
}
