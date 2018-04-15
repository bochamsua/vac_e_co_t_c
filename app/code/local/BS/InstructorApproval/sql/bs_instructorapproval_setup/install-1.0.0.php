<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * InstructorApproval module install script
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_instructorapproval/iapproval'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Instructor Approval ID'
    )
    ->addColumn(
        'iapproval_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Approval Title'
    )
    ->addColumn(
        'iapproval_function',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Function'
    )
    ->addColumn(
        'iapproval_compliance',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Compliance With'
    )
    ->addColumn(
        'iapproval_compliance_other',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Compliance With Other'
    )
    ->addColumn(
        'vaeco_ids',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'VAECO IDs'
    )
    ->addColumn(
        'iapproval_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Prepared Date'
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
        'Instructor Approval Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Instructor Approval Creation Time'
    ) 
    ->setComment('Instructor Approval Table');
$this->getConnection()->createTable($table);
$this->endSetup();
