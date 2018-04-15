<?php

$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_worksheet/worksheet'),
        'ws_file',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,

            'comment'   => 'WS content'
        )
    )
;
$this->endSetup();

