<?php

$this->startSetup();

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_traineedoc/traineedoc'),
        'course_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
            'comment'   => 'Course'
        )
    )
;

$this->getConnection()
    ->addColumn(
        $this->getTable('bs_traineedoc/traineedoc'),
        'trainee_doc_date',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DATE,
            'comment'   => 'Date'
        )
    )
;
$this->endSetup();

