<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Traininglist default helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Helper_Roster extends Mage_Core_Helper_Abstract
{

    public function getEighteenData($rootCat){
        $categories = $rootCat->getChildrenCategories();
        $result = array();
        $i=1;
        foreach ($categories as $cat1) {


            $category1 = Mage::getModel('catalog/category')->load($cat1->getId());
            $name1 = $category1->getName();
            if(strpos($name1, "Course")){
                $name1 = str_replace(array("Courses", "Course"), "Instructor", $name1);
            }else {
                $name1 .= " Instructor";
            }
            if($category1->getDescription() != ''){
                $name1 .= '--desc--'.$category1->getDescription();
            }
            $result[] = $i.'. '.$name1;
            if(count($category1->getChildrenCategories())){
                $j=1;
                foreach ($category1->getChildrenCategories() as $cat2) {
                    $category2 = Mage::getModel('catalog/category')->load($cat2->getId());
                    $name2 = $category2->getName();
                    if(strpos($name2, "Course")){
                        $name2 = str_replace(array("Courses", "Course"), "Instructor", $name2);
                    }else {
                        $name2 .= " Instructor";
                    }
                    if($category2->getDescription() != ''){
                        $name2 .= '--desc--'.$category2->getDescription();
                    }
                    $result[] = $i.'.'.$j.' '.$name2;
                    if(count($category2->getChildrenCategories())){
                        $k=1;
                        foreach ($category2->getChildrenCategories() as $cat3) {
                            $category3 = Mage::getModel('catalog/category')->load($cat3->getId());
                            $name3 = $category3->getName();
                            if(strpos($name3, "Course")){
                                $name3 = str_replace(array("Courses", "Course"), "Instructor", $name3);
                            }else {
                                $name3 .= " Instructor";
                            }
                            if($category3->getDescription() != ''){
                                $name3 .= '--desc--'.$category3->getDescription();
                            }
                            $result[] = $i.'.'.$j.'.'.$k.' '.$name3;

                            //get all curriculums
                            $instructors = Mage::helper('bs_traininglist')->getInstructorByCategory($category3->getId());


                            if($instructors && count($instructors)){
                                $curs = array();
                                foreach ($instructors as $curId) {
                                    $instructor = Mage::getModel('bs_instructor/instructor')->load($curId);
                                    $iname = $instructor->getIname();
                                    $vaecoId = $instructor->getIvaecoid();

                                    //Get approved course and function
                                    $approval = Mage::getModel('bs_instructor/instructorfunction')->getCollection()->addFieldToFilter('category_id', $category3->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                                    $approvedCourse = '';
                                    $approvedFunction = '';
                                    $approvedDoc = '';
                                    $approvedDate = '';
                                    $expireDate = '';


                                    if($approval->getId()){
                                        $approvedCourse = $approval->getApprovedCourse();
                                        $approvedFunction = $approval->getApprovedFunction();

                                        $approvedDate = $approval->getApprovedDate();
                                        if($approvedDate){
                                            $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                                        }
                                        $expireDate = $approval->getExpireDate();
                                        if($expireDate){
                                            $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                                        }

                                        $approvedDoc = $approval->getApprovedDoc();
                                    }


                                    $curs[] = array(
                                        'name'  => $iname,
                                        'vaeco_id'  => $vaecoId,
                                        'course'   => $approvedCourse,
                                        'function'  => $approvedFunction,
                                        'doc'   => $approvedDoc,
                                        'approved_date' => $approvedDate,
                                        'expire_date'   => $expireDate
                                    );
                                }
                                $result[] = $curs;

                            }

                            $k++;
                        }

                    }else {
                        //get all curriculums
                        $instructors = Mage::helper('bs_traininglist')->getInstructorByCategory($category2->getId());


                        if($instructors && count($instructors)){
                            $curs = array();
                            foreach ($instructors as $curId) {
                                $instructor = Mage::getModel('bs_instructor/instructor')->load($curId);
                                $iname = $instructor->getIname();
                                $vaecoId = $instructor->getIvaecoid();

                                //Get approved course and function
                                $approval = Mage::getModel('bs_instructor/instructorfunction')->getCollection()->addFieldToFilter('category_id', $category2->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                                $approvedCourse = '';
                                $approvedFunction = '';
                                $approvedDoc = '';
                                $approvedDate = '';
                                $expireDate = '';


                                if($approval->getId()){
                                    $approvedCourse = $approval->getApprovedCourse();
                                    $approvedFunction = $approval->getApprovedFunction();

                                    $approvedDate = $approval->getApprovedDate();
                                    if($approvedDate){
                                        $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                                    }
                                    $expireDate = $approval->getExpireDate();
                                    if($expireDate){
                                        $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                                    }

                                    $approvedDoc = $approval->getApprovedDoc();
                                }


                                $curs[] = array(
                                    'name'  => $iname,
                                    'vaeco_id'  => $vaecoId,
                                    'course'   => $approvedCourse,
                                    'function'  => $approvedFunction,
                                    'doc'   => $approvedDoc,
                                    'approved_date' => $approvedDate,
                                    'expire_date'   => $expireDate
                                );
                            }
                            $result[] = $curs;

                        }
                    }

                    $j++;

                }

            }else {
                //get all curriculums
                $instructors = Mage::helper('bs_traininglist')->getInstructorByCategory($category1->getId());


                if($instructors && count($instructors)){
                    $curs = array();
                    foreach ($instructors as $curId) {
                        $instructor = Mage::getModel('bs_instructor/instructor')->load($curId);
                        $iname = $instructor->getIname();
                        $vaecoId = $instructor->getIvaecoid();

                        //Get approved course and function
                        $approval = Mage::getModel('bs_instructor/instructorfunction')->getCollection()->addFieldToFilter('category_id', $category1->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                        $approvedCourse = '';
                        $approvedFunction = '';
                        $approvedDoc = '';
                        $approvedDate = '';
                        $expireDate = '';


                        if($approval->getId()){
                            $approvedCourse = $approval->getApprovedCourse();
                            $approvedFunction = $approval->getApprovedFunction();

                            $approvedDate = $approval->getApprovedDate();
                            if($approvedDate){
                                $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                            }
                            $expireDate = $approval->getExpireDate();
                            if($expireDate){
                                $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                            }

                            $approvedDoc = $approval->getApprovedDoc();
                        }


                        $curs[] = array(
                            'name'  => $iname,
                            'vaeco_id'  => $vaecoId,
                            'course'   => $approvedCourse,
                            'function'  => $approvedFunction,
                            'doc'   => $approvedDoc,
                            'approved_date' => $approvedDate,
                            'expire_date'   => $expireDate
                        );
                    }
                    $result[] = $curs;

                }
            }

            $i++;

        }

        return $result;
    }

    public function getEighteenTasktrainingData($rootCat){
        $categories = $rootCat->getChildrenCategories();
        $result = array();
        $i=1;
        foreach ($categories as $cat1) {


            $category1 = Mage::getModel('catalog/category')->load($cat1->getId());
            $name1 = $category1->getName();
            if($category1->getDescription() != ''){
                $name1 .= '--desc--'.$category1->getDescription();
            }
            $result[] = $i.'. '.$name1;
            if(count($category1->getChildrenCategories())){
                $j=1;
                foreach ($category1->getChildrenCategories() as $cat2) {
                    $category2 = Mage::getModel('catalog/category')->load($cat2->getId());
                    $name2 = $category2->getName();
                    if($category2->getDescription() != ''){
                        $name2 .= '--desc--'.$category2->getDescription();
                    }
                    $result[] = $i.'.'.$j.' '.$name2;
                    if(count($category2->getChildrenCategories())){
                        $k=1;
                        foreach ($category2->getChildrenCategories() as $cat3) {
                            $category3 = Mage::getModel('catalog/category')->load($cat3->getId());
                            $name3 = $category3->getName();
                            if($category3->getDescription() != ''){
                                $name3 .= '--desc--'.$category3->getDescription();
                            }
                            $result[] = $i.'.'.$j.'.'.$k.' '.$name3;

                            //get all curriculums
                            $instructors = Mage::helper('bs_traininglist')->getTaskInstructorByCategory($category3->getId());


                            if($instructors && count($instructors)){
                                $curs = array();
                                foreach ($instructors as $curId) {
                                    $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->load($curId);
                                    $iname = $instructor->getName();
                                    $vaecoId = $instructor->getVaecoId();

                                    //Get approved course and function
                                    $approval = Mage::getModel('bs_tasktraining/taskfunction')->getCollection()->addFieldToFilter('category_id', $category3->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                                    if($approval->getId()){
                                        $approvedCourse = $approval->getApprovedCourse();
                                        $approvedFunction = $approval->getApprovedFunction();



                                        $approvedDate = $approval->getApprovedDate();
                                        if($approvedDate){
                                            $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                                        }
                                        $expireDate = $approval->getExpireDate();
                                        if($expireDate){
                                            $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                                        }

                                        $curs[] = array(
                                            'name'  => $iname,
                                            'vaeco_id'  => $vaecoId,
                                            'course'   => $approvedCourse,
                                            'function'  => $approvedFunction,
                                            'doc'   => $approval->getApprovedDoc(),
                                            'approved_date' => $approvedDate,
                                            'expire_date'   => $expireDate,
                                            'new'   => $approval->getIsNew(),
                                            'update'    => $approval->getUpdateFunction()

                                        );
                                    }

                                }
                                $result[] = $curs;

                            }

                            $k++;
                        }

                    }else {
                        //get all curriculums
                        $instructors = Mage::helper('bs_traininglist')->getTaskInstructorByCategory($category2->getId());


                        if($instructors && count($instructors)){
                            $curs = array();
                            foreach ($instructors as $curId) {
                                $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->load($curId);
                                $iname = $instructor->getName();
                                $vaecoId = $instructor->getVaecoId();

                                //Get approved course and function
                                $approval = Mage::getModel('bs_tasktraining/taskfunction')->getCollection()->addFieldToFilter('category_id', $category2->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                                if($approval->getId()){
                                    $approvedCourse = $approval->getApprovedCourse();
                                    $approvedFunction = $approval->getApprovedFunction();

                                    $approvedDate = $approval->getApprovedDate();
                                    if($approvedDate){
                                        $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                                    }
                                    $expireDate = $approval->getExpireDate();
                                    if($expireDate){
                                        $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                                    }

                                    $curs[] = array(
                                        'name'  => $iname,
                                        'vaeco_id'  => $vaecoId,
                                        'course'   => $approvedCourse,
                                        'function'  => $approvedFunction,
                                        'doc'   => $approval->getApprovedDoc(),
                                        'approved_date' => $approvedDate,
                                        'expire_date'   => $expireDate,
                                        'new'   => $approval->getIsNew(),
                                        'update'    => $approval->getUpdateFunction()
                                    );
                                }

                            }
                            $result[] = $curs;

                        }
                    }

                    $j++;

                }

            }else {
                //get all curriculums
                $instructors = Mage::helper('bs_traininglist')->getTaskInstructorByCategory($category1->getId());


                if($instructors && count($instructors)){
                    $curs = array();
                    foreach ($instructors as $curId) {
                        $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->load($curId);
                        $iname = $instructor->getName();
                        $vaecoId = $instructor->getVaecoId();

                        //Get approved course and function
                        $approval = Mage::getModel('bs_tasktraining/taskfunction')->getCollection()->addFieldToFilter('category_id', $category1->getId())->addFieldToFilter('instructor_id', $curId)->getFirstItem();

                        if($approval->getId()){
                            $approvedCourse = $approval->getApprovedCourse();
                            $approvedFunction = $approval->getApprovedFunction();

                            $approvedDate = $approval->getApprovedDate();
                            if($approvedDate){
                                $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $approvedDate);
                            }
                            $expireDate = $approval->getExpireDate();
                            if($expireDate){
                                $expireDate = Mage::getModel('core/date')->date("d/m/Y", $expireDate);
                            }

                            $curs[] = array(
                                'name'  => $iname,
                                'vaeco_id'  => $vaecoId,
                                'course'   => $approvedCourse,
                                'function'  => $approvedFunction,
                                'doc'   => $approval->getApprovedDoc(),
                                'approved_date' => $approvedDate,
                                'expire_date'   => $expireDate,
                                'new'   => $approval->getIsNew(),
                                'update'    => $approval->getUpdateFunction()
                            );
                        }

                    }
                    $result[] = $curs;

                }
            }

            $i++;

        }

        return $result;
    }
}
