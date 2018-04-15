<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Bank module install script
 *
 * @category    BS
 * @package     BS_Bank
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_bank/subject'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Subject ID'
    )
    ->addColumn(
        'subject_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Subject Name'
    )
    ->addColumn(
        'subject_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Subject Code'
    )
    ->addColumn(
        'subject_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Sort Order'
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
        'Subject Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Subject Creation Time'
    ) 
    ->setComment('Subject Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_bank/question'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Question ID'
    )
    ->addColumn(
        'subject_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Subject ID'
    )
    ->addColumn(
        'question_question',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Question'
    )
    ->addColumn(
        'curriculum_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Curriculum'
    )
    ->addColumn(
        'sort_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Sort Order'
    )
    ->addColumn(
        'question_usage',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Question Usage'
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
        'Question Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Question Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_bank/subject', array('subject_id')), array('subject_id'))
    ->setComment('Question Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_bank/answer'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Answer ID'
    )
    ->addColumn(
        'answer_answer',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Answer'
    )
    ->addColumn(
        'question_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Question'
    )
    ->addColumn(
        'answer_correct',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Correct'
    )
    ->addColumn(
        'answer_position',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Position'
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
        'Answer Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Answer Creation Time'
    ) 
    ->setComment('Answer Table');
$this->getConnection()->createTable($table);
$this->endSetup();
