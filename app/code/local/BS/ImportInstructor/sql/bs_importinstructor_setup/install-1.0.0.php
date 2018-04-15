<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * ImportInstructor module install script
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_importinstructor/importinstructor'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Import Instructor ID'
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
        'Import Instructor Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Import Instructor Creation Time'
    ) 
    ->setComment('Import Instructor Table');
$this->getConnection()->createTable($table);
$this->endSetup();
