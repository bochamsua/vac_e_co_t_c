<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$instructors = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection();

foreach ($instructors as $instructor) {
    $vaecoId = $instructor->getVaecoId();

    $staff= Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
    if($staff->getId()){
        $customer = Mage::getModel('customer/customer')->load($staff->getId());
        $username = $customer->getUsername();
        $check = checkUsername($username);
        if(!$check){
            $pass = strtolower($vaecoId);
            $uRoles = array(656);
            $dataUser = array(
                'username'  => $username,
                'firstname' => $customer->getFirstname(),
                'lastname'  => $customer->getLastname(),
                'fullname'  => $customer->getName(),
                'email'     => $username.$pass.'@gmail.com',
                'password'  => $pass,
                'password_confirmation' => $pass,
                'is_active' => true,
                'roles' => $uRoles,
                'user_roles' => '',
                'role_name' => '',
                'is_instructor' => true
            );

            $model = Mage::getModel('admin/user');

            $model->setData($dataUser);

            $model->save();
            $model->setRoleIds($uRoles)
                ->setRoleUserId($model->getUserId())
                ->saveRelations();

            echo "Done {$vaecoId} <br> \n";
        }else {
            echo "{$vaecoId} existed! <br> \n";
        }

    }



}

function checkUsername($username){
    $adminUser = Mage::getModel('admin/user')->getCollection()->addFieldToFilter('username', $username)->getFirstItem();
    if($adminUser->getId()){
        return true;
    }
    return false;

}
