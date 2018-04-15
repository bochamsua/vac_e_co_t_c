<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * KST module install script
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_kst/tfeedback'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Trainee Feedback ID'
    )
    ->addColumn(
        'content',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'content'
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
        'Trainee Feedback Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Trainee Feedback Creation Time'
    )
    ->setComment('Trainee Feedback Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_kst/ifeedback'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Instructor Feedback ID'
    )
    ->addColumn(
        'task_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Task'
    )
    ->addColumn(
        'trainee_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Trainee'
    )
    ->addColumn(
        'criteria_one',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria one'
    )
    ->addColumn(
        'criteria_two',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria two'
    )
    ->addColumn(
        'criteria_three',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria Three'
    )
    ->addColumn(
        'criteria_four',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria Four'
    )
    ->addColumn(
        'criteria_five',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria Five'
    )
    ->addColumn(
        'criteria_six',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Criteria Six'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
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
        'Instructor Feedback Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Instructor Feedback Creation Time'
    )
    ->setComment('Instructor Feedback Table');
$this->getConnection()->createTable($table);


$this->endSetup();
