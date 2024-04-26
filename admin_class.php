<?php

include 'connect.php';
include('smtp/PHPMailerAutoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and id = '".$id."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_type'] == 1)
				return 1;
			else
				return 2;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_files(){
		extract($_POST);
		if(empty($id)){
		if($_FILES['upload']['tmp_name'] != ''){
					$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
					$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets/uploads/'. $fname);
		
					if($move){
						$file = $_FILES['upload']['name'];
						$file = explode('.',$file);
						$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' ");
						if($chk->num_rows > 0){
							$file[0] = $file[0] .' ||'.($chk->num_rows);
						}
						$data = " name = '".$file[0]."' ";
						$data .= ", folder_id = '".$folder_id."' ";
						$data .= ", description = '".$description."' ";
						$data .= ", user_id = '".$_SESSION['login_id']."' ";
						$data .= ", file_type = '".$file[1]."' ";
						$data .= ", file_path = '".$fname."' ";
						if(isset($is_public) && $is_public == 'on')
						$data .= ", is_public = 1 ";
						else
						$data .= ", is_public = 0 ";

						$save = $this->db->query("INSERT INTO files set ".$data);
						if($save)
						return json_encode(array('status'=>1));
		
					}
		
				}
			}else{
						$data = " description = '".$description."' ";
						if(isset($is_public) && $is_public == 'on')
						$data .= ", is_public = 1 ";
						else
						$data .= ", is_public = 0 ";
						$save = $this->db->query("UPDATE files set ".$data. " where id=".$id);
						if($save)
						return json_encode(array('status'=>1));
			}

	}
	
	function save_user(){
		extract($_POST);
	
		// Generate a random password
		$password = bin2hex(random_bytes(10)); // Generate a random 20-character string
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
	
		// Prepare the data for the query
		$data = " name = '$name', username = '$username', id = '$id', email = '$email', password = '$hashedPassword', type = '$type' ";
	
		// Attempt to insert or update the user
		$save = $this->db->query("INSERT INTO users (name, username, id, email, password, type) VALUES ('$name', '$username', '$id', '$email', '$hashedPassword', '$type') ON DUPLICATE KEY UPDATE name = VALUES(name), username = VALUES(username), email = VALUES(email), password = VALUES(password), type = VALUES(type)");
	
		if($save){
			// Send email with PHPMailer
			try {
				$mail = new PHPMailer(true);
				// Server settings
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;
				$mail->Username = "h220290g@hit.ac.zw"; // SMTP username
				$mail->Password = "juls cpkr zirp zjgu"; // SMTP password
				$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587; // TCP port to connect to
	
				// Recipients
				$mail->setFrom('h220290g@hit.ac.zw', 'Mailer');
				$mail->addAddress($email, $name); // Add a recipient
	
				// Content
				$mail->isHTML(true); // Set email format to HTML
				$mail->Subject = 'Your New Password';
				$mail->Body    = 'Hello ' . $name . ',<br><br>Your new password is: ' . $password . '<br><br>Please change your password after logging in.<br><br>Best regards,<br>Your Team';
	
				$mail->send();
				echo 'Message has been sent';
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}
		}
		return 1;
	}
	

	
	function save_category(){
		extract($_POST);
		$data = " category = '$category' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO category_list set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE category_list set ".$data." where id =".$id);
			if($save)
				return 2;
		}
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list where id=".$id);
		if($delete)
			return 1;
	}
	function save_voting(){
		// Assuming $this->db is your database connection object
		// Extract the POST data
		extract($_POST);
		
		// Prepare the data to be inserted or updated
		$data = array(
			'title' => $title,
			'description' => $description,
			'voting_start_date' => $voting_start_date,
			'voting_end_date' => $voting_end_date
		);
		
		// Check if the ID is empty, indicating a new record
		if(empty($id)){
			// Prepare the INSERT statement for the voting_list table
			$stmt = $this->db->prepare("INSERT INTO voting_list (title, description) VALUES (?, ?)");
			$stmt->bind_param("ss", $data['title'], $data['description']);
			
			// Execute the statement
			if($stmt->execute()){
				// Assuming the last inserted ID is needed for the dates table
				
				
				// Prepare the INSERT statement for the dates table
				$stmt = $this->db->prepare("INSERT INTO dates (id, end_voting, start_voting) VALUES (?, ?, ?)");
				$stmt->bind_param("sss" ,$id,  $data['end_voting'], $data['start_voting']);
				
				// Execute the statement
				if($stmt->execute()){
					return 1; // Successfully inserted
				}
			}
		} else {
			// Prepare the UPDATE statement for the voting_list table
			$stmt = $this->db->prepare("UPDATE voting_list SET title = ?, description = ? WHERE id = ?");
			$stmt->bind_param("ssi", $data['title'], $data['description'], $id);
			
			// Execute the statement
			if($stmt->execute()){
				// Prepare the UPDATE statement for the dates table
				$stmt = $this->db->prepare("UPDATE dates SET start_voting = ?, end_voting = ? WHERE id = ?");
				$stmt->bind_param("ssi", $data['start_voting'], $data['end_voting'], $id);
				
				// Execute the statement
				if($stmt->execute()){
					return 2; // Successfully updated
				}
			}
		}
		
		// Return 0 if the operation failed
		return 0;
	}
	
	function get_voting(){
		// Assuming $this->db is your database connection object
		// Extract the ID from the POST data
		extract($_POST);
		
		// Prepare the query to fetch the voting record by ID
		$stmt = $this->db->prepare("SELECT * FROM voting_list WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows > 0) {
			// Fetch the voting record as an associative array
			$row = $result->fetch_assoc();
			// Return the voting record as JSON
			echo json_encode($row);
		} else {
			// Return 0 if no record is found
			echo 0;
		}
	}
	
	function delete_voting(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM voting_list where id=".$id);
		if($delete)
			return 1;
	}

	function update_voting(){
		extract($_POST);
		$this->db->query("UPDATE voting_list set is_default = 0 where id !=".$id);
		$update = $this->db->query("UPDATE voting_list set is_default = 1 where id= ".$id);
		if($update)
			return 1;
	}
	function save_opt(){
		extract($_POST);
		$data = " category_id = '".$category_id."' ";
		$data .= ", opt_txt = '".$opt_txt."' ";
		$data .= ", voting_id = '".$voting_id."' ";

		if($_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/img/'. $fname);
			$data .= ", image_path = '".$fname."' ";
			if(!empty($id)){

			$path = $this->db->query("SELECT * FROM voting_opt where id=".$id)->fetch_array()['image_path'];
			if(!empty($path))
			unlink('assets/img/'.$path);
			}

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO voting_opt set ".$data);
			if($save)
				return 1;
		}else{
			$save = $this->db->query("UPDATE voting_opt set ".$data." where id=".$id);
			if($save)
				return 2;
		}
	}
	function delete_candidate(){
		extract($_POST);
		$path = $this->db->query("SELECT * FROM voting_opt where id=".$id)->fetch_array()['image_path'];
		$delete = $this->db->query("DELETE FROM voting_opt where id=".$id);
		if($delete){
			unlink('assets/img/'.$path);
			return 1;
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " category_id = $category_id ";
		$data .= ", voting_id = $voting_id ";
		$data .= ", max_selection = $max_selection ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO voting_cat_settings set ".$data);
		}else{
			$save = $this->db->query("UPDATE voting_cat_settings set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	 function resetPassword() {
        // Assuming you're receiving the user's ID via POST
        $userId = $_POST['id'];
        $email = $_POST['email'];
        $username = $_POST['username'];

        // Generate a new password
        $newPassword = bin2hex(random_bytes(10)); // Generate a random 20-character string
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash the password

        // Update the user's password in the database
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Send email with PHPMailer
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "h220290g@hit.ac.zw"; // SMTP username
                $mail->Password = "juls cpkr zirp zjgu"; // SMTP password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('h220290g@hit.ac.zw', 'Mailer');
                $mail->addAddress($email, $username);

                $mail->isHTML(true);
                $mail->Subject = 'Your New Password';
                $mail->Body    = 'Hello ' . $username . ',<br><br>Your new password is: ' . $newPassword . '<br><br>Please change your password after logging in.<br><br>Best regards,<br>Your Team';

                $mail->send();
                return 1; // Indicate success
            } catch (Exception $e) {
                return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            return "Error updating password";
        }
    }
	function submit_vote() {
		extract($_POST);
		$set = 0;
		$conn = $this->db; // Assuming $this->db is your database connection object
	
		foreach ($opt_id as $k => $val) {
			foreach ($val as $key => $v) {
				$stmt = $conn->prepare("INSERT INTO votes (voting_id, category_id, user_id, voting_opt_id) VALUES (?, ?, ?, ?)");
				$stmt->bind_param("iiss", $voting_id, $k, $_SESSION['login_id'], $v);
				$stmt->execute();
	
				if ($stmt->affected_rows > 0) {
					$set++;
				}
	
				$stmt->close(); // Close the statement to free up resources
			}
		}
	
		if (isset($save) && count($save) == $set) {
			return 1;
		} else {
			return 0; // Return 0 if not all votes were successfully inserted
		}
	}
	
}