<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Tasktraining module install script
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
$this->startSetup();
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_tasktraining/taskfunction'),
        'approved_doc',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,

            'comment'   => 'approved doc'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_tasktraining/taskfunction'),
        'approved_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,

            'comment'   => 'approved date'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_tasktraining/taskfunction'),
        'expire_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,

            'comment'   => 'expire date'
        )
    )
;
$this->endSetup();
