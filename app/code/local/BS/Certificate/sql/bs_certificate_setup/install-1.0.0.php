<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Certificate module install script
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_certificate/certificate'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Certificate ID'
    )
    ->addColumn(
        'staff_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Staff Name'
    )
    ->addColumn(
        'vaeco_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'VAECO ID'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Description'
    )
    ->addColumn(
        'apply_for',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Apply For'
    )
    ->addColumn(
        'cert_no',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Cert Number'
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
        'Certificate Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Certificate Creation Time'
    ) 
    ->setComment('Certificate Table');
$this->getConnection()->createTable($table);
$this->endSetup();
