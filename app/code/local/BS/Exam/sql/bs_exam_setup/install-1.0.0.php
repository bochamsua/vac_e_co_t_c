<?php
/**
 * BS_Exam extension
 * 
 * @category       BS
 * @package        BS_Exam
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam module install script
 *
 * @category    BS
 * @package     BS_Exam
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_exam/exam'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Exam ID'
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
        'subject_ids',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Subject'
    )
    ->addColumn(
        'exam_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Exam Date'
    )
    ->addColumn(
        'examiners',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Examiner'
    )
    ->addColumn(
        'exam_note',
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
        'Exam Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Exam Creation Time'
    )
    ->addIndex($this->getIdxName('catalog/product', array('course_id')), array('course_id'))
    ->setComment('Exam Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_exam/question'))
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
        'question_question',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Question'
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
        'question_level',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Level'
    )
    ->addColumn(
        'question_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Sort Order'
    )
    ->addColumn(
        'question_usage',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Usage'
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
    ->addIndex($this->getIdxName('bs_subject/subject', array('subject_id')), array('subject_id'))
    ->setComment('Question Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_exam/answer'))
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
        'question_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Question ID'
    )
    ->addColumn(
        'answer_answer',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Answer'
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
    ->addIndex($this->getIdxName('bs_exam/question', array('question_id')), array('question_id'))
    ->setComment('Answer Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_exam/examresult'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Exam Result ID'
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
        'trainee_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Trainee'
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
        'first_mark',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        '1st Mark'
    )
    ->addColumn(
        'second_mark',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        '2nd Mark'
    )
    ->addColumn(
        'third_mark',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        '3rd Mark'
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
        'Exam Result Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Exam Result Creation Time'
    )
    ->addIndex($this->getIdxName('catalog/product', array('course_id')), array('course_id'))
    ->addIndex($this->getIdxName('bs_subject/subject', array('subject_id')), array('subject_id'))
    ->addIndex($this->getIdxName('bs_trainee/trainee', array('trainee_id')), array('trainee_id'))
    ->setComment('Exam Result Table');
$this->getConnection()->createTable($table);
$this->endSetup();
