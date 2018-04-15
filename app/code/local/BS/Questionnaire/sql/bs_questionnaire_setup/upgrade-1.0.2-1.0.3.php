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
        'subject_size',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_FLOAT,
            'comment'   => 'subject_size'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_questionnaire/questionnaire'),
        'question_size',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_FLOAT,
            'comment'   => 'question_size'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_questionnaire/questionnaire'),
        'spacing',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'spacing'
        )
    )
;

$this->endSetup();
