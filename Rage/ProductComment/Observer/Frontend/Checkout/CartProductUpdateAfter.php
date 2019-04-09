<?php
namespace Rage\ProductComment\Observer\Frontend\Checkout;

use Magento\Quote\Model\Quote\ItemFactory;

class CartProductUpdateAfter implements \Magento\Framework\Event\ObserverInterface
{
    protected $_request;
    protected $_itemFactory;
    public function __construct(
        ItemFactory $itemFactory,
        \Magento\Framework\App\RequestInterface $request
        ) {
        $this->_request = $request;
        $this->_itemFactory = $itemFactory;
    }

    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $postdata = $this->_request->getPost();
        $product_comment = $postdata['product_comment'];
        $quoteItem = $observer->getQuoteItem();
        $quoteItme = $quoteItem->getId();
        $quoteItem->setProductComment($product_comment);
        $quoteItem->save();
        return;

        $order = $this->_itemFactory->create()->getCollection()->addFieldToFilter('item_id', $itemId);
        foreach ($order as $items) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/logfile.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info($items->getId());

            $order = $items->setProductComment($product_comment);
        }
        $order->save();
        return;
    }
}
