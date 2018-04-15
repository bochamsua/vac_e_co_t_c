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
 * Trainee admin controller
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Adminhtml_Trainee_TraineeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->setUsedModuleName('BS_Trainee');
    }

    /**
     * init the trainee
     *
     * @access protected 
     * @return BS_Trainee_Model_Trainee
     * @author Bui Phong
     */
    protected function _initTrainee()
    {
        $this->_title($this->__('Manage Trainees'))
             ->_title($this->__('Manage Trainees'));

        $traineeId  = (int) $this->getRequest()->getParam('id');
        $trainee    = Mage::getModel('bs_trainee/trainee')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($traineeId) {
            $trainee->load($traineeId);
        }
        Mage::register('current_trainee', $trainee);
        return $trainee;
    }

    /**
     * default action for trainee controller
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_title($this->__('Manage Trainees'))
             ->_title($this->__('Manage Trainees'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new trainee action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit trainee action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $traineeId  = (int) $this->getRequest()->getParam('id');
        $trainee    = $this->_initTrainee();
        if ($traineeId && !$trainee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_trainee')->__('This trainee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getTraineeData(true)) {
            $trainee->setData($data);
        }
        $this->_title($trainee->getTraineeName());
        Mage::dispatchEvent(
            'bs_trainee_trainee_edit_action',
            array('trainee' => $trainee)
        );
        $this->loadLayout();
        if ($trainee->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('bs_trainee')->__('Default Values'))
                    ->setWebsiteIds($trainee->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save trainee action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $traineeId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            if($data['trainee']['import'] != ''){

                //For example: Trainee Name -- Trainee Code -- Phone -- Birthday -- Place of birth -- CMND. "--" cab be a TAB.
                $import = explode("\r\n", $data['trainee']['import']);
                $success = 1;
                foreach ($import as $line) {

                    if(strpos($line, "--")){
                        $item = explode("--", $line);
                    }else {
                        $item = explode("\t", $line);
                    }
                    $name = false;
                    $code = false;
                    $phone = '';
                    $birthday = null;
                    $pob = '';
                    $cmnd = '';

                    if(count($item) == 2){//Just Name and Code
                        $name = trim($item[0]);
                        $code = trim($item[1]);
                    }elseif(count($item)==6){
                        $name = trim($item[0]);
                        $code = trim($item[1]);
                        $phone = trim($item[2]);
                        $birthday = DateTime::createFromFormat('d/m/Y', trim($item[3]))->format('Y-m-d');
                        $pob = trim($item[4]);
                        $cmnd = trim($item[5]);
                    }

                    if($name && $code){
                        $trainee = Mage::getModel('bs_trainee/trainee');
                        $trainee->setAttributeSetId($trainee->getDefaultAttributeSetId());

                        try {
                            $trainee->setTraineeName($name)
                                ->setTraineeCode($code)
                                ->setTraineePhone($phone)
                                ->setTraineePob($pob)
                                ->setTraineeDob($birthday)
                                ->setTraineeCmnd($cmnd)
                                ->save()
                            ;

                            $success++;
                        } catch (Mage_Core_Exception $e) {
                            Mage::logException($e);
                            $this->_getSession()->addError($e->getMessage());
                            $redirectBack = true;
                        } catch (Exception $e) {
                            Mage::logException($e);
                            $this->_getSession()->addError(
                                Mage::helper('bs_trainee')->__('Error saving trainee')
                            )
                            ;
                            $redirectBack = true;
                        }


                    }

                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_trainee')->__('Total %s trainees were saved', $success)
                    );

                }

            }else {
                $trainee     = $this->_initTrainee();
                $traineeData = $this->getRequest()->getPost('trainee', array());
                $trainee->addData($traineeData);
                $trainee->setAttributeSetId($trainee->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $trainee->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $trainee->setData($attributeCode, false);
                    }
                }
                try {
                    $trainee->save();
                    $traineeId = $trainee->getId();
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_trainee')->__('Trainee was saved')
                    );
                } catch (Mage_Core_Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage())
                        ->setTraineeData($traineeData);
                    $redirectBack = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError(
                        Mage::helper('bs_trainee')->__('Error saving trainee')
                    )
                        ->setTraineeData($traineeData);
                    $redirectBack = true;
                }
            }

        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $traineeId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    public function getusernamesAction()
    {
        $courseId        = $this->getRequest()->getParam('product_id');

        $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($courseId);

        if ($trainees->count()) {

            try {
                $usernames = '';
                foreach ($trainees as $tnId) {
                    $trainee = Mage::getModel('bs_trainee/trainee')->load($tnId->getId());

                    $vaecoId = $trainee->getVaecoId();
                    $cus= Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();

                    $customer = Mage::getModel('customer/customer')->load($cus->getId());

                    $usernames .= $customer->getUsername().', ';
                }

                $usernames = substr($usernames, 0, -2);

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_trainee')->__('Usernames: %s', $usernames)
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/catalog_product/edit', array('id'=>$courseId))
        );
    }

    /**
     * delete trainee
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $trainee = Mage::getModel('bs_trainee/trainee')->load($id);
            try {
                $trainee->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_trainee')->__('The trainees has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete trainees
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            $this->_getSession()->addError($this->__('Please select trainees.'));
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                    $trainee = Mage::getSingleton('bs_trainee/trainee')->load($traineeId);
                    Mage::dispatchEvent(
                        'bs_trainee_controller_trainee_delete',
                        array('trainee' => $trainee)
                    );
                    $trainee->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_trainee')->__('Total of %d record(s) have been deleted.', count($traineeIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_trainee')->__('Please select trainees.')
            );
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                $trainee = Mage::getSingleton('bs_trainee/trainee')->load($traineeId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainees were successfully updated.', count($traineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_trainee')->__('There was an error updating trainees.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massChangeFunctionAction()
    {
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_trainee')->__('Please select trainees.')
            );
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                    $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId)
                        ->setTraineeFunction($this->getRequest()->getParam('function'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainees were successfully updated.', count($traineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_trainee')->__('There was an error updating trainees.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    public function massChangeBasicAction()
    {
        $traineeIds = $this->getRequest()->getParam('trainee');
        if (!is_array($traineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_trainee')->__('Please select trainees.')
            );
        } else {
            try {
                foreach ($traineeIds as $traineeId) {
                    $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId)
                        ->setTraineeBasic($this->getRequest()->getParam('basic'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainees were successfully updated.', count($traineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_trainee')->__('There was an error updating trainees.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/trainee');
    }

    /**
     * Export trainees in CSV format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'trainees.csv';
        $content    = $this->getLayout()->createBlock('bs_trainee/adminhtml_trainee_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export trainees in Excel format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'trainee.xls';
        $content    = $this->getLayout()->createBlock('bs_trainee/adminhtml_trainee_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export trainees in XML format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'trainee.xml';
        $content    = $this->getLayout()->createBlock('bs_trainee/adminhtml_trainee_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'bs_trainee/adminhtml_trainee_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.product')
            ->setTraineeProducts($this->getRequest()->getPost('trainee_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsgridAction()
    {
        $this->_initTrainee();
        $this->loadLayout();
        $this->getLayout()->getBlock('trainee.edit.tab.product')
            ->setTraineeProducts($this->getRequest()->getPost('trainee_products', null));
        $this->renderLayout();
    }
}
