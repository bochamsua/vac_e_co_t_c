<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * KST module install script
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstmember'),
        'course_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Course'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'group_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Group'
        )
    )
;

$this->endSetup();
