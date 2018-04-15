<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder - product relation model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Resource_Filefolder_Exam extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Bui Phong
     */
    protected function  _construct()
    {
        $this->_init('bs_docwise/exam_filefolder', 'rel_id');
    }
    /**
     * Save file folder - product relations
     *
     * @access public
     * @param BS_Logistics_Model_Filefolder $filefolder
     * @param array $data
     * @return BS_Logistics_Model_Resource_Filefolder_Product
     * @author Bui Phong
     */
    public function saveFilefolderRelation($filefolder, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('filefolder_id=?', $filefolder->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'filefolder_id' => $filefolder->getId(),
                    'exam_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - file folder relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return BS_Logistics_Model_Resource_Filefolder_Product
     * @@author Bui Phong
     */
    public function saveExamRelation($exam, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('exam_id=?', $exam->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $filefolderId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'filefolder_id' => $filefolderId,
                    'exam_id'    => $exam->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
