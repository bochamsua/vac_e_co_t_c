<?php
/**
 * BS_News extension
 * 
 * @category       BS
 * @package        BS_News
 * @copyright      Copyright (c) 2015
 */
/**
 * News resource model
 *
 * @category    BS
 * @package     BS_News
 * @author Bui Phong
 */
class BS_News_Model_Resource_News extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function _construct()
    {
        $this->_init('bs_news/news', 'entity_id');
    }

    /**
     * process multiple select fields
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BS_News_Model_Resource_News
     * @author Bui Phong
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $applyfor = $object->getApplyFor();
        if (is_array($applyfor)) {
            $object->setApplyFor(implode(',', $applyfor));
        }
        return parent::_beforeSave($object);
    }

}
