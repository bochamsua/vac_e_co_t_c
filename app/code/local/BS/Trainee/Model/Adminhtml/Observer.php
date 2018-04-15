<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Adminhtml_Observer
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
     * add the trainee tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Trainee_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function addProductTraineeBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('current_product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'trainees',
                array(
                    'label' => Mage::helper('bs_trainee')->__('Trainees'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/trainee_trainee_catalog_product/trainees',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save trainee - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return BS_Trainee_Model_Adminhtml_Observer
     * @author Bui Phong
     */
    public function saveProductTraineeData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('trainees', -1);
        if ($post != '-1') {
            if(strpos("moke".$post, 'custom')){
                $arrayPost = explode("custom", $post);
                $post = $arrayPost[0];
                $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);

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

            $product = Mage::registry('current_product');

            $bypassDocwise = false;
            if($product->getBypassDocwise()){
                $bypassDocwise = true;
            }

            //get curriculum
            $docwise = false;
            $cur = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($product)->getFirstItem();
            if($cur->getId()){
                $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($cur->getId());
                $docwise = $curriculum->getCDocwise();
            }

            //check valid docwise if required
            if($docwise && !$bypassDocwise){

                $result = array();
                $blackListed = array();
                foreach ($post as $traineeId=>$value) {
                    $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);
                    $blackList = Mage::helper('bs_trainee')->checkBlacklist($traineeId, $product->getCourseStartDate());
                    $valid = Mage::helper('bs_trainee')->checkDocwise($trainee->getVaecoId(), $product->getCourseStartDate());

                    if($blackList){
                        $blackListed[] = $trainee->getVaecoId();
                        unset($post[$traineeId]);
                    }elseif(!$valid){
                        unset($post[$traineeId]);
                        $result[] = $trainee->getTraineeName()." (".$trainee->getVaecoId().")";

                    }
                }
                if(count($result)){
                    $idstring = implode(",", $result);
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('These trainees: %s don\'t have valid DOCWISE certificate!',$idstring));
                }

                if(count($blackListed)){
                    $idstring = implode(",", $blackListed);
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('These trainees: %s are blacklisted! They are not allowed to attend the course!',$idstring));
                }


            }

            //check duplicate

            $duplicate = array();
            foreach ($post as $traineeId=>$value) {
                $check = Mage::helper('bs_trainee')->checkDuplicate($product, $traineeId);
                if($check){
                    //unset($post[$traineeId]);
                    $duplicate[$traineeId] = $check;
                }
            }

            if(count($duplicate)){
                $str = '';
                foreach ($duplicate as $key => $values) {
                    $tn = Mage::getModel('bs_trainee/trainee')->load($key);
                    $name = $tn->getTraineeName();
                    $string = array();
                    foreach ($values as $sku) {
                        $prod = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
                        $hrNo = $prod->getHrDecisionNo();
                        $hrDate = '';
                        if($prod->getHrDecisionDate() != ''){
                            $hrDate = Mage::getModel('core/date')->date('d/m/Y',$prod->getHrDecisionDate());
                        }

                        $txt = $sku;
                        if($hrNo != ''){
                            $txt .= ' (HR No: '.$hrNo;
                        }
                        if($hrDate != ''){
                            $txt .= ', HR Date: '.$hrDate.')';
                        }
                        $string[] = $txt;
                    }
                    $str .= 'Trainee: '.$name.' is currently attended in course(s): '.implode(",", $string).'<br>';
                }

                Mage::getSingleton('adminhtml/session')->addError($str);


            }





            $traineeProduct = Mage::getResourceSingleton('bs_trainee/trainee_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}
