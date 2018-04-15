<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News module install script
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_news/news'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'News ID'
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
        'content',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Content'
    )
    ->addColumn(
        'date_from',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'From Date'
    )
    ->addColumn(
        'date_to',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'To Date'
    )
    ->addColumn(
        'receiver',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Receiver'
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
        'News Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'News Creation Time'
    ) 
    ->setComment('News Table');
$this->getConnection()->createTable($table);
$this->endSetup();
