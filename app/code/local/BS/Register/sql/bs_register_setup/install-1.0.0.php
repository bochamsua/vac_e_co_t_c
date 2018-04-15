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
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_register/attendance'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Attendance ID'
    )
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Course'
    )
    ->addColumn(
        'trainee_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Trainee'
    )
    ->addColumn(
        'att_start_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'Date Start'
    )
    ->addColumn(
        'att_start_time',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Time Start'
    )
    ->addColumn(
        'att_finish_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'Date End'
    )
    ->addColumn(
        'att_finish_time',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Time End'
    )
    ->addColumn(
        'att_excuse',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Excuse'
    )
    ->addColumn(
        'att_note',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
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
        'Attendance Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Attendance Creation Time'
    )
    ->addIndex($this->getIdxName('catalog/product', array('course_id')), array('course_id'))
    ->addIndex($this->getIdxName('bs_trainee/trainee', array('trainee_id')), array('trainee_id'))
    ->setComment('Attendance Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_register/schedule'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Course Schedule ID'
    )
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Course'
    )
    ->addColumn(
        'subject_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Subject'
    )
    ->addColumn(
        'instructor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Instructor'
    )
    ->addColumn(
        'room_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Room'
    )
    ->addColumn(
        'schedule_start_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'Start Date'
    )
    ->addColumn(
        'schedule_start_time',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Start Time'
    )
    ->addColumn(
        'schedule_finish_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(
            'nullable'  => false,
        ),
        'Finish Date'
    )
    ->addColumn(
        'schedule_finish_time',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Finish Time'
    )
    ->addColumn(
        'schedule_note',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
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
        'Course Schedule Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Course Schedule Creation Time'
    )
    ->addIndex($this->getIdxName('catalog/product', array('course_id')), array('course_id'))
    ->addIndex($this->getIdxName('bs_subject/subject', array('subject_id')), array('subject_id'))
    ->addIndex($this->getIdxName('bs_instructor/instructor', array('instructor_id')), array('instructor_id'))
    ->addIndex($this->getIdxName('bs_logistics/classroom', array('room_id')), array('room_id'))
    ->setComment('Course Schedule Table');
$this->getConnection()->createTable($table);
$this->endSetup();
