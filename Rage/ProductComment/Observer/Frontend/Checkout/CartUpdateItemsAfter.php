<?php
namespace Rage\ProductComment\Observer\Frontend\Checkout;

class CartUpdateItemsAfter implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        array $data = []
    ) {
        $this->_request = $request;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $postdata = $this->_request->getPost();
        $priceoption = $postdata['product_comment'];
        $item = $observer->getEvent()->getData('quote_item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);
        $price = $priceoption; //set your price here
        $item->setCustomPrice($price);
        $item->setOriginalCustomPrice($price);
        $item->getProduct()->setIsSuperMode(true);
    }
}
