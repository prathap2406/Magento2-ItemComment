<?php
namespace Rage\ProductComment\Plugin\Magento\Quote\Model\Quote\Item;

use Closure;

class QuoteToOrderItem
{
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        $orderItem = $proceed($item, $additional);
        $orderItem->setProductComment($item->getProductComment());
        return $orderItem;
    }
}
