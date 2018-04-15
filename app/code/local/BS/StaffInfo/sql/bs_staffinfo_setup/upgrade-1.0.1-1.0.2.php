<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * InstructorInfo module install script
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
$this->startSetup();
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'start_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'Start Date'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'end_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'End Date'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'working_place',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Place'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'working_as',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'As'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'working_on',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'On'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/working'),
        'remark',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Remark'
        )
    )
;
$this->endSetup();
