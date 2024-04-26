<?php 
include('db_connect.php');

if(isset($_GET['id'])){
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    
    // Bind the parameter to the SQL statement
    $stmt->bind_param("s", $_GET['id']);
    
    // Execute the SQL statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Fetch the data
    while ($row = $result->fetch_assoc()) {
        $meta[] = $row;
    }
    
    // Close the statement
    $stmt->close();
}

?>

<div class="container-fluid">
    <form action="" id="manage-user">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required>
        </div>
        <div class="form-group">
            <label for="id">User_id</label>
            <input type="text" name="id" id="id" class="form-control" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required>
        </div>
      
        <div class="form-group">
            <label for="type">User Type</label>
            <select name="type" id="type" class="custom-select">
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
                <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>User</option>
            </select>
        </div>
    </form>
</div>

<script>
$('#manage-user').submit(function(e){
    e.preventDefault();
    start_load()
    $.ajax({
        url:'ajax.php?action=save_user',
        method:'POST',
        data:$(this).serialize(),
        success:function(resp){
            if(resp ==1){
                alert_toast("Data successfully saved",'success')
                setTimeout(function(){
                    location.reload()
                },1500)
            }
        }
    })
})
	
</script>