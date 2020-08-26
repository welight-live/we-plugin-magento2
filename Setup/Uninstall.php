<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UOL\welight\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Used to uninstall all of the data of the WE_welight module from the Magento
 */
class Uninstall implements UninstallInterface
{
    /**
     * Called when run the command: php bin/magento module:uninstall WE_welight
     * to uninstall WE_welight data from the DB
     * @param Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param Magento\Framework\Setup\ModuleContextInterface $context
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $statuses = [
            'welight_iniciado',
            'welight_aguardando_pagamento',
            'welight_cancelada',
            'welight_chargeback_debitado',
            'welight_devolvida',
            'welight_disponivel',
            'welight_em_analise',
            'welight_em_contestacao',
            'welight_em_disputa',
            'welight_paga'
        ];
        
        $paths = [
            'welight/store/reference',
            'payment/welight/active',
            'payment/welight/title',
            'payment/welight/email',
            'payment/welight/token',
            'payment/welight/redirect',
            'payment/welight/notification',
            'payment/welight/charset',
            'payment/welight/log',
            'payment/welight/log_file',
            'payment/welight/checkout',
            'payment/welight/environment',
            'payment/welight/abandoned_active'
        ];

        $setup->startSetup();
        $this->dropwelightOrdersTable($setup);
        $this->dropColumnsFromSalesOrderGrid($setup);
        $this->removeDataFromSalesOrderStatus($setup, $statuses);
        $this->removeDataFromSalesOrderStatusState($setup, $statuses);
        $this->removeDataFromCoreConfigData($setup, $paths);
        $setup->endSetup();
    }

    private function dropwelightOrdersTable($setup) 
    {
        $setup->getConnection()->dropTable($setup->getTable('welight_orders'));
    }
    
    private function dropColumnsFromSalesOrderGrid($setup)
    {
        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order_grid'),
            'transaction_code'
        );
        
        $setup->getConnection()->dropColumn(
            $setup->getTable('sales_order_grid'),
            'environment'
        );
    }
    //sales_order_status
    private function removeDataFromSalesOrderStatus($setup, $statuses)
    {
        foreach ($statuses as $status) {
            $setup->getConnection()
                ->delete($setup->getTable('sales_order_status'), "status='$status'");
        }
    }
    //sales_order_status_state
    private function removeDataFromSalesOrderStatusState($setup, $statuses)
    {
        foreach ($statuses as $status) {
            $setup->getConnection()
                ->delete($setup->getTable('sales_order_status_state'), "status='$status'");
        }
    }
    //core_config_data
    private function removeDataFromCoreConfigData($setup, $paths)
    {
        foreach ($paths as $path) {
            $setup->getConnection()
                ->delete($setup->getTable('core_config_data'), "path='$path'");
        }
    }
    
    private function removeModuleSetup($setup)
    {
        $setup->getConnection()
            ->delete($setup->getTable('setup_module'), "module='WE_welight'");
    }
}