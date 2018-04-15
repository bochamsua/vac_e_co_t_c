<?php
/**
 * BS_Tools extension
 * 
 * @category       BS
 * @package        BS_Tools
 * @copyright      Copyright (c) 2015
 */
/**
 * Get Info admin controller
 *
 * @category    BS
 * @package     BS_Tools
 * @author Bui Phong
 */
class BS_Tools_Adminhtml_Tools_GetinfoController extends BS_Tools_Controller_Adminhtml_Tools
{
    /**
     * init the get info
     *
     * @access protected
     * @return BS_Tools_Model_Getinfo
     */
    protected function _initGetinfo()
    {
        $getinfoId  = (int) $this->getRequest()->getParam('id');
        $getinfo    = Mage::getModel('bs_tools/getinfo');
        if ($getinfoId) {
            $getinfo->load($getinfoId);
        }
        Mage::register('current_getinfo', $getinfo);
        return $getinfo;
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
        $this->_title(Mage::helper('bs_tools')->__('Miscellaneous'))
             ->_title(Mage::helper('bs_tools')->__('Get Info'));
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
     * edit get info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $getinfoId    = $this->getRequest()->getParam('id');
        $getinfo      = $this->_initGetinfo();
        if ($getinfoId && !$getinfo->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tools')->__('This get info no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getGetinfoData(true);
        if (!empty($data)) {
            $getinfo->setData($data);
        }
        Mage::register('getinfo_data', $getinfo);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_tools')->__('Miscellaneous'))
             ->_title(Mage::helper('bs_tools')->__('Get Info'));
        if ($getinfo->getId()) {
            $this->_title($getinfo->getVaecoIds());
        } else {
            $this->_title(Mage::helper('bs_tools')->__('Add get info'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new get info action
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
     * save get info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('getinfo')) {
            try {

                $vaecoIds = explode("\r\n", $data['vaeco_ids']);
                $type = $data['action_type'];
                $option = $data['option'];

                $result = '';

                if($type == 1){

                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                                $name = $cus->getName();
                                $nameUpper = mb_strtoupper($name, 'UTF-8');
                                $phone = $cus->getPhone();
                                $username = $cus->getUsername();
                                $position = $cus->getPosition();
                                $division = $cus->getDivision();

                                $strUsername[] = $username;

                                $nameArray = explode(" ", $name);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;


                                $groupId = $cus->getGroupId();
                                $group = Mage::getModel('customer/group')->load($groupId);
                                $dept = $group->getCustomerGroupCode();

                                $list[] = $j.'. '.$name.' - '.$vaecoId;

                            }



                            $j++;
                        }

                    }
                    $result = 'Username: '.implode(", ", $strUsername);
                }elseif ($type == 2){
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>STT</td>
                                <td>1.VAECO ID</td>
                                <td>2.Name</td>
                                <td>3.Phone</td>
                                <td>4.Position</td>
                                <td>5.Division</td>
                                <td>6.Department</td>
                                <td>7.Username</td>
                                <td>8.Birthday</td>
                                <td>9.Place of birth</td>
                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                                $name = $cus->getName();
                                $nameUpper = mb_strtoupper($name, 'UTF-8');
                                $phone = $cus->getPhone();
                                $username = $cus->getUsername();
                                $position = $cus->getPosition();
                                $division = $cus->getDivision();

                                $pob = $cus->getPob();

                                $strUsername[] = $username;

                                $nameArray = explode(" ", $name);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;


                                $groupId = $cus->getGroupId();
                                $group = Mage::getModel('customer/group')->load($groupId);
                                $dept = $group->getCustomerGroupCode();

                                $list[] = $j.'. '.$name.' - '.$vaecoId;

                            }

                            $result .= "<tr><td>".$j."</td><td>".$vaecoId."</td><td>".$name."</td><td>".$phone."</td><td>".$position."</td><td>".$division."</td><td>".$dept."</td><td>".$username."</td><td>".$dob."</td><td>".$pob."</td></tr>";


                            $j++;
                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 3){
                    $j=1;
                    $strUsername = array();
                    $result = '';
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                                $name = $cus->getName();
                                $nameUpper = mb_strtoupper($name, 'UTF-8');
                                $phone = $cus->getPhone();
                                $username = $cus->getUsername();
                                $position = $cus->getPosition();
                                $division = $cus->getDivision();

                                $strUsername[] = $username;

                                $nameArray = explode(" ", $name);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;

                                $fileLocation = '';
                                $ins = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                                if($ins->getId()){
                                    $instructor = Mage::getModel('bs_instructor/instructor')->load($ins->getId());
                                    $fileLocation = $instructor->getIfilefolder();
                                }

                                $function = $customer->getStaffFunction();
                                if($function == 3){
                                    $function = 'Avionics';
                                }elseif($function == 4){
                                    $function = 'Mechanic';
                                }

                                $groupId = $cus->getGroupId();
                                $group = Mage::getModel('customer/group')->load($groupId);
                                $dept = $group->getCustomerGroupNameVi();
                                $dept = str_replace("Trung tâm Bảo dưỡng","TTBD", $dept);

                                $list[] = $j.'. '.$name.' - '.$vaecoId;

                            }

                            $table = "<table border='1'>";
                            $table .= "<tr><td>1. Name:</td><td>".$name."</td><td>3. Date of Birth:</td><td>".$dob."</td><td>5. Mobile phone:</td><td>".$phone."</td><td>7. Instructor No.</td><td>&nbsp;</td></tr>";
                            $table .= "<tr><td>2. Company ID:</td><td>".$vaecoId."</td><td>4. Dept./Center:</td><td>".$dept."</td><td>6. Location:</td><td>".$fileLocation."</td><td>8. Update.</td><td>&nbsp;</td></tr>";
                            $table .= "<tr><td>9. Function (M/A):</td><td>".$function."</td></tr>";
                            $table.= "</table><br>";

                            $result .= $table;
                            $j++;
                        }

                    }
                }elseif ($type == 4){
                    $j=1;
                    $strUsername = array();
                    $result = '';
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;

                            $ins = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                            if($ins->getId()){
                                $instructor = Mage::getModel('bs_instructor/instructor')->load($ins->getId());
                                $name = $instructor->getIname();

                                $result .= '<strong>'.$j.'. '.$name.' - '.$id.' is an instructor.</strong><br>';

                                $approvedCourses = Mage::getModel('bs_instructor/instructor_curriculum')->getCollection()->addInstructorFilter($ins->getId());
                                if($approvedCourses->count()){
                                    $result .= 'Approved Courses: <br>';
                                    foreach ($approvedCourses as $c) {
                                        $cu = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());
                                        $result .= '- '.$cu->getCName().'<br>';
                                    }


                                }
                                $result .=  '<br><br>';



                            }else {
                                $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                                if($customer->getId()) {
                                    $cus = Mage::getModel('customer/customer')->load($customer->getId());

                                    $result .=  '<strong style="color: red;">'.$j.'. '.$cus->getName().' - '.$id.' is NOT an instructor! </strong> <br>';
                                }
                            }
                            $j++;
                        }

                    }
                }elseif ($type == 5){
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                                $name = $cus->getName();
                                $nameUpper = mb_strtoupper($name, 'UTF-8');
                                $phone = $cus->getPhone();
                                $username = $cus->getUsername();
                                $position = $cus->getPosition();
                                $division = $cus->getDivision();

                                $strUsername[] = $username;

                                $nameArray = explode(" ", $name);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;


                                $groupId = $cus->getGroupId();
                                $group = Mage::getModel('customer/group')->load($groupId);
                                $dept = $group->getCustomerGroupCode();

                                $list[] = $j.'. '.$name.' - '.$vaecoId;

                            }



                            $j++;
                        }

                    }
                    $result = implode("<br>", $list);
                }elseif ($type == 6){
                    $j=1;
                    $strUsername = array();
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>1.Name</td>
                                <td>2.VAECO ID</td>
                            </tr></thead>";
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                                $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                                $name = $cus->getName();
                                $nameUpper = mb_strtoupper($name, 'UTF-8');
                                $phone = $cus->getPhone();
                                $username = $cus->getUsername();
                                $position = $cus->getPosition();
                                $division = $cus->getDivision();

                                $strUsername[] = $username;

                                $nameArray = explode(" ", $name);
                                $last = $nameArray[count($nameArray)-1];

                                for($i=0; $i< count($nameArray)-1; $i++){
                                    $shortName .= $nameArray[$i][0].'.';
                                }
                                $shortName .= $last;


                                $groupId = $cus->getGroupId();
                                $group = Mage::getModel('customer/group')->load($groupId);
                                $dept = $group->getCustomerGroupCode();

                                $name = Mage::helper('bs_traininglist')->convertToUnsign($name,false,true);

                                $result .= "<tr><td>".$name."</td><td>".$vaecoId."</td></tr>";


                            }



                            $j++;
                        }

                    }
                    $result .= "</table>";
                }elseif ($type == 7){//search by name
                    $j=1;
                    $strUsername = array();
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>1.Name</td>
                                <td>2.VAECO ID</td>
                                <td>3.Username</td>
                                <td>4.Division - Dept.</td>
                            </tr></thead>";
                    foreach ($vaecoIds as $id) {
                        $nameO = trim($id, $charlist = " .;,-\t\n\r\0\x0B");
                        $names = array($nameO);
                        $newName = strtolower($nameO);
                        if(strpos("".$newName, "d")){
                            $newName = str_replace("d", "đ", $newName);
                            $names[] = $newName;
                        }
                        foreach ($names as $name) {
                            $customer = Mage::getModel('customer/customer')->getCollection()->addNameToSelect()->addAttributeToFilter('name', array('like'=>'%'.$name.'%'));
                            //$sql = $customer->getSelect()->__toString();

                            if($customer->count()){
                                foreach ($customer as $item) {
                                    $cus = Mage::getModel('customer/customer')->load($item->getId());
                                    $dob = $cus->getDob();
                                    $vaecoId = $cus->getVaecoId();


                                    $name = $cus->getName();
                                    $nameUpper = mb_strtoupper($name, 'UTF-8');

                                    $username = $cus->getUsername();
                                    $position = $cus->getPosition();
                                    $division = $cus->getDivision();




                                    $groupId = $cus->getGroupId();
                                    $group = Mage::getModel('customer/group')->load($groupId);
                                    $dept = $group->getCustomerGroupCode();

                                    //$name = Mage::helper('bs_traininglist')->convertToUnsign($name,false,true);

                                    $result .= "<tr><td>".$name."</td><td>".$vaecoId."</td><td>".$username."</td><td>".$division." - ".$dept."</td></tr>";
                                    $j++;
                                }



                            }
                        }

                    }
                    $result .= "</table>";
                }elseif ($type == 8){//get date
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>Date</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '&nbsp;';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $dob = $cus->getDob();
                                $dob = Mage::getModel('core/date')->date("d-M-Y", $dob);




                            }

                            $result .= "<tr><td>".$dob."</td></tr>";


                            $j++;
                        }

                    }
                    $result.= "</table>";
                }elseif($type == 9){
                    $j=1;
                    $strUsername = array();
                    $txt = '';
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($trainee->getId()){

                                $tnId = $trainee->getId();
                                $resource = Mage::getSingleton('core/resource');
                                //$writeConnection = $resource->getConnection('core_write');
                                $readConnection = $resource->getConnection('core_read');

                                $tableTnPr = $resource->getTableName('bs_trainee/trainee_product');
                                $courses = $readConnection->fetchCol("SELECT product_id FROM {$tableTnPr} WHERE trainee_id = {$tnId} ORDER BY position ASC");


                                $tn = Mage::getModel('bs_trainee/trainee')->load($trainee->getId());
                                $tnName = $tn->getTraineeName();

                                $txt .= $j.'. '.$tnName.'<br>';
                                
                                if(count($courses)){
                                    foreach ($courses as $course) {
                                        $c = Mage::getModel('catalog/product')->load($course);

                                        $txt .= '- <a target="_blank" href="'.$this->getUrl('*/catalog_product/edit', array('id'=>$c->getId())).'">'.$c->getSku().'</a><br>';
                                    }
                                }

                            }



                            $j++;
                        }

                    }
                    $result = $txt;
                }elseif ($type == 10){//get pob
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>Date</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                            $id = strtoupper($id);

                            $vaecoId = $id;
                            $name = '';
                            $dept = '';
                            $phone = '';
                            $username = '';
                            $dob = '&nbsp;';
                            $nameUpper = '';
                            $shortName = '';
                            $dobNew = '';


                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $pob = $cus->getPob();
                                if(strpos($pob,",")){
                                    $pob = explode(",", $pob);
                                    $pob = trim($pob[count($pob)-1]);
                                }elseif(strpos($pob,"-")){
                                    $pob = explode("-", $pob);
                                    $pob = trim($pob[count($pob)-1]);
                                }

                            }

                            $result .= "<tr><td>".$pob."</td></tr>";


                            $j++;
                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 11){//get phone
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>No.</td>
                                <td>Username</td>
                                <td>Phone</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strpos($id, ",")){
                                $id = explode(",", $id);
                            }else {
                                $id = array($id);
                            }

                            foreach ($id as $item) {
                                $item = trim($item);
                                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('username', $item)->getFirstItem();
                                if($staff->getId()){
                                    $customer = Mage::getModel('customer/customer')->load($staff->getId());

                                    $result .= "<tr><td>".$j."</td><td>".$item."</td><td>".$customer->getPhone()."</td></tr>";
                                }

                                $j++;
                            }



                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 12){//check train the trainer
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>No.</td>
                                <td>Name</td>
                                <td>VAECO ID</td>
                                <td>Train the trainer</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strpos($id, ",")){
                                $id = explode(",", $id);
                            }else {
                                $id = array($id);
                            }

                            foreach ($id as $item) {
                                $item = trim($item);
                                $related = Mage::helper('bs_instructorapproval')->getRelatedTraining($item, array('trainer', 'teaching'));
                                $valid = '';
                                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                if($related){
                                    $valid = 'X';
                                }
                                if($staff->getId()){
                                    $customer = Mage::getModel('customer/customer')->load($staff->getId());

                                    $result .= "<tr><td>".$j."</td><td>".$customer->getName()."</td><td>".$item."</td><td>".$valid."</td></tr>";
                                }

                                $j++;
                            }



                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 13){//check crs
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>No.</td>
                                <td>Name</td>
                                <td>VAECO ID</td>
                                <td>CRS</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strpos($id, "\t")){
                                $id = explode("\t", $id);
                            }else {
                                $id = array($id);
                            }

                            foreach ($id as $item) {
                                $item = trim($item);
                                $crs = Mage::helper('bs_instructorapproval')->getCRSInfo($item,$option);
                                $valid = '';
                                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                if($crs){
                                    $valid = $crs;
                                }
                                if($staff->getId()){
                                    $customer = Mage::getModel('customer/customer')->load($staff->getId());

                                    $result .= "<tr><td>".$j."</td><td>".$customer->getName()."</td><td>".$item."</td><td>".$valid."</td></tr>";
                                }

                                $j++;
                            }



                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 14){//check conducted courses
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>No.</td>
                                <td>Name</td>
                                <td>VAECO ID</td>
                                <td>Conducted Courses</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    if(!strpos($data['option'], "-")){
                        $data = Mage::helper('bs_instructorapproval')->filterDates($data, array('option'));
                    }

                    $date = $data['option'];
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strpos($id, "\t")){
                                $id = explode("\t", $id);
                            }else {
                                $id = array($id);
                            }

                            foreach ($id as $item) {
                                $item = trim($item);
                                $crs = Mage::helper('bs_instructorapproval')->getConductedCourses($item,$date);
                                $valid = '';
                                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $item)->getFirstItem();
                                if($crs){
                                    $valid = $crs;
                                }
                                if($staff->getId()){
                                    $customer = Mage::getModel('customer/customer')->load($staff->getId());

                                    $result .= "<tr><td>".$j."</td><td>".$customer->getName()."</td><td>".$item."</td><td>".$valid."</td></tr>";
                                }

                                $j++;
                            }



                        }

                    }
                    $result.= "</table>";
                }elseif ($type == 15){//get curriculum hours
                    $result = "<table border='1' cellpadding='3'><thead>
                            <tr>
                                <td>No.</td>
                                <td>Code</td>
                                <td>Hours</td>

                            </tr></thead>";
                    $j=1;
                    $strUsername = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if($id != ''){
                            if(strpos($id, "/YYZZ")){
                                $id = str_replace("/YYZZ", "", $id);
                            }
                            $hours = '';
                            $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('c_code', array('eq'=>$id))->getFirstItem();
                            if($curriculum->getId()){
                                $c = Mage::getModel('bs_traininglist/curriculum')->load($curriculum->getId());
                                $hours = $c->getCDuration();
                            }
                            $result .= "<tr><td>".$j."</td><td>".$id."</td><td>".$hours."</td></tr>";
                            $j++;



                        }

                    }
                    $result.= "</table>";
                }






                //$getinfo = $this->_initGetinfo();
                //$getinfo->addData($data);
                //$getinfo->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->setGetinfoData($data);

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tools')->__($result)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));

                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setGetinfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was a problem saving the get info.')
                );
                Mage::getSingleton('adminhtml/session')->setGetinfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tools')->__('Unable to find get info to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete get info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $getinfo = Mage::getModel('bs_tools/getinfo');
                $getinfo->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tools')->__('Get Info was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error deleting get info.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tools')->__('Could not find get info to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete get info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $getinfoIds = $this->getRequest()->getParam('getinfo');
        if (!is_array($getinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tools')->__('Please select get info to delete.')
            );
        } else {
            try {
                foreach ($getinfoIds as $getinfoId) {
                    $getinfo = Mage::getModel('bs_tools/getinfo');
                    $getinfo->setId($getinfoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tools')->__('Total of %d get info were successfully deleted.', count($getinfoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error deleting get info.')
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
        $getinfoIds = $this->getRequest()->getParam('getinfo');
        if (!is_array($getinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tools')->__('Please select get info.')
            );
        } else {
            try {
                foreach ($getinfoIds as $getinfoId) {
                $getinfo = Mage::getSingleton('bs_tools/getinfo')->load($getinfoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d get info were successfully updated.', count($getinfoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error updating get info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Action change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massActionTypeAction()
    {
        $getinfoIds = $this->getRequest()->getParam('getinfo');
        if (!is_array($getinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tools')->__('Please select get info.')
            );
        } else {
            try {
                foreach ($getinfoIds as $getinfoId) {
                $getinfo = Mage::getSingleton('bs_tools/getinfo')->load($getinfoId)
                    ->setActionType($this->getRequest()->getParam('flag_action_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d get info were successfully updated.', count($getinfoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tools')->__('There was an error updating get info.')
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
        $fileName   = 'getinfo.csv';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_getinfo_grid')
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
        $fileName   = 'getinfo.xls';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_getinfo_grid')
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
        $fileName   = 'getinfo.xml';
        $content    = $this->getLayout()->createBlock('bs_tools/adminhtml_getinfo_grid')
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
        return true;//Mage::getSingleton('admin/session')->isAllowed('bs_tools/getinfo');
    }
}
