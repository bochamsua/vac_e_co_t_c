<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Material module install script
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_material/instructordoc'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Instructor Document ID'
    )
    ->addColumn(
        'idoc_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Document Name'
    )
    ->addColumn(
        'idoc_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Document Type'
    )
    ->addColumn(
        'idoc_file',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'File'
    )
    ->addColumn(
        'idoc_rev',
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
        'Instructor Document Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Instructor Document Creation Time'
    ) 
    ->setComment('Instructor Document Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_material/instructordoc_instructor'))
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
        'instructordoc_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Instructor Document ID'
    )
    ->addColumn(
        'instructor_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Instructor ID'
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
            'bs_material/instructordoc_instructor',
            array('instructor_id')
        ),
        array('instructor_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_material/instructordoc_instructor',
            'instructordoc_id',
            'bs_material/instructordoc',
            'entity_id'
        ),
        'instructordoc_id',
        $this->getTable('bs_material/instructordoc'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_material/instructordoc_instructor',
            'instructor_id',
            'bs_instructor/instructor',
            'entity_id'
        ),
        'instructor_id',
        $this->getTable('bs_instructor/instructor'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bs_material/instructordoc_instructor',
            array('instructordoc_id', 'instructor_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('instructordoc_id', 'instructor_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Instructor Document to Instructor Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
