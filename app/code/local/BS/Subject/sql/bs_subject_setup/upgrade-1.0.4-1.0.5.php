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
        $this->getTable('bs_subject_subject'),
        'subject_ws',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'comment'   => 'Refer to WS'
        )
    )
    ;
$this->endSetup();
