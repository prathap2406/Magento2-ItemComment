<?php
namespace Rage\ProductComment\Plugin\Magento\Quote\Model\Cart\Totals;

use Magento\Quote\Api\Data\TotalsItemExtensionFactory;

class ItemConverter
{
    protected $totalsItemExtensionFactory;

    public function __construct(
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        \Magento\Checkout\Model\Cart $cart,
        TotalsItemExtensionFactory $totalsItemExtensionFactory
    ) {
        $this->quoteItemFactory = $quoteItemFactory;
        $this->_cart = $cart;
        $this->totalsItemExtensionFactory = $totalsItemExtensionFactory;
    }

    public function afterModelToDataObject(
        \Magento\Quote\Model\Cart\Totals\ItemConverter $subject,
        $result
    ) {
        $extensionAttributes = $result->getExtensionAttributes();
        $itemid = $result->getItemId();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->totalsItemExtensionFactory->create();
        }

        $quote = $this->_cart->getQuote()->getAllVisibleItems();
        foreach ($quote as $qu) {
            $quote = $this->quoteItemFactory->create()->getCollection()->addFieldToFilter('item_id', $qu->getId());
            foreach ($quote as $item) {
                $itemMessage = $item->getProductComment();
                $msg = empty($itemMessage)?'':"$itemMessage";
                $message[$qu->getId()] = $msg;
            }
        }
        if (!empty($message)) {
            $extensionAttributes->setCmessage($message[$itemid]);
        }

        $result->setExtensionAttributes($extensionAttributes);
        return $result;
    }
}
