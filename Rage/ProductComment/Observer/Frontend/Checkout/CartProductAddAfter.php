<?php
namespace Rage\ProductComment\Observer\Frontend\Checkout;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\Quote\ItemFactory;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class CartProductAddAfter implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    private $_checkoutSession;
    protected $_itemFactory;

    public function __construct(
        TypeListInterface $typeListInterface,
        Pool $pool,
        CheckoutSession $checkoutSession,
        ItemFactory $itemFactory,
        \Magento\Framework\App\RequestInterface $request
        ) {
        $this->_request = $request;
        $this->_itemFactory = $itemFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->typeListInterface = $typeListInterface;
        $this->pool = $pool;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $product = $observer->getEvent()->getDataByKey('product');
        $item = $this->_checkoutSession->getQuote()->getItemByProduct($product);
        $itemId = $item->getId();
        $postdata = $this->_request->getPost();
        $product_comment = $postdata['product_comment'];
        // set product comment
        $order = $this->_itemFactory->create()->getCollection()->addFieldToFilter('item_id', $itemId);
        foreach ($order as $items) {
            $order = $items->setProductComment($product_comment);
        }
        $this->cachePrograme();
        $order->save();
    }

    public function cachePrograme()
    {
        $_cacheTypeList = $this->typeListInterface;

        $_cacheFrontendPool = $this->pool;

        $types = array('full_page');

        foreach ($types as $type) {
            $_cacheTypeList->cleanType($type);
        }
        foreach ($_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
