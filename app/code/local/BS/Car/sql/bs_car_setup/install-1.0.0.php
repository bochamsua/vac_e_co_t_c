<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * Car module install script
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_car/qacar'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'QA Car ID'
    )
    ->addColumn(
        'car_no',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'No'
    )
    ->addColumn(
        'car_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Date'
    )
    ->addColumn(
        'sendto',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Send To'
    )
    ->addColumn(
        'auditor',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Auditor'
    )
    ->addColumn(
        'auditee',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Auditee representative'
    )
    ->addColumn(
        'ref',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Audit Report Ref'
    )
    ->addColumn(
        'level',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Level'
    )
    ->addColumn(
        'nc',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'NC Cause'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Non-Comformity Description'
    )
    ->addColumn(
        'expire_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Correct Before Date'
    )
    ->addColumn(
        'root_cause',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Identification of root cause'
    )
    ->addColumn(
        'corrective',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Corrective Action'
    )
    ->addColumn(
        'preventive',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Preventive Action'
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
        'QA Car Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'QA Car Creation Time'
    ) 
    ->setComment('QA Car Table');
$this->getConnection()->createTable($table);
$this->endSetup();
