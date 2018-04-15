<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Model_Adminhtml_Search_Shortcut extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Shortcut_Model_Adminhtml_Search_Shortcut
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_shortcut/shortcut_collection')
            ->addFieldToFilter('shortcut', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $shortcut) {
            $arr[] = array(
                'id'          => 'shortcut/1/'.$shortcut->getId(),
                'type'        => Mage::helper('bs_shortcut')->__('Shortcut'),
                'name'        => $shortcut->getShortcut(),
                'description' => $shortcut->getShortcut(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/shortcut_shortcut/edit',
                    array('id'=>$shortcut->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
