<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor module install script
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
$this->startSetup();

$table = $this->getConnection()
    ->newTable($this->getTable('bs_logistics/foldercontent'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Folder Content ID'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Title'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Description'
    )
    ->addColumn(
        'updated_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Updated Date'
    )
    ->addColumn(
        'filefolder_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Filefolder'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Position'
    )
    ->addColumn(
        'note',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
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
        'Folder Content Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Folder Content Creation Time'
    )
    ->setComment('Folder Content Table');
$this->getConnection()->createTable($table);
$this->endSetup();
