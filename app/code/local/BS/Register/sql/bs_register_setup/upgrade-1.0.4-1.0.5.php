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
    ->addColumn(
        $this->getTable('bs_register/schedule'),
        'subject_type',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'comment'   => 'Subject Type'
        )
    )
    ;
$this->endSetup();
