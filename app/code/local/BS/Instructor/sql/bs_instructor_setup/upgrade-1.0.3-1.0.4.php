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
        $this->getTable('bs_instructor/instructorfunction'),
        'is_ti',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,

            'comment'   => 'Is Theoretical Instructor'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_instructor/instructorfunction'),
        'is_te',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,

            'comment'   => 'Is Theoretical Evaluator'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_instructor/instructorfunction'),
        'is_pi',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,

            'comment'   => 'Is Practical Instructor'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_instructor/instructorfunction'),
        'is_pe',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,

            'comment'   => 'Is Practical Evaluator'
        )
    )
;
$this->endSetup();
