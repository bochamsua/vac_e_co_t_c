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
        $this->getTable('bs_staffinfo/training'),
        'start_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'Start Date'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/training'),
        'end_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'End Date'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_staffinfo/training'),
        'note',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Note'
        )
    )
;
$this->endSetup();
