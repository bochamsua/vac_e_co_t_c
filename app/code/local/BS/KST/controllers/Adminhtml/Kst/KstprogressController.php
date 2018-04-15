<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_KstprogressController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the progress
     *
     * @access protected
     * @return BS_KST_Model_Kstprogress
     */
    protected function _initKstprogress()
    {
        $kstprogressId  = (int) $this->getRequest()->getParam('id');
        $kstprogress    = Mage::getModel('bs_kst/kstprogress');
        if ($kstprogressId) {
            $kstprogress->load($kstprogressId);
        }
        Mage::register('current_kstprogress', $kstprogress);
        return $kstprogress;
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
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Progresses'));
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
     * edit progress - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {

        $kstprogressIds = $this->getRequest()->getParam('kstprogress');

        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
            ->_title(Mage::helper('bs_kst')->__('Progresses'));




        $kstprogressId    = $this->getRequest()->getParam('id');
        $kstprogress      = $this->_initKstprogress();
        $data = Mage::getSingleton('adminhtml/session')->getKstprogressData(true);
        if (!empty($data)) {
            $kstprogress->setData($data);
        }
        Mage::register('kstprogress_data', $kstprogress);

        if ($kstprogressId && !$kstprogress->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This progress no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }


        if ($kstprogress->getId()) {
            $this->_title($kstprogress->getAcReg());
        }


        if($kstprogressIds){

//            $courseId  = $this->getRequest()->getParam('course_id', false);
//            if(!$courseId){
//                $this->_getSession()->addError(
//                    Mage::helper('bs_kst')->__('You are not allowed to perform this action!')
//                );
//                $this->_redirect('*/kst_kstprogress/');
//                return;
//            }

            $this->_title(Mage::helper('bs_kst')->__('Update tasks progress'));

        }




        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new progress action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function viewAction()
    {


        $this->loadLayout();
        $this->renderLayout();
    }
    public function listAction()
    {


        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * save progress - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('kstprogress')) {
            try {


                $data = $this->_filterDates($data, array('complete_date'));
                $courseId = $data['course_id'];

                if(isset($data['task_ids'])){//update all action

                    $taskIds = $data['task_ids'];
                    $acReg = $data['ac_reg'];
                    $instructor = $data['instructor'];
                    $completeDate = $data['complete_date'];
                    $status = $data['task_status'];
                    $feedback = $data['trainee_feedback'];

                    $taskIds = explode(",", $taskIds);
                    foreach ($taskIds as $kstprogressId) {
                        $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId);
                        $kstprogress->setStatus(1);
                        $kstprogress->setInstructorId($instructor);
                        $kstprogress->setAcReg($acReg);
                        $kstprogress->setCompleteDate($completeDate);
                        $kstprogress->setTraineeFeedback($feedback);

                        $kstprogress->setIsMassupdate(true)
                            ->save();
                    }


                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_kst')->__('%s items have been updated', count($taskIds))
                    );


                    $this->_redirect('*/kst_kstprogress/index/', array('course_id'=>$courseId));
                    return;


                }else {
                    $sku = Mage::getSingleton('catalog/product')->load($courseId)->getSku();

                    //check existing data
                    $progress = Mage::getModel('bs_kst/kstprogress')->getCollection()->addFieldToFilter('course_id', $courseId);
                    if($progress->count()){
                        Mage::getSingleton('adminhtml/session')->addError('The items for this course have already been filled out!');
                        $this->_redirect('*/*/new');
                        return;
                    }

                    //get all groups

                    $groups = Mage::getModel('bs_kst/kstgroup')->getCollection()->addFieldToFilter('course_id', $courseId);
                    if(!$groups->count()){
                        //Add groups and members first

                        $newGroupIds = array();
                        if(isset($data['group1'])){
                            $members1 = $data['group1'];
                            $leader1 = $data['leader1'];

                            //Save group first
                            $group = Mage::getModel('bs_kst/kstgroup');
                            $group->setName('Group 1');
                            $group->setCourseId($courseId);
                            $group->save();

                            $gId1 = $group->getId();
                            $newGroupIds[] = $gId1;

                            //save members
                            if(count($leader1) == 1){//Ok, should have only one leader

                                //make sure that the leader is in members
                                if(in_array($leader1[0], $members1)){

                                    foreach ($members1 as $item) {

                                        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                        if($customer->getId()) {
                                            $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                            $name = $cus->getName();
                                            $username = $cus->getUsername();


                                        }else {//this is for order trainees from other companies
                                            $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $item)->getFirstItem();
                                            if($tn->getId()){
                                                $trainee = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                                                $name = $trainee->getTraineeName();
                                                $username = strtolower($item).'-'.Mage::helper('bs_traininglist')->convertToUnsign($name, true);
                                            }
                                        }

                                        $model = Mage::getModel('bs_kst/kstmember');
                                        $model->setCourseId($courseId);
                                        $model->setKstgroupId($gId1);
                                        $model->setName($name);
                                        $model->setVaecoId($item);
                                        $model->setUsername($username);

                                        if($item == $leader1[0]){
                                            $model->setIsLeader(true);
                                        }

                                        $model->save();


                                    }
                                }else {
                                    $this->redirectFail();
                                    return;
                                }
                            }else {

                                $this->redirectFail();
                                return;
                            }



                        }

                        if(isset($data['group2'])){
                            $members2 = $data['group2'];
                            $leader2 = $data['leader2'];

                            //Save group first
                            $group = Mage::getModel('bs_kst/kstgroup');
                            $group->setName('Group 2');
                            $group->setCourseId($courseId);
                            $group->save();

                            $gId2 = $group->getId();
                            $newGroupIds[] = $gId2;

                            //save members
                            if(count($leader2) == 1){//Ok, should have only one leader

                                //make sure that the leader is in members
                                if(in_array($leader2[0], $members2)){

                                    foreach ($members2 as $item) {

                                        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                        if($customer->getId()) {
                                            $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                            $name = $cus->getName();
                                            $username = $cus->getUsername();

                                            $model = Mage::getModel('bs_kst/kstmember');
                                            $model->setCourseId($courseId);
                                            $model->setKstgroupId($gId2);
                                            $model->setName($name);
                                            $model->setVaecoId($item);
                                            $model->setUsername($username);

                                            if($item == $leader2[0]){
                                                $model->setIsLeader(true);
                                            }

                                            $model->save();
                                        }


                                    }
                                }else {
                                    $this->redirectFail();
                                    return;
                                }
                            }else {

                                $this->redirectFail();
                                return;
                            }



                        }

                        if(isset($data['group3'])){
                            $members3 = $data['group3'];
                            $leader3 = $data['leader3'];

                            //Save group first
                            $group = Mage::getModel('bs_kst/kstgroup');
                            $group->setName('Group 3');
                            $group->setCourseId($courseId);
                            $group->save();

                            $gId3 = $group->getId();
                            $newGroupIds[] = $gId3;

                            //save members
                            if(count($leader3) == 1){//Ok, should have only one leader

                                //make sure that the leader is in members
                                if(in_array($leader3[0], $members3)){

                                    foreach ($members3 as $item) {

                                        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                        if($customer->getId()) {
                                            $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                            $name = $cus->getName();
                                            $username = $cus->getUsername();

                                            $model = Mage::getModel('bs_kst/kstmember');
                                            $model->setCourseId($courseId);
                                            $model->setKstgroupId($gId3);
                                            $model->setName($name);
                                            $model->setVaecoId($item);
                                            $model->setUsername($username);

                                            if($item == $leader2[0]){
                                                $model->setIsLeader(true);
                                            }

                                            $model->save();
                                        }


                                    }
                                }else {
                                    $this->redirectFail();
                                    return;
                                }
                            }else {

                                $this->redirectFail();
                                return;
                            }



                        }


                        $groups = Mage::getModel('bs_kst/kstgroup')->getCollection()->addFieldToFilter('entity_id', array('in'=>$newGroupIds));

                    }



                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($courseId);

                    $curriculumId = $curriculum->getFirstItem()->getId();

                    $subjects = Mage::getModel('bs_kst/kstsubject')->getCollection()->addFieldToFilter('curriculum_id', $curriculumId)->setOrder('position', 'ASC');

                    if($groups->count()){
                        $err = array();
                        foreach ($groups as $group) {

                            //get leader
                            $leader = Mage::getModel('bs_kst/kstmember')->getCollection()->addFieldToFilter('kstgroup_id', $group->getId())->addFieldToFilter('is_leader', 1)->getFirstItem();

                            if($leader->getId()){
                                $username = $leader->getUsername();

                                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $leader->getVaecoId())->getFirstItem();
                                if($staff->getId()){
                                    //we need to check if this username existed or not
                                    $check = $this->checkUsername($username);
                                    if(!$check){//if not exist, create new user

                                        $customer = Mage::getModel('customer/customer')->load($staff->getId());
                                        $uRoles = array(656);
                                        $dataUser = array(
                                            'username'  => $username,
                                            'firstname' => $customer->getFirstname(),
                                            'lastname'  => $customer->getLastname(),
                                            'fullname'  => $customer->getName(),
                                            'email'     => $username.'@gmail.com',
                                            'password'  => $username,
                                            'password_confirmation' => $username,
                                            'is_active' => true,
                                            'roles' => $uRoles,
                                            'user_roles' => '',
                                            'role_name' => ''
                                        );

                                        $model = Mage::getModel('admin/user');

                                        $model->setData($dataUser);

                                        $model->save();
                                        $model->setRoleIds($uRoles)
                                            ->setRoleUserId($model->getUserId())
                                            ->saveRelations();
                                    }
                                }else {//the we will check trainee data
                                    $check = $this->checkUsername($username);
                                    if(!$check){//if not exist, create new user

                                        $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $item)->getFirstItem();
                                        if($tn->getId()){
                                            $uRoles = array(656);
                                            $tnModel = Mage::getModel('bs_trainee/trainee')->load($tn->getId());

                                            $tnName = $tnModel->getTraineeName();
                                            $array = explode(" ", $tnName);
                                            $firstName = $array[0];
                                            unset($array[0]);
                                            $lastName = implode(" ", $array);

                                            $dataUser = array(
                                                'username'  => $username,
                                                'firstname' => $firstName,
                                                'lastname'  => $lastName,
                                                'fullname'  => $tnName,
                                                'email'     => $username.'@gmail.com',
                                                'password'  => $username,
                                                'password_confirmation' => $username,
                                                'is_active' => true,
                                                'roles' => $uRoles,
                                                'user_roles' => '',
                                                'role_name' => ''
                                            );

                                            $model = Mage::getModel('admin/user');

                                            $model->setData($dataUser);

                                            $model->save();
                                            $model->setRoleIds($uRoles)
                                                ->setRoleUserId($model->getUserId())
                                                ->saveRelations();
                                        }

                                    }
                                }


                            }


                            if($subjects->count()){
                                foreach ($subjects as $subject) {
                                    $subId = $subject->getId();

                                    $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('kstsubject_id', $subId)->setOrder('position', 'ASC');

                                    $i=1;
                                    foreach ($items as $item) {

                                        $progress = Mage::getModel('bs_kst/kstprogress');
                                        $progress->setCourseId($courseId)
                                            ->setSku($sku)
                                            ->setGroupId($group->getId())
                                            ->setKstitemId($item->getId())
                                            ->setKstsubjectId($subId)
                                            ->setSubjectName($subject->getName())
                                            ->setSubjectPosition($subject->getPosition())
                                            ->setItemName($item->getName())
                                            ->setItemRef($item->getRef())
                                            ->setItemTaskcode($item->getTaskcode())
                                            ->setItemTaskcat($item->getTaskcat())
                                            ->setItemApplicable($item->getApplicableFor())
                                            ->setItemPosition($item->getPosition())
                                            ->setPosition($i)
                                            ->setStatus(0)

                                        ;

                                        $progress->save();

                                        $i++;
                                    }
                                }
                            }else {
                                $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('curriculum_id', $curriculumId)->setOrder('position', 'ASC');

                                $i=1;
                                foreach ($items as $item) {

                                    $progress = Mage::getModel('bs_kst/kstprogress');
                                    $progress->setCourseId($courseId)
                                        ->setSku($sku)
                                        ->setGroupId($group->getId())
                                        ->setKstitemId($item->getId())
                                        ->setKstsubjectId(null)
                                        ->setSubjectName(null)
                                        ->setSubjectPosition(null)
                                        ->setItemName($item->getName())
                                        ->setItemRef($item->getRef())
                                        ->setItemTaskcode($item->getTaskcode())
                                        ->setItemTaskcat($item->getTaskcat())
                                        ->setItemApplicable($item->getApplicableFor())
                                        ->setItemPosition($item->getPosition())
                                        ->setPosition($i)
                                        ->setStatus(0)

                                    ;

                                    $progress->save();
                                    $i++;
                                }
                            }

                        }



                    }

                    /*else {
                        if($subjects->count()){
                            foreach ($subjects as $subject) {
                                $subId = $subject->getId();

                                $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('kstsubject_id', $subId)->setOrder('position', 'ASC');

                                $i=1;
                                foreach ($items as $item) {

                                    $progress = Mage::getModel('bs_kst/kstprogress');
                                    $progress->setCourseId($courseId)
                                        ->setSku($sku)
                                        ->setKstitemId($item->getId())
                                        ->setKstsubjectId($subId)
                                        ->setSubjectName($subject->getName())
                                        ->setSubjectPosition($subject->getPosition())
                                        ->setItemName($item->getName())
                                        ->setItemRef($item->getRef())
                                        ->setItemTaskcode($item->getTaskcode())
                                        ->setItemTaskcat($item->getTaskcat())
                                        ->setItemApplicable($item->getApplicableFor())
                                        ->setItemPosition($item->getPosition())
                                        ->setPosition($i)
                                        ->setStatus(0)

                                    ;

                                    $progress->save();
                                    $i++;
                                }
                            }
                        }else {
                            $items = Mage::getModel('bs_kst/kstitem')->getCollection()->addFieldToFilter('curriculum_id', $curriculumId)->setOrder('position', 'ASC');

                            $i=1;
                            foreach ($items as $item) {

                                $progress = Mage::getModel('bs_kst/kstprogress');
                                $progress->setCourseId($courseId)
                                    ->setSku($sku)
                                    ->setKstitemId($item->getId())
                                    ->setKstsubjectId(null)
                                    ->setSubjectName(null)
                                    ->setSubjectPosition(null)
                                    ->setItemName($item->getName())
                                    ->setItemRef($item->getRef())
                                    ->setItemTaskcode($item->getTaskcode())
                                    ->setItemTaskcat($item->getTaskcat())
                                    ->setItemApplicable($item->getApplicableFor())
                                    ->setItemPosition($item->getPosition())
                                    ->setPosition($i)
                                    ->setStatus(0)

                                ;

                                $progress->save();
                                $i++;
                            }
                        }
                    }*/





                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_kst')->__('All items have been filled out!')
                    );
                    Mage::getSingleton('adminhtml/session')->setFormData(false);

                    $this->_redirect('*/*/view');
                    return;
                }




            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setKstprogressData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the progress.')
                );
                Mage::getSingleton('adminhtml/session')->setKstprogressData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find progress to save.')
        );
        $this->_redirect('*/*/');
    }

    function redirectFail(){
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Please check your data! Members in each group are unique. Each group must have only one leader')
        );


        $this->_redirect('*/*/new/');
        return;
    }
    public function getGroupsAction(){
        $result = array();

        $courseId = $this->getRequest()->getPost('course_id');
        $numberGroup = $this->getRequest()->getPost('number_group');
        $result['groups'] = '';
        if($courseId){
            //$course = Mage::getModel('catalog/product')->load($courseId);

            $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($courseId);
            if($trainees->count()){
                $groups = '';
                $leaders = '';
                for($i=1; $i<= $numberGroup; $i++){
                    $groups .= '<label>Group '.$i.'</label>
                                <select class="select required-entry multiselect" multiple="multiple" name="kstprogress[group'.$i.'][]">';
                    $leaders .= '<label>Leader '.$i.'</label>
                                <select class="select required-entry multiselect" multiple="multiple" name="kstprogress[leader'.$i.'][]">';
                    foreach ($trainees as $trainee) {
                        $tn = Mage::getModel('bs_trainee/trainee')->load($trainee->getId());

                        $vaecoId = $tn->getVaecoId();
                        if($vaecoId == ''){
                            $vaecoId = $tn->getTraineeCode();
                        }
                        $name = $tn->getTraineeName();

                        $groups .= '<option value="'.$vaecoId.'">'.$name.'</option>';
                        $leaders .= '<option value="'.$vaecoId.'">'.$name.'</option>';


                    }

                    $groups .= '</select>';
                    $leaders .= '</select>';
                }



                $result['groups'] = $groups.$leaders;
            }

        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    protected function checkUsername($username){
        $adminUser = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('username', $username)->getFirstItem();
        if($adminUser->getId()){
            return true;
        }
        return false;

    }

    /**
     * delete progress - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $kstprogress = Mage::getModel('bs_kst/kstprogress');
                $kstprogress->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Progress was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting progress.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find progress to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete progress - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses to delete.')
            );
        } else {
            try {
                foreach ($kstprogressIds as $kstprogressId) {
                    $kstprogress = Mage::getModel('bs_kst/kstprogress');
                    $kstprogress->setId($kstprogressId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d progresses were successfully deleted.', count($kstprogressIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting progresses.')
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
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select items.')
            );
        } else {
            try {
                $status = $this->getRequest()->getParam('status');
                if($status == 0){
                    $status = 1;
                }elseif($status == 9999){
                    $status = 0;
                }
                foreach ($kstprogressIds as $kstprogressId) {
                    $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId);

                    $courseId = $kstprogress->getCourseId();
                    $kstprogress->setStatus($status)
                                ->setIsMassupdate(true)
                                ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d items were successfully updated.', count($kstprogressIds))
                );
                $this->_redirect('*/*/index', array('course_id'=>$courseId));
                return;

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating items.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass subject change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massKstsubjectIdAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses.')
            );
        } else {
            try {
                foreach ($kstprogressIds as $kstprogressId) {
                $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId)
                    ->setKstsubjectId($this->getRequest()->getParam('flag_kstsubject_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d progresses were successfully updated.', count($kstprogressIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating progresses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massInstructorAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses.')
            );
        } else {
            try {
                $instructorId = $this->getRequest()->getParam('instructor');
                foreach ($kstprogressIds as $kstprogressId) {
                    $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId);

                    $courseId = $kstprogress->getCourseId();

                    $kstprogress
                        ->setInstructorId($instructorId)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d progresses were successfully updated.', count($kstprogressIds))
                );

                $this->_redirect('*/*/index', array('course_id'=>$courseId));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating progresses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massAircraftAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses.')
            );
        } else {
            try {
                $aircraft = $this->getRequest()->getParam('aircraft');
                foreach ($kstprogressIds as $kstprogressId) {
                    $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId);

                    $courseId = $kstprogress->getCourseId();

                    $kstprogress
                        ->setAcReg($aircraft)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d progresses were successfully updated.', count($kstprogressIds))
                );

                $this->_redirect('*/*/index', array('course_id'=>$courseId));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating progresses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massCompleteDateAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('complete_date');
                $dates = array('input_date'=>$date);

                $dates = $this->_filterDates($dates,array('input_date'));

                $date = $dates['input_date'];

                foreach ($kstprogressIds as $kstprogressId) {
                    $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId);

                    $courseId = $kstprogress->getCourseId();

                    $kstprogress
                        ->setCompleteDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d progresses were successfully updated.', count($kstprogressIds))
                );

                $this->_redirect('*/*/index', array('course_id'=>$courseId));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating progresses.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass item change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massKstitemIdAction()
    {
        $kstprogressIds = $this->getRequest()->getParam('kstprogress');
        if (!is_array($kstprogressIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select progresses.')
            );
        } else {
            try {
                foreach ($kstprogressIds as $kstprogressId) {
                $kstprogress = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId)
                    ->setKstitemId($this->getRequest()->getParam('flag_kstitem_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d progresses were successfully updated.', count($kstprogressIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating progresses.')
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
        $fileName   = 'kstprogress.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstprogress_grid')
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
        $fileName   = 'kstprogress.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstprogress_grid')
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
        $fileName   = 'kstprogress.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstprogress_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/kstprogress/view');
    }
}
