<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Report module install script
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_report/report'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Individual Report ID'
    )
    ->addColumn(
        'brief',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Brief'
    )
    ->addColumn(
        'detail',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Detail'
    )
    ->addColumn(
        'user_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'User Id'
    )
    ->addColumn(
        'supervisor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Supervisor Id'
    )
    ->addColumn(
        'complete',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Complete (%)'
    )
    ->addColumn(
        'expected_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Expected Complete Date'
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
        'Individual Report Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Individual Report Creation Time'
    ) 
    ->setComment('Individual Report Table');
$this->getConnection()->createTable($table);
/*$table = $this->getConnection()
    ->newTable($this->getTable('bs_report/manage'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Manage ID'
    )
    ->addColumn(
        'manage',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'manage'
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
        'Manage Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Manage Creation Time'
    ) 
    ->setComment('Manage Table');
$this->getConnection()->createTable($table);*/
$this->endSetup();
