<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

switch ($action) {
    case 'login':
        $login = $crud->login();
        if($login) echo $login;
        break;
    case 'logout':
        $logout = $crud->logout();
        if($logout) echo $logout;
        break;
    case 'save_user':
        $save = $crud->save_user();
        if($save) echo $save;
        break;
		case 'resetPassword':
			// Assuming the resetPassword method exists in your Action class
			$save = $crud->resetPassword();
			if($save) echo $save;
			break;
    case 'save_category':
        $save = $crud->save_category();
        if($save) echo $save;
        break;
    case 'delete_category':
        $delete = $crud->delete_category();
        if($delete) echo $delete;
        break;
    case 'save_voting':
        $save = $crud->save_voting();
        if($save) echo $save;
        break;
    case 'get_voting':
        $get = $crud->get_voting();
        if($get) echo $get;
        break;
    case 'update_voting':
        $update = $crud->update_voting();
        if($update) echo $update;
        break;
    case 'delete_voting':
        $delete = $crud->delete_voting();
        if($delete) echo $delete;
        break;
    case 'save_opt':
        $save = $crud->save_opt();
        if($save) echo $save;
        break;
    case 'delete_candidate':
        $delete = $crud->delete_candidate();
        if($delete) echo $delete;
        break;
    case 'save_settings':
        $save = $crud->save_settings();
        if($save) echo $save;
        break;
    case 'submit_vote':
        $save = $crud->submit_vote();
        if($save) echo $save;
        break;
    default:
        // Handle unknown actions or default case
        echo "Unknown action";
        break;
}
