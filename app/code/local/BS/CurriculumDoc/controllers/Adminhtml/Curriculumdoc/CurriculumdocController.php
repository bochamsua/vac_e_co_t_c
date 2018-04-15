<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document admin controller
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Adminhtml_Curriculumdoc_CurriculumdocController extends BS_CurriculumDoc_Controller_Adminhtml_CurriculumDoc
{
    /**
     * init the curriculum doc
     *
     * @access protected
     * @return BS_CurriculumDoc_Model_Curriculumdoc
     */
    protected function _initCurriculumdoc()
    {
        $curriculumdocId  = (int) $this->getRequest()->getParam('id');
        $curriculumdoc    = Mage::getModel('bs_curriculumdoc/curriculumdoc');
        if ($curriculumdocId) {
            $curriculumdoc->load($curriculumdocId);
        }
        Mage::register('current_curriculumdoc', $curriculumdoc);
        return $curriculumdoc;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->loadLayout();

        $this->_title(Mage::helper('bs_curriculumdoc')->__('Curriculum Documents'))
             ->_title(Mage::helper('bs_curriculumdoc')->__('Curriculum Documents'));
        $this->renderLayout();
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
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit curriculum doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $curriculumdocId    = $this->getRequest()->getParam('id');
        $curriculumdoc      = $this->_initCurriculumdoc();
        if ($curriculumdocId && !$curriculumdoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_curriculumdoc')->__('This curriculum doc no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCurriculumdocData(true);
        if (!empty($data)) {
            $curriculumdoc->setData($data);
        }
        Mage::register('curriculumdoc_data', $curriculumdoc);
        $this->loadLayout();

        $this->_title(Mage::helper('bs_curriculumdoc')->__('Training List'))
            ->_title(Mage::helper('bs_curriculumdoc')->__('Curriculum Documents'));
        if ($curriculumdoc->getId()) {
            $this->_title($curriculumdoc->getCdocName());
        } else {
            $this->_title(Mage::helper('bs_curriculumdoc')->__('Add curriculum doc'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new curriculum doc action
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
     * save curriculum doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('curriculumdoc')) {
            try {
                $data = $this->_filterDates($data, array('cdoc_date'));
                $curriculumdoc = $this->_initCurriculumdoc();
                $curriculumdoc->addData($data);
                $cdocFileName = $this->_uploadAndGetName(
                    'cdoc_file',
                    Mage::helper('bs_curriculumdoc/curriculumdoc')->getFileBaseDir(),
                    $data
                );
                $curriculumId = null;

                if(isset($data['hidden_curriculum_id']) && $data['hidden_curriculum_id'] > 0) {
                    $curriculumId = $data['hidden_curriculum_id'];
                }elseif($this->getRequest()->getParam('curriculum_id')){
                    $curriculumId = $this->getRequest()->getParam('curriculum_id');
                }
                $curriculumdoc->setData('cdoc_file', $cdocFileName);
                $curriculums = $this->getRequest()->getPost('curriculums', -1);
                if ($curriculums != -1) {
                    $curriculumdoc->setCurriculumsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($curriculums));
                }else {
                    if(isset($data['hidden_curriculum_id']) && $data['hidden_curriculum_id'] > 0){
                        $curriculumId = $data['hidden_curriculum_id'];
                        $curriculumdoc->setCurriculumsData(
                            array(
                                $data['hidden_curriculum_id'] => array(
                                    'position' => ""
                                )
                            )
                        );
                    }
                }

                $curriculumdoc->save();

                $add = '';//traininglist_curriculum/edit/id/1/back/edit/tab/curriculum_info_tabs_curriculumdocs/
                $backUrl = $this->getUrl('*/traininglist_curriculum/edit/', array('back'=>'edit','id' => $curriculumId, 'tab'=>'curriculum_info_tabs_curriculumdocs'));
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.location.href=\''.$backUrl.'\'; window.close()</script>';//window.opener.location.reload()
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_curriculumdoc')->__('Curriculum Document was successfully saved %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $curriculumdoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['cdoc_file']['value'])) {
                    $data['cdoc_file'] = $data['cdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCurriculumdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['cdoc_file']['value'])) {
                    $data['cdoc_file'] = $data['cdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was a problem saving the curriculum doc.')
                );
                Mage::getSingleton('adminhtml/session')->setCurriculumdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_curriculumdoc')->__('Unable to find curriculum doc to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete curriculum doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $curriculumdoc = Mage::getModel('bs_curriculumdoc/curriculumdoc');
                $curriculumdoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_curriculumdoc')->__('Curriculum Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was an error deleting curriculum doc.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_curriculumdoc')->__('Could not find curriculum doc to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete curriculum doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $curriculumdocIds = $this->getRequest()->getParam('curriculumdoc');
        if (!is_array($curriculumdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_curriculumdoc')->__('Please select curriculum docs to delete.')
            );
        } else {
            try {
                foreach ($curriculumdocIds as $curriculumdocId) {
                    $curriculumdoc = Mage::getModel('bs_curriculumdoc/curriculumdoc');
                    $curriculumdoc->setId($curriculumdocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_curriculumdoc')->__('Total of %d curriculum docs were successfully deleted.', count($curriculumdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was an error deleting curriculum docs.')
                );
                Mage::logException($e);
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
        $curriculumdocIds = $this->getRequest()->getParam('curriculumdoc');
        if (!is_array($curriculumdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_curriculumdoc')->__('Please select curriculum docs.')
            );
        } else {
            try {
                foreach ($curriculumdocIds as $curriculumdocId) {
                $curriculumdoc = Mage::getSingleton('bs_curriculumdoc/curriculumdoc')->load($curriculumdocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d curriculum docs were successfully updated.', count($curriculumdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was an error updating curriculum docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Document Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCdocTypeAction()
    {
        $curriculumdocIds = $this->getRequest()->getParam('curriculumdoc');
        if (!is_array($curriculumdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_curriculumdoc')->__('Please select curriculum docs.')
            );
        } else {
            try {
                foreach ($curriculumdocIds as $curriculumdocId) {
                $curriculumdoc = Mage::getSingleton('bs_curriculumdoc/curriculumdoc')->load($curriculumdocId)
                    ->setCdocType($this->getRequest()->getParam('flag_cdoc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d curriculum docs were successfully updated.', count($curriculumdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was an error updating curriculum docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Revision change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCdocRevAction()
    {
        $curriculumdocIds = $this->getRequest()->getParam('curriculumdoc');
        if (!is_array($curriculumdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_curriculumdoc')->__('Please select curriculum docs.')
            );
        } else {
            try {
                foreach ($curriculumdocIds as $curriculumdocId) {
                $curriculumdoc = Mage::getSingleton('bs_curriculumdoc/curriculumdoc')->load($curriculumdocId)
                    ->setCdocRev($this->getRequest()->getParam('flag_cdoc_rev'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d curriculum docs were successfully updated.', count($curriculumdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_curriculumdoc')->__('There was an error updating curriculum docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsAction()
    {
        $this->_initCurriculumdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculumdoc.edit.tab.curriculum')
            ->setCurriculumdocCurriculums($this->getRequest()->getPost('curriculumdoc_curriculums', null));
        $this->renderLayout();
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsgridAction()
    {
        $this->_initCurriculumdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculumdoc.edit.tab.curriculum')
            ->setCurriculumdocCurriculums($this->getRequest()->getPost('curriculumdoc_curriculums', null));
        $this->renderLayout();
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'curriculumdoc.csv';
        $content    = $this->getLayout()->createBlock('bs_curriculumdoc/adminhtml_curriculumdoc_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'curriculumdoc.xls';
        $content    = $this->getLayout()->createBlock('bs_curriculumdoc/adminhtml_curriculumdoc_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'curriculumdoc.xml';
        $content    = $this->getLayout()->createBlock('bs_curriculumdoc/adminhtml_curriculumdoc_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/curriculumdoc');
    }
}
