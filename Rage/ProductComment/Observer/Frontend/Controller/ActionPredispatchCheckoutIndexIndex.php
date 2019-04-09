<?php

namespace Rage\ProductComment\Observer\Frontend\Controller;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class ActionPredispatchCheckoutIndexIndex implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(TypeListInterface $typeListInterface, Pool $pool)
    {
        $this->typeListInterface = $typeListInterface;
        $this->pool = $pool;
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
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/product.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Simple Text Log');
        $this->cachePrograme();
        //Your observer code
    }
}
