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
    ->newTable($this->getTable('bs_logistics/wgroupitem'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Group Item ID'
    )
    ->addColumn(
        'grouptype_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Type ID'
    )
    ->addColumn(
        'workshop_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Workshop ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'name_vi',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Vietnamese'
    )
    ->addColumn(
        'code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Code'
    )
    ->addColumn(
        'qty',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
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
        'Group Item Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Group Item Creation Time'
    )
    ->addIndex($this->getIdxName('bs_logistics/workshop', array('workshop_id')), array('workshop_id'))
    ->addIndex($this->getIdxName('bs_logistics/grouptype', array('grouptype_id')), array('grouptype_id'))
    ->setComment('Group Item Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_logistics/wtool'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Tool ID'
    )
    ->addColumn(
        'workshop_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Workshop ID'
    )
    ->addColumn(
        'wgroupitem_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Group Item ID'
    )
    ->addColumn(
        'grouptype_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Type ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'name_vi',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Vietnamese'
    )
    ->addColumn(
        'code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Code'
    )
    ->addColumn(
        'tool_status',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Tool Status'
    )
    ->addColumn(
        'qty',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
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
        'Tool Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Tool Creation Time'
    )
    ->addIndex($this->getIdxName('bs_logistics/workshop', array('workshop_id')), array('workshop_id'))
    ->addIndex($this->getIdxName('bs_logistics/wgroupitem', array('wgroupitem_id')), array('wgroupitem_id'))
    ->addIndex($this->getIdxName('bs_logistics/grouptype', array('grouptype_id')), array('grouptype_id'))
    ->setComment('Tool Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('bs_logistics/grouptype'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Type ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'name_vi',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Vietnamese'
    )
    ->addColumn(
        'code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Code'
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
        'Type Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Type Creation Time'
    )
    ->setComment('Type Table');
$this->getConnection()->createTable($table);


$table = $this->getConnection()
    ->newTable($this->getTable('bs_logistics/equipment'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Equipment ID'
    )
    ->addColumn(
        'classroom_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Classroom/Examroom ID'
    )
    ->addColumn(
        'workshop_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Workshop ID'
    )
    ->addColumn(
        'otherroom_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Other room ID'
    )
    ->addColumn(
        'equipment_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'name_vi',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Vietnamese'
    )
    ->addColumn(
        'qty',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
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
        'Equipment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Equipment Creation Time'
    )
    ->addIndex($this->getIdxName('bs_logistics/classroom', array('classroom_id')), array('classroom_id'))
    ->addIndex($this->getIdxName('bs_logistics/workshop', array('workshop_id')), array('workshop_id'))
    ->addIndex($this->getIdxName('bs_logistics/otherroom', array('otherroom_id')), array('otherroom_id'))
    ->setComment('Equipment Table');
$this->getConnection()->createTable($table);

$this->endSetup();
