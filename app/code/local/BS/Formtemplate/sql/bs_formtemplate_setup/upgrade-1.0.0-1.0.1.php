<?php
/**
 * BS_Formtemplate extension
 * 
 * @category       BS
 * @package        BS_Formtemplate
 * @copyright      Copyright (c) 2015
 */
/**
 * Formtemplate module install script
 *
 * @category    BS
 * @package     BS_Formtemplate
 * @author Bui Phong
 */
$installer = $this;
$installer->startSetup();
if ($installer->tableExists($installer->getTable('bs_formtemplate/formtemplate'))) {
    $installer->getConnection()
        ->addIndex(
            $installer->getTable('bs_formtemplate/formtemplate'),
            $installer->getIdxName('bs_formtemplate/formtemplate', array('template_code'), Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
            array('template_code'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        );
}
$installer->endSetup();
