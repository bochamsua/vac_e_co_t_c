<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject module install script
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_subject/subject'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Subject ID'
    )
    ->addColumn(
        'curriculum_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Curriculum ID'
    )
    ->addColumn(
        'subject_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Subject Name'
    )
    ->addColumn(
        'subject_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Code'
    )
    ->addColumn(
        'subject_level',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Level'
    )
    ->addColumn(
        'subject_hour',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Hour'
    )
    ->addColumn(
        'subject_content',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Content'
    )
    ->addColumn(
        'subject_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Order'
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
        'Subject Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Subject Creation Time'
    )
    ->addIndex($this->getIdxName('bs_traininglist/curriculum', array('curriculum_id')), array('curriculum_id'))
    ->setComment('Subject Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_subject/subjectcontent'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Subject Content ID'
    )
    ->addColumn(
        'subject_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Subject ID'
    )
    ->addColumn(
        'subcon_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Title'
    )
    ->addColumn(
        'subcon_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(

        ),
        'Code'
    )
    ->addColumn(
        'subcon_level',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Level'
    )
    ->addColumn(
        'subcon_hour',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Hour'
    )
    ->addColumn(
        'subcon_content',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Content'
    )
    ->addColumn(
        'subcon_note',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Note'
    )
    ->addColumn(
        'subcon_order',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Sort Order'
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
        'Subject Content Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Subject Content Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_subject/subject', array('subject_id')), array('subject_id'))
    ->setComment('Subject Content Table');
$this->getConnection()->createTable($table);
$this->endSetup();
