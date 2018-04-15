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
    ->newTable($this->getTable('bs_instructorinfo/info'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Info ID'
    )
    ->addColumn(
        'instructor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Instructor'
    )
    ->addColumn(
        'compliance_with',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Compliance With'
    )
    ->addColumn(
        'approved_course',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Approved Course'
    )
    ->addColumn(
        'approved_function',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Approved Function'
    )
    ->addColumn(
        'approved_doc',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Approved Doc'
    )
    ->addColumn(
        'approved_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Approved Date'
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
        'Info Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Info Creation Time'
    ) 
    ->setComment('Info Table');
$this->getConnection()->createTable($table);
$this->endSetup();
