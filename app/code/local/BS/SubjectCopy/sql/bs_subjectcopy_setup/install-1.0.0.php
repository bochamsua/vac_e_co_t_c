<?php
/**
 * BS_SubjectCopy extension
 * 
 * @category       BS
 * @package        BS_SubjectCopy
 * @copyright      Copyright (c) 2015
 */
/**
 * SubjectCopy module install script
 *
 * @category    BS
 * @package     BS_SubjectCopy
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_subjectcopy/subjectcopy'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Subject Copy ID'
    )
    ->addColumn(
        'c_from',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Copy from Curriculum'
    )
    ->addColumn(
        'c_to',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Copy to Curriculum'
    )
    ->addColumn(
        'include_sub',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Include Subcontent'
    )
    ->addColumn(
        'replace_all',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Replace All Existing Subjects'
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
        'Subject Copy Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Subject Copy Creation Time'
    ) 
    ->setComment('Subject Copy Table');
$this->getConnection()->createTable($table);
$this->endSetup();
