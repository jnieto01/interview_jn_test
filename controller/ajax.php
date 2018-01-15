<?php
    include("system/securitycad.php");    
    include("config.php");
    include("system/db.php");
    include("model/ajax.php");
   
    $user_language = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);     
   // if($user_language=='en'){ 
        include("language/en/ajax.php");
        include("language/en/common/filemanager.php");
   // } 
  
    $jsondata = array();

    if (isset($_GET["addEdit"])){
         
        // less process and time than using logical operator
        $hab = true;
        while(true){
            if (isset($_POST['kind'])){
                if ($_POST['kind'] == "new_img"){
                    if (!(isset($_FILES['fileToUpload']))) break;                     
                }
            }
            else {
                break;
            }

            if (!(isset($_POST['id']))) break;  
            if (!(isset($_POST['description']))) break;                
            $hab = false;
            break;
        }
        if ($hab)   die($text_access); 
 

        $model_security = new SecurityCad();
        $id = $model_security->test_input($_POST["id"]);       
        $description = $model_security->test_input($_POST["description"]); 


     
        // validation process
        $hab = true; 
        while(true){
 
            // description less than 300 words
            if ($description != ''){
                if (!$model_security->maxLenght($description)){
                    $message = $text_maxDescription;
                    $hab = false;
                    break;                             
                }
            }
              
            $image = ''; // when user whant to keep the image
            if ($_POST['kind'] == "np"){
               $image = $text_noimage;
            }  

            if ($_POST['kind'] == "new_img"){
                // uses np(not picture) when the data is empty
                $image = basename($_FILES["fileToUpload"]["name"]);                
                if ($image == '') $image = $text_noimage;
                
                $target_dir = "img/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if(!$check) {
                    $message =  $text_title_check_img;
                    $hab = false;
                    break;
                }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > MAX_SIZE) {
                    $message = $text_img_size . MAX_SIZE ;
                    $hab = false;
                    break; 
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "gif" && $imageFileType != "png" ) {
                    $message =  $text_title_kimg;
                    $hab = false;
                    break; 
                }
                
                // Check if $uploadOk is set to 0 by an error
                if (!(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))) {
                    $message =  $text_img_notupload;
                    $hab = false;
                    break;                    
                }
            }
            break;     
         } // end of validation process


        if ($hab){        
            $db = new DB(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	     
            $module_ajax = new Ajax($db);

            $data['image'] = $image;
            $data['description'] = $description;   
            
            if ($id == ''){ // new item
              $jsondata['last_id'] = $module_ajax->addList($data);            
              $jsondata['itemsCounter'] = $module_ajax->getItemsTotal();
               
            }
            else{ // edit item
                $data['id'] = $id;                
                $module_ajax->editList($data); 
            }
        
            $jsondata['p_status'] = true;
            $jsondata['message'] = '';       
        }
        else{
            $jsondata['p_status'] = false;
            $jsondata['message'] = $message;       
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();   
    }   

 
    if (isset($_GET["delete"])){
        if (!(isset($_POST['id']))){
            die($text_access); 
        }

        $model_security = new SecurityCad();
        $id = $model_security->test_input($_POST["id"]);

        $hab = false;
        if ($id != '') $hab = true;

        if ($hab){      
            $db = new DB(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	     
            $module_ajax = new Ajax($db);
            $module_ajax->deleteList($id);
            $jsondata['itemsCounter'] = $module_ajax->getItemsTotal();
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($jsondata);                      
        }
        exit(); 
    }    
    
    if (isset($_GET["getItems"])){
        if (!(isset($_POST['access']))) die($text_access);
        $db = new DB(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	     
        $module_ajax = new Ajax($db);
        $jsondata['itemsCounter'] = $module_ajax->getItemsTotal();
        $jsondata['itemsData'] = $module_ajax->getItems();  
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();  
    }

    if (isset($_GET["sortOrder"])){

        // less process and time than using logical operator
        $hab = true;
        while(true){
            if (!(isset($_POST['ini']))) break;            
            if (!(isset($_POST['end']))) break;
            $hab = false;
            break;
        }
        if ($hab)   die($text_access); 

        $model_security = new SecurityCad();
        $ini = $model_security->test_input($_POST["ini"]);        
        $end = $model_security->test_input($_POST["end"]);


        // description less than 300 words and not empty id
        $hab = false;  
        while(true){        
            if (!is_numeric ($ini)) break;
            if (!is_numeric ($end)) break;           
            $hab = true;
            break;              
        }    
     
        if ($hab){
            ++$ini;
            ++$end;
            $db = new DB(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	     
            $module_ajax = new Ajax($db);
            $data['ini'] = $ini;           
            $data['end'] = $end;
            $module_ajax->sortList($data);
        }  
        exit();     
    }    

?>          
