<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Bui Phong
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the training curriculum tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Traininglist_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductCurriculumBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'curriculums',
                array(
                    'label' => Mage::helper('bs_traininglist')->__('Training Curriculum'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/traininglist_curriculum_catalog_product/curriculums',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save training curriculum - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Traininglist_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductCurriculumData($observer)
    {

        $product = Mage::registry('current_product');
        $curriculumCode = $product->getHiddenCurriculumCode();//hidden_curriculum_code
        if($curriculumCode != ''){
            $curriculum = Mage::getModel('bs_traininglist/curriculum')->loadByAttribute('c_code',$curriculumCode);
            if($curriculumId = $curriculum->getId()){
                $info = array(
                    $curriculumId => array('position'=>"")
                );


                $curriculumProduct = Mage::getResourceSingleton('bs_traininglist/curriculum_product')
                    ->saveProductRelation($product, $info);
            }
        }


        return $this;
    }
    /**
     * add the curriculum tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Traininglist_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addCategoryCurriculumBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'bs_traininglist/adminhtml_catalog_category_tab_curriculum',
            'category.curriculum.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.curriculum.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.curriculum.grid',
            'getSelectedCurriculums',
            'curriculums',
            'category_curriculums'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'curriculum',
            array(
                'label'   => Mage::helper('bs_traininglist')->__('Training Curriculum'),
                'content' => $content,
            )
        );
        return $this;
    }

    /**
     * save training curriculum - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Traininglist_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveCategoryCurriculumData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('curriculums', -1);
        if ($post != '-1') {

            if(strpos("moke".$post, 'custom')){
                $arrayPost = explode("custom", $post);
                $post = $arrayPost[0];
                $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);

                //Always get the last update position
                $new = $arrayPost[count($arrayPost)-1];

                parse_str($new, $new);
                foreach ($post as $key => $value) {
                    foreach ($new as $k => $v) {
                        if($key == $k){
                            $post[$key]['position'] = $v;
                        }
                    }
                }

            }else {
                $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            }



            $category = Mage::registry('category');
            $curriculumCategory = Mage::getResourceSingleton('bs_traininglist/curriculum_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
