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

$table = $this->getConnection()
    ->newTable($this->getTable('bs_certificate/crs'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'CRS ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'vaeco_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Staff ID'
    )
    ->addColumn(
        'authorization_number',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Authorization Number'
    )
    ->addColumn(
        'category',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Category'
    )
    ->addColumn(
        'ac_type',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'A/C Type'
    )
    ->addColumn(
        'engine_type',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Engine Type'
    )
    ->addColumn(
        'issue_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Issue Date'
    )
    ->addColumn(
        'expire_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Expire Date'
    )
    ->addColumn(
        'function_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Function'
    )
    ->addColumn(
        'limitation',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Limitation'
    )
    ->addColumn(
        'reason',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Reason'
    )
    ->addColumn(
        'note',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Note'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'CRS Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'CRS Creation Time'
    )
    ->setComment('CRS Table');
$this->getConnection()->createTable($table);

$this->endSetup();
