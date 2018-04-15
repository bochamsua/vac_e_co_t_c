<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * CurriculumDoc module install script
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_curriculumdoc/curriculumdoc'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Curriculum Document ID'
    )
    ->addColumn(
        'cdoc_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Document Name'
    )
    ->addColumn(
        'cdoc_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Document Type'
    )
    ->addColumn(
        'cdoc_file',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'File'
    )
    ->addColumn(
        'cdoc_rev',
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
        'Curriculum Document Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Curriculum Document Creation Time'
    ) 
    ->setComment('Curriculum Document Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_curriculumdoc/curriculumdoc_curriculum'))
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
        'curriculumdoc_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Curriculum Document ID'
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
            'bs_curriculumdoc/curriculumdoc_curriculum',
            array('curriculum_id')
        ),
        array('curriculum_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_curriculumdoc/curriculumdoc_curriculum',
            'curriculumdoc_id',
            'bs_curriculumdoc/curriculumdoc',
            'entity_id'
        ),
        'curriculumdoc_id',
        $this->getTable('bs_curriculumdoc/curriculumdoc'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_curriculumdoc/curriculumdoc_curriculum',
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
            'bs_curriculumdoc/curriculumdoc_curriculum',
            array('curriculumdoc_id', 'curriculum_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('curriculumdoc_id', 'curriculum_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Curriculum Document to Curriculum Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
