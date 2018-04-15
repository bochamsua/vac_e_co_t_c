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
        $this->getTable('bs_report/report'),
        'rate_one',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Rate One'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_report/report'),
        'rate_two',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Rate two'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_report/report'),
        'rate_three',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Rate three'
        )
    )
;
$this->endSetup();
