<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment module install script
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_assessment/assessment'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Assessment ID'
    )
    ->addColumn(
        'content',
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
        'detail',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Detail'
    )
    ->addColumn(
        'duration',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Duration'
    )
    ->addColumn(
        'article',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'On article'
    )
    ->addColumn(
        'app_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'App. Type'
    )
    ->addColumn(
        'app_cat',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'App Cat'
    )
    ->addColumn(
        'prepared_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Prepared Date'
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
        'Assessment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Assessment Creation Time'
    ) 
    ->setComment('Assessment Table');
$this->getConnection()->createTable($table);
$this->endSetup();
