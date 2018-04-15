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
        'subject_name',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Subject Name'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'subject_position',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Subject Position'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_name',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Item Name'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_ref',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Item Ref'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_taskcode',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Item Taskcode'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_taskcat',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Item Taskcat'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_applicable',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Item Applicable For'
        )
    )
;
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_kst/kstprogress'),
        'item_position',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Item Position'
        )
    )
;
$this->endSetup();
