<?php
class UserController extends MainController {
	
    public function __construct() {
        $this->bean               = load_bean("UsersMasterBean");
        $this->dao                = load_dao("UserMasterDAO");
        $this->default_password   = "safetyportal";

        parent::__construct();
    }
	 
    public function index() {
        $status_id          = $this->bean->get_request("status_id");
        $search_user_txt    = $this->bean->get_request("search_user_txt");

        $limit   = 10;
        $clause = "";
        $clause  .= " master_users.id > 0 ";
        if($status_id > 0)
        {
            $clause  .= " AND master_users.status_id = ".$status_id;
        }
        $clause .= " AND deleted = 0";
        if( $search_user_txt != "" ) {
            $clause .= " AND (master_users.employee_code LIKE '%".$search_user_txt."%' OR master_users.full_name LIKE '%".$search_user_txt."%')";
        }
        $clause .= " ORDER BY master_users.last_modified_date DESC";

        $allusers           = $this->dao->get_users_with_pagging( $clause, $limit );
        $users_paggin_html  = get_page_html($this->dao);

        $this->beanUi->set_view_data("user_status", $this->get_status_options("user_status"));
        $this->beanUi->set_view_data("allusers", $allusers);
        $this->beanUi->set_view_data("users_paggin_html", $users_paggin_html);
        $this->beanUi->set_view_data("page", $this->bean->get_request("page"));
        $this->beanUi->set_view_data("search_user_txt", $search_user_txt);
        $this->beanUi->set_view_data("status_id", $status_id);
    }
	
    public function login() {
        if( $this->action == 'login' ) {
            $data = $_POST["data"];
            if( ! $this->bean->validateLogin($data) ) {
                $this->set_session( "errors", $this->bean->get_error_messages() );
                redirect( "login.php" );
            }
            $user = $this->dao->login($data);
            if( empty( $user ) ) {
                $this->beanUi->set_error_message("Wrong Employee Code or Password.");
                redirect( "login.php" );
            }               
            $status_options = array();
            $status_options = $this->dao->get_status_options();
            $user_status = isset( $status_options["user_status"] ) ? $status_options["user_status"] : array();
            $registered_status_id = get_id_from_array("status_name", "Registered", $user_status);
            $deactive_status_id = get_id_from_array("status_name", "Deactive", $user_status);
            if( $user->status_id == $registered_status_id ) {
                    $this->beanUi->set_error_message( "Your account has not been activatted yeat. Please check your email to activate it." );
            } elseif( $user->status_id == $deactive_status_id ) {
                    $this->beanUi->set_error_message( "Your account is deactivatted. Please contact to administrator to activate it." );
            } else {
                    $this->set_session( "status_options", $status_options );
                    $this->set_session( "admin_auth_user", $user );
                    redirect( "../dashboard.php" );
            }
               
            redirect( "login.php" );
        }
    }
	
    public function register() {
        $this->dao 	= load_dao("UserMasterDAO");
        $_action = $this->bean->get_request("_action");
        if( $_action == "Register" ) {
            $validate = $this->bean->valid_register();
            if( ! $validate ) {
                    $this->set_session( "errors", $this->bean->get_error_messages() );
                    $this->redirect('register.php');
            }

            $data = $this->bean->get_request("data");
            $data["role_id"] = 2;
            $this->loadLibraries('encription');
            $data["password"] = $this->encription->vvs_encript($data["password"]);
            $data["status_id"] = 1;
            $data["created_date"] = date("c");
            $this->beanUi->set_error_message( "User registration faild." );
            if( $this->dao->save($data) ) {
                // Send email
                $this->loadLibraries('PHPMail');
                if( is_object( $this->phpmail ) ) {
                    $to = $email;
                    $from = "From : career@viavitae.co.in\r\n";
                    $from .=  "Content-type: text/html\r\n";
                    $subject = "Confirmation : Upload Resume - Via Vitae Solutions";
                    $message = "Hello ".$data["full_name"].", You have been successfully registered on CESC Safety Monitoring System website. Please click the link given bellow to activate and login to your account.
                    <br /><a hreh=\"".page_link("login.php?tokenid=".time()."_")."\">login to CESC Safety Monitoring System</a>";
                    @mail($to,$subject,$message,$from);
                }
                $this->beanUi->set_success_message( "Registration has been completted. Please check your e-mail to activate and login." );
            } else {
                if( $this->dao->pdo_error != "" ) die( $this->dao->pdo_error );
            }
            redirect('login.php');
        }
        $this->beanUi = new BeanUi();
        $security_questions = $this->dao->select( "SELECT * FROM `master_security_questions`" );
        $this->beanUi->set_view_data("security_questions", $security_questions);
    }
	
    public function delete_user() {
        $this->dao->_table = "master_users";
        $success = 0;
        $ids = $this->bean->get_request("ids");
        
        $ids_arr = explode( ',', $ids );
        $idstr = "";
        if( count($ids_arr) ) {
                foreach($ids_arr as $id) {
            
                        if( $id > 0 ) if( $this->dao->save( array( "deleted" => 1, "id" => $id ) ) ) $success++;
                }
        }
        if( $success ) $this->beanUi->set_success_message( "successfully deleted." );
        redirect(page_link("users/"));
    }
    
    public function resetPassword() {
        $success    = 0;
        $ids        = $this->bean->get_request("ids");
        $ids_arr    = explode( ',', $ids );
        $idstr      = "";
        if( count($ids_arr) ) {
            foreach($ids_arr as $id) {
                if( $id > 0 ) if( $this->dao->save( array( "password" => md5($this->default_password), "id" => $id ) ) ) $success++;
            }
        }
        if( $success ) $this->beanUi->set_success_message( "Successfully reset password." );
        redirect(page_link("users/"));        
    }
    
    public function bulk_active_innactive() {
        $success    = 0;
        $ids        = $this->bean->get_request("ids");
        $status_id  = $this->bean->get_request("status_id");
        if($status_id == 2)
        {
            $st = "activated";
        }
        else
        {
            $st = "deactivated";
        }
        $ids_arr    = explode( ',', $ids );
        $idstr      = "";
        if( count($ids_arr) ) {
            foreach($ids_arr as $id) {
                if( $id > 0 ) if( $this->dao->save( array( "status_id" => $status_id, "id" => $id ) ) ) $success++;
            }
        }
        if( $success ) $this->beanUi->set_success_message( "Account has successfully ".$st."." );
        redirect(page_link("users/"));  
        
    }
    
    public function change_password() {
        $action             = $this->bean->get_request("_action");
        $old_password       = $this->bean->get_request("old_password");
        $new_password       = $this->bean->get_request("new_password");
        $confirm_password   = $this->bean->get_request("confirm_password");
        $auth_user_id       = $this->get_auth_user("id");
        $userDetails        = $this->dao->getUserDetails($auth_user_id);
        $dbpassword         = $userDetails[0]->password;
        if( $action == "Create" )
        {
            if( $old_password == '' ||  $new_password == '' || $confirm_password=='')
            {
                $this->beanUi->set_error_message("All fields are mandatory.");
                redirect( "change_password.php" );
            }
            else if( $dbpassword!= md5($old_password) )
            {
                $this->beanUi->set_error_message("Old password doesnot match.");
                redirect( "change_password.php" );
            }
            else if( $new_password!= $confirm_password )
            {
                $this->beanUi->set_error_message("New password doesnot match with confirm password.");
                redirect( "change_password.php" );
            }
            else
            {
                $this->dao->save( array( "password" => md5($new_password), "id" => $auth_user_id ));
                $this->beanUi->set_success_message("Password has successfully changed.");
		redirect( "change_password.php" );
            }
        }
    }
    
    public function dbbackup()
    {
		 $action             = $this->bean->get_request("_action");
		
		 if( $action == "Backup" )
        {
			//die("anima");
			
			$this->dao->backup_tables("localhost","root","root","cesc_safety_management",$tables = '*');
		}
		
	}
    
    public function add() {
        $action             = $this->bean->get_request("_action");
        $auth_user_id       = $this->get_auth_user("id");
        $userDetails        = $this->dao->getUserDetails($auth_user_id);
        $default_password   = $this->default_password;
        $role_id 			= $this->get_auth_user("role_id");
        
       
      
        if( $action == "add" )
        {
            $this->set_session( "data", $this->bean->get_request("data") );
            $data                       = $this->bean->get_request("data");
            $validate                   = $this->bean->validUser();
                if( !$validate ) {
                    $this->set_session( "errors", $this->bean->get_error_messages() );
                    redirect('add.php');
                }
            
            //$data["role_id"]            = $role;
            $data["password"]           = md5($default_password);
            $data["created_by"]         = $this->get_auth_user("id");
            $data["created_date"]       = date("c");
            $data["last_modified_by"]   = $this->get_auth_user("id");
            $data["last_modified_date"] = date("c");
            $this->dao->_table = "master_users";
            $userid = $this->dao->save($data);
            if( ! $userid ) {
                $this->beanUi->set_error_message($this->dao->get_query_error());
                redirect();
            }
            else {
                $this->beanUi->set_success_message("User has successfully added.");
		redirect( "add.php" );
            }
        }
    }
        
}
