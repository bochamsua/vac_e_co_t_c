<?php
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
        $this->getTable('bs_instructorapproval/iapproval'),
        'evaluation_subject',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Evaluation Subject'
        )
    )
;

$this->endSetup();
