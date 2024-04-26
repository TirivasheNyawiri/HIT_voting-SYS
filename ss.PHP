<?php include('db_connect.php');?>
<?php
    $voting = $conn->query("SELECT * FROM voting_list where is_default = 1 ");
    foreach ($voting->fetch_array() as $key => $value) {
        $$key = $value;
    }
// Assuming $conn is your database connection
$stmt = $conn->prepare("SELECT DISTINCT voting_id FROM votes WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['login_id']); // Assuming login_id is a string
$stmt->execute();
$result = $stmt->get_result();
$vchk = $result->num_rows;

if($vchk > 0){
    header('Location: voting.php?page=view_vote');
    exit; // It's important to exit after a header redirect to prevent further execution
}

    

    $vote = $conn->query("SELECT * FROM voting_list where id=".$id);
    foreach ($vote->fetch_array() as $key => $value) {
        $$key= $value;
    }
    $opts = $conn->query("SELECT * FROM voting_opt where voting_id=".$id);
    $opt_arr = array();
    $set_arr = array();

    while($row=$opts->fetch_assoc()){
        $opt_arr[$row['category_id']][] = $row;
        $set_arr[$row['category_id']] = array('id'=>'','max_selection'=>1);
    }

    $settings = $conn->query("SELECT * FROM voting_cat_settings where voting_id=".$id);
    while($row=$settings->fetch_assoc()){
        $set_arr[$row['category_id']] = $row;
    }
?>
<style>
	.candidate {
	    margin: auto;
	    width: 16vw;
	    padding: 10px;
	    cursor: pointer;
	    border-radius: 3px;
	    margin-bottom: 1em
	}
	.candidate:hover {
	    background-color: #80808030;
	    box-shadow: 2.5px 3px #00000063;
	}
	.candidate img {
	    height: 14vh;
	    width: 8vw;
	    margin: auto;
	}
	span.rem_btn {
	    position: absolute;
	    right: 0;
	    top: -1em;
	    z-index: 10;
	    display: none
	}
	span.rem_btn.active{
		display: block
	}
    /* Your CSS styles remain unchanged */
</style>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="submit_vote.php" method="post" id="manage-vote">
                    <input type="hidden" name="voting_id" value="<?php echo $id ?>">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <h3><b><?php echo 'CONTESTING CANDIDATES' ?></b></h3>
                        
                        </div>
                        
                        <?php 
                        $cats = $conn->query("SELECT * FROM category_list where id in (SELECT category_id from voting_opt where voting_id = '".$id."' )");
                        while($row = $cats->fetch_assoc()):
                        ?>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <h3><b><?php echo $row['category'] ?></b></h3>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                            <?php foreach ($opt_arr[$row['id']] as $candidate) {
                            ?>
                                <div class="candidate" style="position: relative;" data-cid = '<?php echo $row['id'] ?>' data-max="<?php echo $set_arr[$row['id']]['max_selection'] ?>" data-name="<?php echo $row['category'] ?>">
                                    <input type="checkbox" name="opt_id[<?php echo $row['id'] ?>][]" value="<?php echo $candidate['id'] ?>" style="display: none;">
                                    <span class="rem_btn">
                                        <label for="" class="btn btn-primary"><span class="fa fa-check"></span></label>
                                    </span>
                                    <div class="item" data-id="<?php echo $candidate['id'] ?>">
                                        <div style="display: flex">
                                            <img src="assets/img/<?php echo $candidate['image_path'] ?>" alt="">
                                        </div>
                                        <br>
                                        <div class="text-center">
                                            <large class="text-center"><b><?php echo ucwords($candidate['opt_txt']) ?></b></large>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <hr>
                    
                </form>
            </div>
        </div>
    </div>
</div>
