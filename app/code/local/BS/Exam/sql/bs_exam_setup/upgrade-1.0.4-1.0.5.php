<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Register module install script
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 *
 * @var $this Mage_Core_Model_Resource_Setup
 */
$this->startSetup();

$this->getConnection()
    ->modifyColumn(
        $this->getTable('bs_exam_examresult'),
        'first_mark',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_FLOAT,
            'comment'   => '1st Mark'
        )
    )
    ->modifyColumn(
        $this->getTable('bs_exam_examresult'),
        'second_mark',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_FLOAT,
            'comment'   => '2nd Mark'
        )
    )
    ->modifyColumn(
        $this->getTable('bs_exam_examresult'),
        'third_mark',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_FLOAT,
            'comment'   => '3rd Mark'
        )
    )
    ;

$this->endSetup();
