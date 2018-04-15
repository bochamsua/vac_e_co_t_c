<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * StaffInfo module install script
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_staffinfo/training'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Related Training ID'
    )
    ->addColumn(
        'staff_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Staff ID'
    )
    ->addColumn(
        'course',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Course'
    )
    ->addColumn(
        'organization',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Training Organization'
    )
    ->addColumn(
        'training_year',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Year of training'
    )
    ->addColumn(
        'certificate',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Proof of completion'
    )
    ->addColumn(
        'keyword',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Keyword'
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
        'Related Training Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Related Training Creation Time'
    ) 
    ->setComment('Related Training Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_staffinfo/working'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Related Working ID'
    )
    ->addColumn(
        'staff_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Staff ID'
    )
    ->addColumn(
        'division_dept',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Division-Department/center'
    )
    ->addColumn(
        'work',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Work performed'
    )
    ->addColumn(
        'duration',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Duration'
    )
    ->addColumn(
        'keyword',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Keyword'
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
        'Related Working Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Related Working Creation Time'
    ) 
    ->setComment('Related Working Table');
$this->getConnection()->createTable($table);
$this->endSetup();
