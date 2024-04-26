  <?php
  include('connect.php');

  $username=$_POST['username'];
  $number=$_POST['mobile_number'];
  $password=$_POST['password'];
  $id=$_POST['id'];
  $std=$_POST['std'];
  $status=$_POST['status'];
  $votes=$_POST['votes'];
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $sql="insert into `users`(username,mobile,password,id,standard) values ('$username','$mobile_number','$password','$id','$std') "
  ?>