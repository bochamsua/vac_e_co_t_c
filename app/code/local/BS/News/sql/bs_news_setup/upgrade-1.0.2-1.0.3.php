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
    ->newTable($this->getTable('bs_news/user'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'User ID'
    )
    ->addColumn(
        'news_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'News'
    )
    ->addColumn(
        'users_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'User'
    )
    ->addColumn(
        'mark_read',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Read?'
    )
    ->addColumn(
        'read_time',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Read Time'
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
        'User Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'User Creation Time'
    )
    ->setComment('User Table');
$this->getConnection()->createTable($table);

$this->endSetup();
