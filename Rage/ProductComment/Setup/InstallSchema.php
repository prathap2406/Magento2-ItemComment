<?php
/**
 * MageRef module utilises most of the Magento concepts and serves as a reference.
 * Copyright (C) 2018  Copyright © 2018 Rage Communications Pvt. Ltd. All rights reserved.
 */

namespace Rage\PrpductComment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    protected $installer;
    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $this->installer = $setup;
        $this->installer->startSetup();

        //Add columns to existing cms table
        $this->addColumnsToTable($setup);

        $this->installer->endSetup();
    }

    protected function addColumnsToTable($setup)
    {
        $installer = $setup;
        $table_columns = [
            'product_comment' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default' => 0,
                'comment' => 'product comment'
            ],
        ];

        $connection = $installer->getConnection();

        foreach ($table_columns as $name => $definition) {
            $connection->addColumn($installer->getTable('quote_item'), $name, $definition);
            $connection->addColumn($installer->getTable('sales_order_item'), $name, $definition);
        }
    }
}
