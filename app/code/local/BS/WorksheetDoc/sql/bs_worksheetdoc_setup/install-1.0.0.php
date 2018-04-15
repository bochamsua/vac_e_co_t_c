<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * WorksheetDoc module install script
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_worksheetdoc/worksheetdoc'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Worksheet Document ID'
    )
    ->addColumn(
        'wsdoc_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Document Name'
    )
    ->addColumn(
        'wsdoc_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Document Type'
    )
    ->addColumn(
        'wsdoc_file',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'File'
    )
    ->addColumn(
        'wsdoc_rev',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Revision'
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
        'Worksheet Document Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Worksheet Document Creation Time'
    ) 
    ->setComment('Worksheet Document Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_worksheetdoc/worksheetdoc_worksheet'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'worksheetdoc_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Worksheet Document ID'
    )
    ->addColumn(
        'worksheet_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Worksheet ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'bs_worksheetdoc/worksheetdoc_worksheet',
            array('worksheet_id')
        ),
        array('worksheet_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_worksheetdoc/worksheetdoc_worksheet',
            'worksheetdoc_id',
            'bs_worksheetdoc/worksheetdoc',
            'entity_id'
        ),
        'worksheetdoc_id',
        $this->getTable('bs_worksheetdoc/worksheetdoc'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_worksheetdoc/worksheetdoc_worksheet',
            'worksheet_id',
            'bs_worksheet/worksheet',
            'entity_id'
        ),
        'worksheet_id',
        $this->getTable('bs_worksheet/worksheet'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bs_worksheetdoc/worksheetdoc_worksheet',
            array('worksheetdoc_id', 'worksheet_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('worksheetdoc_id', 'worksheet_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Worksheet Document to Worksheet Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
