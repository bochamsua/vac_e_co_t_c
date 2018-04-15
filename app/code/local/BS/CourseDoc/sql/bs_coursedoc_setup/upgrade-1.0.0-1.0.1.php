<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Register module install script
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 *
 * @var $this Mage_Core_Model_Resource_Setup
 */
$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_coursedoc_coursedoc'),
        'doc_dept',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Department'
        )
    );
$this->getConnection()->addColumn(
        $this->getTable('bs_coursedoc_coursedoc'),
        'doc_inorout',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'In or Out'
        )
    );
$this->getConnection()->addColumn(
        $this->getTable('bs_coursedoc_coursedoc'),
        'doc_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATETIME,
            'comment'   => 'Date'
        )
    );
$this->endSetup();
