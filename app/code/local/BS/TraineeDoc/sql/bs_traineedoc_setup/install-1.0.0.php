<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * TraineeDoc module install script
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_traineedoc/traineedoc'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Trainee Document ID'
    )
    ->addColumn(
        'trainee_doc_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Document Name'
    )
    ->addColumn(
        'trainee_doc_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Document Type'
    )
    ->addColumn(
        'trainee_doc_file',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'File'
    )
    ->addColumn(
        'trainee_doc_note',
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
        'Trainee Document Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Trainee Document Creation Time'
    ) 
    ->setComment('Trainee Document Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_traineedoc/traineedoc_trainee'))
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
        'traineedoc_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Trainee Document ID'
    )
    ->addColumn(
        'trainee_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Trainee ID'
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
            'bs_traineedoc/traineedoc_trainee',
            array('trainee_id')
        ),
        array('trainee_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_traineedoc/traineedoc_trainee',
            'traineedoc_id',
            'bs_traineedoc/traineedoc',
            'entity_id'
        ),
        'traineedoc_id',
        $this->getTable('bs_traineedoc/traineedoc'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_traineedoc/traineedoc_trainee',
            'trainee_id',
            'bs_trainee/trainee',
            'entity_id'
        ),
        'trainee_id',
        $this->getTable('bs_trainee/trainee'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'bs_traineedoc/traineedoc_trainee',
            array('traineedoc_id', 'trainee_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('traineedoc_id', 'trainee_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Trainee Document to Trainee Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
