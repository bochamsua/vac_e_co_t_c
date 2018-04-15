<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document model
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Model_Worksheetdoc extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_worksheetdoc_worksheetdoc';
    const CACHE_TAG = 'bs_worksheetdoc_worksheetdoc';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_worksheetdoc_worksheetdoc';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'worksheetdoc';
    protected $_worksheetInstance = null;

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
        $this->_init('bs_worksheetdoc/worksheetdoc');
    }

    /**
     * before save worksheet doc
     *
     * @access protected
     * @return BS_WorksheetDoc_Model_Worksheetdoc
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
     * save worksheet doc relation
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Worksheetdoc
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getWorksheetInstance()->saveWorksheetdocRelation($this);
        return parent::_afterSave();
    }

    /**
     * get worksheet relation model
     *
     * @access public
     * @return BS_WorksheetDoc_Model_Worksheetdoc_Worksheet
     * @author Bui Phong
     */
    public function getWorksheetInstance()
    {
        if (!$this->_worksheetInstance) {
            $this->_worksheetInstance = Mage::getSingleton('bs_worksheetdoc/worksheetdoc_worksheet');
        }
        return $this->_worksheetInstance;
    }

    /**
     * get selected worksheets array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedWorksheets()
    {
        if (!$this->hasSelectedWorksheets()) {
            $worksheets = array();
            foreach ($this->getSelectedWorksheetsCollection() as $worksheet) {
                $worksheets[] = $worksheet;
            }
            $this->setSelectedWorksheets($worksheets);
        }
        return $this->getData('selected_worksheets');
    }

    /**
     * Retrieve collection selected worksheets
     *
     * @access public
     * @return BS_WorksheetDoc_Resource_Worksheetdoc_Worksheet_Collection
     * @author Bui Phong
     */
    public function getSelectedWorksheetsCollection()
    {
        $collection = $this->getWorksheetInstance()->getWorksheetCollection($this);
        return $collection;
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
