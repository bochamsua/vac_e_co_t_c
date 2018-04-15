<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * CourseCost module install script
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_coursecost/coursecost'),
        'coursecost_cost',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'comment'   => 'Cost'
        )
    )
;
$this->endSetup();
