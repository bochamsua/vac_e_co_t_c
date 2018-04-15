<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Adminhtml_Search_Filefolder extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Logistics_Model_Adminhtml_Search_Filefolder
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_logistics/filefolder_collection')
            ->addFieldToFilter(array('filefolder_name', 'filefolder_code'), array(array('like' => '%'.$this->getQuery().'%'),array('like' => '%'.$this->getQuery().'%')))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $filefolder) {
            $arr[] = array(
                'id'          => 'filefolder/1/'.$filefolder->getId(),
                'type'        => Mage::helper('bs_logistics')->__('File Folder'),
                'name'        => $filefolder->getFilefolderName(),
                'description' => $filefolder->getFilefolderCode(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/logistics_filefolder/edit',
                    array('id'=>$filefolder->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
