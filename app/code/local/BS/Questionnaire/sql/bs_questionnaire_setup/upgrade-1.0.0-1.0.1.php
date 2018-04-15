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

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_questionnaire/questionnaire'),
        'question_time',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'question time'
        )
    )
;

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_questionnaire/questionnaire'),
        'exam_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'date'
        )
    )
;

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_questionnaire/questionnaire'),
        'questions',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'questions'
        )
    )
;
$this->endSetup();
