<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * InstructorInfo module install script
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_instructorinfo/otherinfo'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Other Info ID'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Training Description'
    )
    ->addColumn(
        'instructor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Instructor'
    )
    ->addColumn(
        'country',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Country'
    )
    ->addColumn(
        'start_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Start Date'
    )
    ->addColumn(
        'end_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'End Date'
    )
    ->addColumn(
        'cert_info',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Cert.#/Evidence'
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
        'Other Info Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Other Info Creation Time'
    )
    ->setComment('Other Info Table');
$this->getConnection()->createTable($table);

$this->endSetup();
