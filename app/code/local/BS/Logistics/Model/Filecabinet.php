<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Filecabinet extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_filecabinet';
    const CACHE_TAG = 'bs_logistics_filecabinet';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_filecabinet';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'filecabinet';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_logistics/filecabinet');
    }

    /**
     * before save file cabinet
     *
     * @access protected
     * @return BS_Logistics_Model_Filecabinet
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save file cabinet relation
     *
     * @access public
     * @return BS_Logistics_Model_Filecabinet
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Logistics_Model_Filefolder_Collection
     * @author Bui Phong
     */
    public function getSelectedFilefoldersCollection()
    {
        if (!$this->hasData('_filefolder_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_logistics/filefolder_collection')
                        ->addFieldToFilter('filecabinet_id', $this->getId());
                $this->setData('_filefolder_collection', $collection);
            }
        }
        return $this->getData('_filefolder_collection');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Logistics_Model_Otherroom
     * @author Bui Phong
     */
    public function getParentOtherroom()
    {
        if (!$this->hasData('_parent_otherroom')) {
            if (!$this->getOtherroomId()) {
                return null;
            } else {
                $otherroom = Mage::getModel('bs_logistics/otherroom')
                    ->load($this->getOtherroomId());
                if ($otherroom->getId()) {
                    $this->setData('_parent_otherroom', $otherroom);
                } else {
                    $this->setData('_parent_otherroom', null);
                }
            }
        }
        return $this->getData('_parent_otherroom');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
