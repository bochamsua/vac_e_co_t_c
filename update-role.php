<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
        table thead {
            background-color: #ccc;
        }
        table tr td {
            padding: 3px;
        }
    </style>
    <title>Update permissions!</title>
    <meta charset="utf-8" />
</head>
<body>
<?php
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$allRoles = Mage::getModel('admin/role')->getCollection()->addFieldToFilter('role_id', array('gt'=>1))->addFieldToFilter('role_type', 'G');
$roleStr = '';
foreach ($allRoles as $allRole) {
    $roleStr .= '<option value="'.$allRole->getId().'">'.$allRole->getRoleName().'</option>';
}

?>


<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Resources:<br><textarea name="resources" rows="5" cols="40"></textarea>
    <br>

    <p style="font-style: italic; color: blue;">
        Example:<br>
        admin/bs_tools<br>
        admin/bs_tools/getinfo<br>
        admin/bs_tools/getinfo/new<br>
        admin/bs_tools/getinfo/edit<br>
        admin/bs_tools/getinfo/delete
    </p>
    <br>
    Roles: <br>
    <select multiple="multiple" class=" select multiselect" size="10" name="role[]">
        <?php echo $roleStr?>
    </select>
    <br>
    <select class=" select" name="action_type">
        <option value="1">Add</option>
        <option value="2">Remove</option>
        <option value="3">Search Roles</option>
    </select>
    <br>
    <input style="padding: 5px 10px; font-size: 20px; font-weight: bold;" type="submit" name="submit" value="Submit">
</form>
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["resources"])) {
        $nameErr = "Please put value";
    } else {
        $resources = explode("\r\n", $_POST["resources"]);
    }

    if (empty($_POST["role"])) {
        $nameErr = "Please select roles";
    } else {
        $roles = $_POST["role"];
    }

    $actionType = $_POST["action_type"];


    foreach ($roles as $role) {
        $currentResources = array();
        $byRole = Mage::getModel('admin/rules')->getCollection()
            ->addFieldToFilter('role_id',$role)
            ->addFieldToFilter('role_type','G')
            ->addFieldToFilter('permission','allow');

        $byRole->setOrder('resource_id', 'ASC');
        if($actionType == 2){
            $byRole->addFieldToFilter('resource_id',array('nin'=>$resources));
            foreach ($byRole as $item) {
                $currentResources[] = $item->getResourceId();
            }
            $newResources = $currentResources;
            $newResources = array_unique($newResources);

            $rules = Mage::getModel('admin/rules')->setRoleId($role)->setResources($newResources)->saveRel();

        }elseif($actionType == 1) {
            foreach ($byRole as $item) {
                $currentResources[] = $item->getResourceId();
            }

            $newResources = array_merge($currentResources, $resources);
            $newResources = array_unique($newResources);

            $rules = Mage::getModel('admin/rules')->setRoleId($role)->setResources($newResources)->saveRel();
        }elseif($actionType == 3) {
            foreach ($byRole as $item) {
                $currentResources[] = $item->getResourceId();
            }

            //$newResources = array_merge($currentResources, $resources);
            $newResources = $currentResources;
            $newResources = array_unique($newResources);

            $roleModel = Mage::getSingleton('admin/role')->load($role);
            echo $roleModel->getRoleName().'<br>';

            foreach ($newResources as $newResource) {
                echo $newResource."<br>";
            }
            echo '<br><br>';
        }





    }
    echo "Done~";
}

echo '<br> <strong>All roles: </strong><br> <p style="font-style: italic;" >';
$resources = Mage::getModel('admin/roles')->getResourcesList();
ksort($resources);
foreach ($resources as $key => $value) {
    if(!strpos($key,"/system") && !in_array($key, array('all', 'admin'))){
        echo $key.'<br>';
    }

}
echo '</p>';

?>
</body>
</html>