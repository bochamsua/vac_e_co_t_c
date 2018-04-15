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
    ->newTable($this->getTable('bs_instructor/instructor_curriculum'))
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
            'bs_instructor/instructor_curriculum',
            array('curriculum_id')
        ),
        array('curriculum_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'bs_instructor/instructor_curriculum',
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
    ->addForeignKey(
        $this->getFkName(
            'bs_instructor/instructor_curriculum',
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
            'bs_instructor/instructor_curriculum',
            array('instructor_id', 'curriculum_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('instructor_id', 'curriculum_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Instructor to Curriculum Linkage Table');
$this->getConnection()->createTable($table);


$this->endSetup();
