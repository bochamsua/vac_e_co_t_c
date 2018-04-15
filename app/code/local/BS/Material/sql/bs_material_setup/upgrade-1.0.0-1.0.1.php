<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor module install script
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_material/instructordoc'),
        'idoc_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'Approved/Revised Date'
        )
    );

$this->endSetup();
