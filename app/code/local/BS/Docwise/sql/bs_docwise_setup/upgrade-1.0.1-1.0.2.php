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
 *
 * @var $this Mage_Core_Model_Resource_Setup
 */
$this->startSetup();

$table = $this->getConnection()
    ->newTable($this->getTable('bs_docwise/scores'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Score (OLD) ID'
    )
    ->addColumn(
        'trainee_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Trainee'
    )
    ->addColumn(
        'vaeco_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Vaeco ID'
    )
    ->addColumn(
        'dob',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Dob'
    )
    ->addColumn(
        'score',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Score'
    )
    ->addColumn(
        'cert_no',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Cert No'
    )
    ->addColumn(
        'exam_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Exam Date'
    )
    ->addColumn(
        'expire_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Expire Date'
    )
    ->addColumn(
        'question_no',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Question No'
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
        'Score (OLD) Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Score (OLD) Creation Time'
    )
    ->setComment('Score (OLD) Table');
$this->getConnection()->createTable($table);

$this->endSetup();
