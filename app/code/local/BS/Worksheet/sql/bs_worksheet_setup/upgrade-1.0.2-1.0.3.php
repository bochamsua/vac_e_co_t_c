<?php

$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_worksheet/worksheet'),
        'ws_pdf',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,

            'comment'   => 'PDF'
        )
    )
;
$this->endSetup();

