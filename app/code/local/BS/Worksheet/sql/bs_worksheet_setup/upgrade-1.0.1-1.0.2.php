<?php

$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_worksheet/worksheet'),
        'ws_page',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,

            'comment'   => 'Page'
        )
    )
;
$this->endSetup();

