<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="" id="manage-voting">
                    <div class="card">
                        <div class="card-header">
                            Voting Form
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <label class="control-label">Title</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Voting Start Date</label>
                                <input type="date" class="form-control" name="start_voting">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Voting End Date</label>
                                <input type="date" class="form-control" name="end_voting">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary"> Save</button>
                                    <button class="btn btn-sm btn-default" type="button" onclick="$('#manage-voting').get(0).reset()"> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <!-- Your existing table code -->
            </div>
            <!-- Table Panel -->
        </div>
    </div>    
</div>

<script>
	$('#manage-voting').submit(function(e){
    e.preventDefault()
    start_load()
    $.ajax({
        url:'ajax.php?action=save_voting',
        method:'POST',
        data:$(this).serialize(),
        success:function(resp){
            if(resp==1){
                alert_toast("Data successfully added",'success')
                setTimeout(function(){
                    location.reload()
                },1500)
            }
            else if(resp==2){
                alert_toast("Data successfully updated",'success')
                setTimeout(function(){
                    location.reload()
                },1500)
            }
        }
    })
})

$('.edit_voting').click(function(){
    start_load()
    var cat = $('#manage-voting')
    var _this = $(this)
    cat.get(0).reset()
    $.ajax({
        url:'ajax.php?action=get_voting',
        method:'POST',
        data:{id:_this.attr('data-id')},
        success:function(resp){
            if(typeof resp != undefined){
                resp = JSON.parse(resp)
                cat.find('[name="id"]').val(_this.attr('data-id'))
                cat.find('[name="title"]').val(resp.title)
                cat.find('[name="description"]').val(resp.description)
                cat.find('[name="voting_start_date"]').val(resp.voting_start_date)
                cat.find('[name="voting_end_date"]').val(resp.voting_end_date)
                end_load()
            }
        }
    })
})

	$('.update_default').click(function(){
		_conf("Are you sure to set this data as default?","update_default",[$(this).attr('data-id')])
		
	})
	$('.delete_voting').click(function(){
		_conf("Are you sure to delete this data?","delete_voting",[$(this).attr('data-id')])
	})
	function update_default($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=update_voting',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
	function delete_voting($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_voting',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>
