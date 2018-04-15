<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Model_Adminhtml_Search_Certificate extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Certificate_Model_Adminhtml_Search_Certificate
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_certificate/certificate_collection')
            ->addFieldToFilter('staff_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $certificate) {
            $arr[] = array(
                'id'          => 'certificate/1/'.$certificate->getId(),
                'type'        => Mage::helper('bs_certificate')->__('Certificate'),
                'name'        => $certificate->getStaffName(),
                'description' => $certificate->getStaffName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/certificate_certificate/edit',
                    array('id'=>$certificate->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
