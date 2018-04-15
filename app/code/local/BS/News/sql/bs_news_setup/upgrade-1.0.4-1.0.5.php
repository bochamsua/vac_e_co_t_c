<?php
$this->startSetup();
$this->getConnection()
    ->addColumn(
        $this->getTable('bs_news/news'),
        'short_description',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment'   => 'Short Description'
        )
    )
;
$this->endSetup();
