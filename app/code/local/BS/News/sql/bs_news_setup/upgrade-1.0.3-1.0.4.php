<?php
$this->startSetup();
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_news/news'),
        'view_users',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'View User'
        )
    )
;
$this->endSetup();
