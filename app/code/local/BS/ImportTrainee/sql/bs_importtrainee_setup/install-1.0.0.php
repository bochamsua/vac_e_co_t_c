<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * ImportTrainee module install script
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_importtrainee/importtrainee'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Import Trainee ID'
    )
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Course'
    )
    ->addColumn(
        'vaeco_ids',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'VAECO IDs'
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
        'Import Trainee Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Import Trainee Creation Time'
    ) 
    ->setComment('Import Trainee Table');
$this->getConnection()->createTable($table);
$this->endSetup();
