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
        $this->getTable('bs_curriculumdoc_curriculumdoc'),
        'cdoc_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'Approved/Revised Date'
        )
    );
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_curriculumdoc_curriculumdoc'),
        'cdoc_page',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Number of Page'
        )
    );

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_curriculumdoc_curriculumdoc'),
        'cdoc_amm',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
            'comment'   => 'Approved/Revised Date'
        )
    );

    ;
$this->endSetup();
