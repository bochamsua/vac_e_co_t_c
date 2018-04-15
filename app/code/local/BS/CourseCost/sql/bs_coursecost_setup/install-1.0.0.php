<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * CourseCost module install script
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('bs_coursecost/costgroup'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Manage Cost Group ID'
    )
    ->addColumn(
        'group_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'group_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Code'
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
        'Manage Cost Group Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Manage Cost Group Creation Time'
    ) 
    ->setComment('Manage Cost Group Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_coursecost/costitem'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Manage Group Items ID'
    )
    ->addColumn(
        'costgroup_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Manage Cost Group ID'
    )
    ->addColumn(
        'item_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'item_cost',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(),
        'Cost'
    )
    ->addColumn(
        'item_unit',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Unit'
    )
    ->addColumn(
        'update_date',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Date'
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
        'Manage Group Items Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Manage Group Items Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_coursecost/costgroup', array('costgroup_id')), array('costgroup_id'))
    ->setComment('Manage Group Items Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_coursecost/coursecost'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Course Cost ID'
    )
    ->addColumn(
        'costgroup_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Manage Cost Group ID'
    )
    ->addColumn(
        'costitem_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Manage Group Items ID'
    )
    ->addColumn(
        'course_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Course'
    )
    ->addColumn(
        'qty',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Qty'
    )
    ->addColumn(
        'note',
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
        'Course Cost Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Course Cost Creation Time'
    ) 
    ->addIndex($this->getIdxName('bs_coursecost/costgroup', array('costgroup_id')), array('costgroup_id'))
    ->addIndex($this->getIdxName('bs_coursecost/costitem', array('costitem_id')), array('costitem_id'))
    ->setComment('Course Cost Table');
$this->getConnection()->createTable($table);
$this->endSetup();
