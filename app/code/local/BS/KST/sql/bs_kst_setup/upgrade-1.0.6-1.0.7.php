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
        $this->getTable('bs_kst/kstprogress'),
        'trainee_feedback',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'trainee_feedback'
        )
    )
;

$this->endSetup();
