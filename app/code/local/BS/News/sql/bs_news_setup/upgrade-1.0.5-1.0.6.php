<?php
$this->startSetup();
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_news/news'),
        'close_text',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'close_text'
        )
    )
;
$this->endSetup();
