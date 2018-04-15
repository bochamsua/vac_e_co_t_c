<?php
/**
 * BS_Tc extension
 * 
 * @category       BS
 * @package        BS_Tc
 * @copyright      Copyright (c) 2015
 */
/**
 * Family admin controller
 *
 * @category    BS
 * @package     BS_Tc
 * @author Bui Phong
 */
class BS_Tc_Adminhtml_Tc_FamilyController extends Mage_Adminhtml_Controller_Action
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
        $this->setUsedModuleName('BS_Tc');
    }

    /**
     * init the family
     *
     * @access protected 
     * @return BS_Tc_Model_Family
     * @author Bui Phong
     */
    protected function _initFamily()
    {
        $this->_title($this->__('TC'))
             ->_title($this->__('Manage Familes'));

        $familyId  = (int) $this->getRequest()->getParam('id');
        $family    = Mage::getModel('bs_tc/family')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($familyId) {
            $family->load($familyId);
        }
        Mage::register('current_family', $family);
        return $family;
    }

    /**
     * default action for family controller
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_title($this->__('TC'))
             ->_title($this->__('Manage Familes'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new family action
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
     * edit family action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $familyId  = (int) $this->getRequest()->getParam('id');
        $family    = $this->_initFamily();
        if ($familyId && !$family->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tc')->__('This family no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getFamilyData(true)) {
            $family->setData($data);
        }
        $this->_title($family->getFname());
        Mage::dispatchEvent(
            'bs_tc_family_edit_action',
            array('family' => $family)
        );
        $this->loadLayout();
        if ($family->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('bs_tc')->__('Default Values'))
                    ->setWebsiteIds($family->getWebsiteIds())
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
     * save family action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $familyId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $family     = $this->_initFamily();
            $familyData = $this->getRequest()->getPost('family', array());
            $family->addData($familyData);
            $family->setAttributeSetId($family->getDefaultAttributeSetId());
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $family->setData($attributeCode, false);
                }
            }
            try {
                $family->save();
                $familyId = $family->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('Family was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setFamilyData($familyData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('bs_tc')->__('Error saving family')
                )
                ->setFamilyData($familyData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $familyId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete family
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $family = Mage::getModel('bs_tc/family')->load($id);
            try {
                $family->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('The familes has been deleted.')
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
     * mass delete familes
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $familyIds = $this->getRequest()->getParam('family');
        if (!is_array($familyIds)) {
            $this->_getSession()->addError($this->__('Please select familes.'));
        } else {
            try {
                foreach ($familyIds as $familyId) {
                    $family = Mage::getSingleton('bs_tc/family')->load($familyId);
                    Mage::dispatchEvent(
                        'bs_tc_controller_family_delete',
                        array('family' => $family)
                    );
                    $family->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_tc')->__('Total of %d record(s) have been deleted.', count($familyIds))
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
        $familyIds = $this->getRequest()->getParam('family');
        if (!is_array($familyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tc')->__('Please select familes.')
            );
        } else {
            try {
                foreach ($familyIds as $familyId) {
                $family = Mage::getSingleton('bs_tc/family')->load($familyId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d familes were successfully updated.', count($familyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tc')->__('There was an error updating familes.')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_tc/family');
    }

    /**
     * Export families in CSV format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'families.csv';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_family_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export familes in Excel format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'family.xls';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_family_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export familes in XML format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'family.xml';
        $content    = $this->getLayout()->createBlock('bs_tc/adminhtml_family_grid')
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
            'bs_tc/adminhtml_tc_helper_form_wysiwyg_content',
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
     * mass employee change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massEmployeeIdAction()
    {
        $familyIds = $this->getRequest()->getParam('family');
        if (!is_array($familyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tc')->__('Please select familes.')
            );
        } else {
            try {
                foreach ($familyIds as $familyId) {
                $family = Mage::getSingleton('bs_tc/family')->load($familyId)
                    ->setEmployeeId($this->getRequest()->getParam('flag_employee_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d familes were successfully updated.', count($familyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tc')->__('There was an error updating familes.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
}
