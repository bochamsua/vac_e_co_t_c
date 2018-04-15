<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire module install script
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_questionnaire/questionnaire'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Questionnaire ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Content'
    )
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Course'
    )
    ->addColumn(
        'subject_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Subject'
    )
    ->addColumn(
        'instructor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Instructor'
    )
    ->addColumn(
        'number_of_questions',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Number of Questions'
    )
    ->addColumn(
        'number_of_times',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Number of Questionnaires'
    )
    ->addColumn(
        'import_bank',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Import to Question Bank?'
    )
    ->addColumn(
        'input_file',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'File'
    )
    ->addColumn(
        'note',
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
        'Questionnaire Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Questionnaire Creation Time'
    ) 
    ->setComment('Questionnaire Table');
$this->getConnection()->createTable($table);
$this->endSetup();
