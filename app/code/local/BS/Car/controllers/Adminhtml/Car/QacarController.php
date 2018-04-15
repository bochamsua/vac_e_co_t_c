<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * QA Car admin controller
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Adminhtml_Car_QacarController extends BS_Car_Controller_Adminhtml_Car
{
    /**
     * init the qa car
     *
     * @access protected
     * @return BS_Car_Model_Qacar
     */
    protected function _initQacar()
    {
        $qacarId  = (int) $this->getRequest()->getParam('id');
        $qacar    = Mage::getModel('bs_car/qacar');
        if ($qacarId) {
            $qacar->load($qacarId);
        }
        Mage::register('current_qacar', $qacar);
        return $qacar;
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
        $this->_title(Mage::helper('bs_car')->__('CAR'))
             ->_title(Mage::helper('bs_car')->__('QA Cars'));
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
     * edit qa car - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $qacarId    = $this->getRequest()->getParam('id');
        $qacar      = $this->_initQacar();
        if ($qacarId && !$qacar->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_car')->__('This qa car no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getQacarData(true);
        if (!empty($data)) {
            $qacar->setData($data);
        }
        Mage::register('qacar_data', $qacar);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_car')->__('CAR'))
             ->_title(Mage::helper('bs_car')->__('QA Cars'));
        if ($qacar->getId()) {
            $this->_title($qacar->getCarNo());
        } else {
            $this->_title(Mage::helper('bs_car')->__('Add qa car'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new qa car action
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
     * save qa car - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('qacar')) {
            try {
                $data = $this->_filterDates($data, array('car_date' ,'expire_date'));
                $qacar = $this->_initQacar();
                $qacar->addData($data);
                $qacar->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('QA Car was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $qacar->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setQacarData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was a problem saving the qa car.')
                );
                Mage::getSingleton('adminhtml/session')->setQacarData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_car')->__('Unable to find qa car to save.')
        );
        $this->_redirect('*/*/');
    }

    public function generateAction()
    {

        if ($id = $this->getRequest()->getParam('id')) {
            $car = Mage::getModel('bs_car/qacar')->load($id);

            $this->generateCar($car);
        }
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

    }

    public function generateCar($car, $compress)
    {

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('2033');
        //$template = Mage::getBaseDir('media').DS.'templates'.DS.'8005.docx';

        $name = $car->getCarNo() . '_2033_CORRECTIVE ACTION REQUEST';

        $no = $car->getCarNo();


        $startDate = $car->getCarDate();
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

        $finishDate = $car->getExpireDate();
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

        $listvars = array();
        $description = $car->getDescription();
        $description = explode("\r\n", $description);

        if(count($description)){
            foreach ($description as $key => $value){
                $description[$key] = '- '.$value;
            }
            $listvars['description'] = $description;
        }



        $root = $car->getRootCause();
        $root = explode("\r\n", $root);
        if(count($root)){
            foreach ($root as $key => $value){
                $root[$key] = '- '.$value;
            }
            $listvars['root'] = $root;
        }

        $corrective = $car->getCorrective();
        $corrective = explode("\r\n", $corrective);
        if(count($corrective)){
            foreach ($corrective as $key => $value){
                $corrective[$key] = '- '.$value;
            }
            $listvars['corrective'] = $corrective;
        }

        $preventive = $car->getPreventive();
        $preventive = explode("\r\n", $preventive);
        if(count($preventive)){
            foreach ($preventive as $key => $value){
                $preventive[$key] = '- '.$value;
            }
            $listvars['preventive'] = $preventive;
        }




        $data = array(
            'no' => $no,
            'date' => $startDate,
            'sendto' => $car->getSendto(),
            'auditor' => $car->getAuditor(),
            'auditee' => $car->getAuditee(),
            'ref' => $car->getRef(),
            'level' => $car->getLevel(),
            'nc' => $car->getNc(),
            'expire' => $finishDate,




        );


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx($name, $template, $data,null,null,$listvars);
            if($compress){
                $files = array();
                $files[] = $res['url'];
                return $files;
            }else {
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_car')->__('Click <a target="_blank" href="%s">%s</a>.', $res['url'], $res['name'])
                );
            }




        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
    }

    public function massGenerateCarAction()
    {

        $cars = (array)$this->getRequest()->getParam('qacar');
        $compress = $this->getRequest()->getParam('compress');
        $files = array();

        try {
            foreach ($cars as $id) {
                $car = Mage::getModel('bs_car/qacar')->load($id);


                if($compress){
                    $files = array_merge($files,$this->generateCar($car, $compress));
                }else {
                    $this->generateCar($car);
                }
            }
            if($compress){
                $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'cars_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                if($zip){
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_car')->__('Generated files have been zipped. Click <a href="%s">%s</a> to download.', $zip, 'HERE')
                    );
                }
            }

        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('bs_car')->__('An error occurred while generating the files.')
            );
        }
        $this->_redirect('*/*/');
    }

    /**
     * delete qa car - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $qacar = Mage::getModel('bs_car/qacar');
                $qacar->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('QA Car was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error deleting qa car.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_car')->__('Could not find qa car to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete qa car - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $qacarIds = $this->getRequest()->getParam('qacar');
        if (!is_array($qacarIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_car')->__('Please select qa cars to delete.')
            );
        } else {
            try {
                foreach ($qacarIds as $qacarId) {
                    $qacar = Mage::getModel('bs_car/qacar');
                    $qacar->setId($qacarId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('Total of %d qa cars were successfully deleted.', count($qacarIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error deleting qa cars.')
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
        $qacarIds = $this->getRequest()->getParam('qacar');
        if (!is_array($qacarIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_car')->__('Please select qa cars.')
            );
        } else {
            try {
                foreach ($qacarIds as $qacarId) {
                $qacar = Mage::getSingleton('bs_car/qacar')->load($qacarId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d qa cars were successfully updated.', count($qacarIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error updating qa cars.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $fileName   = 'qacar.csv';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_qacar_grid')
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
        $fileName   = 'qacar.xls';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_qacar_grid')
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
        $fileName   = 'qacar.xml';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_qacar_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_car/qacar');
    }
}
