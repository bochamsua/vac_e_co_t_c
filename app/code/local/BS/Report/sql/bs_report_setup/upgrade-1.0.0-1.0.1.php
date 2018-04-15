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
    ->newTable($this->getTable('bs_report/tctask'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'TC Task ID'
    )
    ->addColumn(
        'tctask_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Task Name'
    )
    ->addColumn(
        'tctask_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Task Code'
    )
    ->addColumn(
        'supervisor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Supervisor'
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
        'TC Task Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'TC Task Creation Time'
    )
    ->setComment('TC Task Table');
$this->getConnection()->createTable($table);
$this->endSetup();
