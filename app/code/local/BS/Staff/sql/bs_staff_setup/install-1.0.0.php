<?php
/**
 * BS_Staff extension
 * 
 * @category       BS
 * @package        BS_Staff
 * @copyright      Copyright (c) 2015
 */
/**
 * Staff module install script
 *
 * @category    BS
 * @package     BS_Staff
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_staff/staff'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Staff ID'
    )
    ->addColumn(
        'username',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'VAECO Username'
    )
    ->addColumn(
        'vaeco_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'VAECO ID'
    )
    ->addColumn(
        'fullname',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Full Name'
    )
    ->addColumn(
        'phone',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Phone'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Position'
    )
    ->addColumn(
        'division',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Division'
    )
    ->addColumn(
        'department',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Department'
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
        'Staff Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Staff Creation Time'
    ) 
    ->setComment('Staff Table');
$this->getConnection()->createTable($table);
$this->endSetup();
