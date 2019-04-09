<?php
namespace Rage\ProductComment\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $quoteItemFactory;
    protected $checkoutSession;
    private $objectManager;

    public function __construct(
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    ) {
        $this->quoteItemFactory = $quoteItemFactory;
        $this->checkoutSession = $checkoutSession;
        $this->objectManager = $objectmanager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        $this->_isScopePrivate = true;
    }

    public function getHelp(){
        $help = "this is helper file";
        return $help;
    }

    public function getItem($itemId = '')
    {
        $quote = $this->quoteItemFactory->create()->getCollection()->addFieldToFilter('item_id', $itemId);
        foreach ($quote as $item) {
            $comments = $item->getProductComment();
        }
        return $comments;
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
