<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry front contrller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_InquiryController extends Mage_Core_Controller_Front_Action
{

    /**
     * init Inquiry
     *
     * @access protected
     * @return BS_Docwise_Model_Inquiry
     * @author Bui Phong
     */
    protected function _initInquiry()
    {
        return true;
    }

    /**
     * view inquiry action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function viewAction()
    {
        $inquiry = $this->_initInquiry();


        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            //$root->addBodyClass('docwise-inquiry docwise-inquiry' . $inquiry->getId());
        }
        if (Mage::helper('bs_docwise/inquiry')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('bs_docwise')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'inquiry',
                    array(
                        'label' => 'Inquiry Docwise Score',
                        'link'  => '',
                    )
                );
            }
        }

        $vaecoId = $this->getRequest()->getPost('vaeco_id');
        if($vaecoId != ''){

            //$ids = explode("\r\n", $vaecoId);

            $ids = explode(",", $vaecoId);
            $ids = array_unique($ids);
            $result = array();
            foreach ($ids as $id) {
                $id = trim($id);
                if(strlen($id) == 5){
                    $id = "VAE".$id;
                }elseif (strlen($id) == 4){
                    $id = "VAE0".$id;
                }
                $id = strtoupper($id);

                $score = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', $id)->setOrder('expire_date', 'DESC')->getFirstItem();
                if($score->getId()){
                    $result[] = $score;

                }


            }


            Mage::register('current_inquiry', $result);
        }


        $this->renderLayout();
    }
}
