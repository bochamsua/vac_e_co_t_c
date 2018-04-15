<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet module install script
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_worksheet/worksheet'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Worksheet ID'
    )
    ->addColumn(
        'ws_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Worksheet Name'
    )
    ->addColumn(
        'ws_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Worksheet Code'
    )
    ->addColumn(
        'ws_approved_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Approved Date'
    )
    ->addColumn(
        'ws_revision',
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
        'Worksheet Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Worksheet Creation Time'
    ) 
    ->setComment('Worksheet Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_worksheet/worksheet_curriculum'))
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
        'curriculum_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Curriculum ID'
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
            'bs_worksheet/worksheet_curriculum',
            array('curriculum_id')
        ),
        array('curriculum_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_worksheet/worksheet_curriculum',
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
    ->addForeignKey(
        $this->getFkName(
            'bs_worksheet/worksheet_curriculum',
            'curriculum_id',
            'bs_traininglist/curriculum',
            'entity_id'
        ),
        'curriculum_id',
        $this->getTable('bs_traininglist/curriculum'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bs_worksheet/worksheet_curriculum',
            array('worksheet_id', 'curriculum_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('worksheet_id', 'curriculum_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Worksheet to Curriculum Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
