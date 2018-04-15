<?php
/**
 * BS_Material extension
 * 
 * 
 * @category       BS
 * @package        BS_Material
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Material
 * @author      Bui Phong
 */
class BS_Material_Model_Adminhtml_Search_Instructordoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Material_Model_Adminhtml_Search_Instructordoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_material/instructordoc_collection')
            ->addFieldToFilter('idoc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $instructordoc) {
            $arr[] = array(
                'id'          => 'instructordoc/1/'.$instructordoc->getId(),
                'type'        => Mage::helper('bs_material')->__('Instructor Document'),
                'name'        => $instructordoc->getIdocName(),
                'description' => $instructordoc->getIdocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/material_instructordoc/edit',
                    array('id'=>$instructordoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
