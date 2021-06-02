<?php
	session_start();
	include "session_check.php";
	include "db_connect.php";
	
	$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Snickr Manage Invites</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="snickr_style.css">
<style>
html { 
	background: url('https://d2v9y0dukr6mq2.cloudfront.net/video/thumbnail/cW5lDBG/e-mail-icons-move-in-perspective-view-on-dark-grid-background-internet-message-concept_s7esa7lke_thumbnail-full01.png') no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
</style>

<script>
    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
        	$(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
        	$(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    });
</script>
</head>
<body>
<div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a href="welcome.php" class="navbar-brand">Snickr</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
		
		<div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Profile</a>
                    <div class="dropdown-menu">
                        <a href="profile.php" class="dropdown-item">Update Profile</a>
						<a href="change_password.php" class="dropdown-item">Change Password</a>
                    </div>
                </div>
				
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" data-toggle="dropdown">Manage Invitations</a>
                    <div class="dropdown-menu">
                        <a href="received_workspace_invites.php" class="dropdown-item">Received Workspace Invites</a>
						<a href="received_channel_invites.php" class="dropdown-item">Received Channel Invites</a>
                        <a href="sent_invites.php" class="dropdown-item">View Sent Invites</a>
                    </div>
                </div>
				
				<div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Manage Workspaces</a>
                    <div class="dropdown-menu">
						<a href="create_workspace.php" class="dropdown-item">Create a new workspace</a>
						<a href="remove_workspace.php" class="dropdown-item">Delete a workspace</a>
						<a href="view_members.php" class="dropdown-item">View all workspace members and admins</a>
						<a href="view_my_memberships.php" class="dropdown-item">View my memberships</a>
						<a href="join_public_channels.php" class="dropdown-item">Join public channels in your workspaces</a>
						<a href="add_workspace_users.php" class="dropdown-item">Add users to a workspace</a>
                        <a href="remove_workspace_users.php" class="dropdown-item">Remove users from a workspace</a>
                        <a href="add_workspace_admins.php" class="dropdown-item">Promote admins in a workspace</a>
                        <a href="remove_workspace_admins.php" class="dropdown-item">Demote admins in a workspace</a>
						<a href="add_workspace_channels.php" class="dropdown-item">Add channels to a workspace</a>
                        <a href="remove_workspace_channels.php" class="dropdown-item">Remove channels from a workspace</a>
						<a href="add_channel_users.php" class="dropdown-item">Add users to a channel</a>
                        <a href="remove_channel_users.php" class="dropdown-item">Remove users from a channel</a>
						<a href="leave_workspace.php" class="dropdown-item">Leave a workspace</a>
                        <a href="leave_channel.php" class="dropdown-item">Leave a channel</a>
                    </div>
                </div>
				
                <a href="choose_channel.php" class="nav-item nav-link">Workspace Chat</a>
				
            </div>
			<form class="form-inline ml-auto">
                <a href="logout.php" class="btn btn-danger">Logout</a>
			</form>
        </div>
    </nav>
	
	<div class="container-fluid">
		<br>
		<h3 class="text-center">View The Status of Invites You Sent</h3>
			
		 <div class="accordion" id="accordionExample">
			<div class="card">
				<div class="card-header" id="headingOne">
					<h2 class="mb-0">
						<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i> Workspace Invites</button>									
					</h2>
				</div>
				<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
					<div class="card-body">
					
						<div class="panel panel-primary filterable">
							<div class="panel-heading">
								<div class="pull-right">
									<button class="btn btn-danger btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Reset</button>
								</div>
							</div>
							
							<div class="table-responsive"> 
								<table class="table table-bordered table-striped">
									<thead class="thead-dark">
										<tr class="filters">
											<th><input type="text" class="form-control" placeholder="Workspace ID" enabled></th>
											<th><input type="text" class="form-control" placeholder="Workspace Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Email" enabled></th>
											<th><input type="text" class="form-control" placeholder="First Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Last Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Nickname" enabled></th>
											<th><input type="text" class="form-control" placeholder="Status" enabled></th>
											<th><input type="text" class="form-control" placeholder="Date" enabled></th>
										</tr>
										<tr>
											<th style="text-align:center;">Workspace ID</th>
											<th style="text-align:center;">Workspace Name</th>
											<th style="text-align:center;">Sent To (Email)</th>
											<th style="text-align:center;">First Name</th>
											<th style="text-align:center;">Last Name</th>
											<th style="text-align:center;">Nickname</th>
											<th style="text-align:center;">Invite Status</th>
											<th style="text-align:center;">Date Sent</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql1 = "SELECT wsi.workspace_id, ws.workspace_name, wsi.wsi_receiver,
												u.u_first_name, u.u_last_name, u.u_nickname, wsi.wsi_status,
												wsi.wsi_invite_timedate
												FROM workspace ws JOIN workspace_invites wsi JOIN users u
												WHERE (ws.workspace_id = wsi.workspace_id) AND (wsi.wsi_receiver = u.email)
													AND (wsi.wsi_sender = '$email')
												ORDER BY wsi.wsi_invite_timedate DESC";
										$workspaceResult = $mysqli->query($sql1);
										while($row1 = $workspaceResult->fetch_assoc()){
											$wsID = $row1['workspace_id'];
											$wsName = $row1['workspace_name'];
											$wsEmail = $row1['wsi_receiver'];
											$wsFirstName = $row1['u_first_name'];
											$wsLastName = $row1['u_last_name'];
											$wsNickname = $row1['u_nickname'];
											$wsDate = $row1['wsi_invite_timedate'];
											$time1=date('M-d-Y h:i a', strtotime($wsDate));
											$wsStatusVal = $row1['wsi_status'];
											$wsStatus = "";
											if($wsStatusVal == 0){$wsStatus = "pending";}
											elseif($wsStatusVal == 1){$wsStatus = "accepted";}
											elseif($wsStatusVal == 2){$wsStatus = "declined";}
											elseif($wsStatusVal == 3){$wsStatus = "already joined";}
										?>
										<tr>
											<td style="text-align:center;"><?php echo"$wsID"; ?></td>
											<td style="text-align:center;"><?php echo"$wsName"; ?></td>
											<td style="text-align:center;"><?php echo"$wsEmail"; ?></td>
											<td style="text-align:center;"><?php echo"$wsFirstName"; ?></td>
											<td style="text-align:center;"><?php echo"$wsLastName"; ?></td>
											<td style="text-align:center;"><?php echo"$wsNickname"; ?></td>
											<td style="text-align:center;"><?php echo"$wsStatus"; ?></td>
											<td style="text-align:center;"><?php echo"$time1"; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>  
						</div>
						
					</div>
				</div>
				
			</div>
			<div class="card">
				<div class="card-header" id="headingTwo">
					<h2 class="mb-0">
						<button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> Channel Invites</button>
					</h2>
				</div>
				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
					<div class="card-body">
						
					<div class="panel panel-primary filterable">
							<div class="panel-heading">
								<div class="pull-right">
									<button class="btn btn-danger btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span>Filter Reset</button>
								</div>
							</div>

						<div class="table-responsive"> 
								<table class="table table-bordered table-striped">
									<thead class="thead-dark">
										<tr class="filters">
											<th><input type="text" class="form-control" placeholder="Workspace ID" enabled></th>
											<th><input type="text" class="form-control" placeholder="Workspace Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Channel Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Email" enabled></th>
											<th><input type="text" class="form-control" placeholder="First Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Last Name" enabled></th>
											<th><input type="text" class="form-control" placeholder="Nickname" enabled></th>
											<th><input type="text" class="form-control" placeholder="Status" enabled></th>
											<th><input type="text" class="form-control" placeholder="Date" enabled></th>
										</tr>
										<tr>
											<th style="text-align:center;">Workspace ID</th>
											<th style="text-align:center;">Workspace Name</th>
											<th style="text-align:center;">Channel Name</th>
											<th style="text-align:center;">Sent To (Email)</th>
											<th style="text-align:center;">First Name</th>
											<th style="text-align:center;">Last Name</th>
											<th style="text-align:center;">Nickname</th>
											<th style="text-align:center;">Invite Status</th>
											<th style="text-align:center;">Date Sent</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql1 = "SELECT chi.workspace_id, ws.workspace_name, chi.chi_receiver,
												u.u_first_name, u.u_last_name, u.u_nickname, chi.chi_status,
												chi.chi_invite_timedate, chi.channel_name
												FROM workspace ws JOIN channel_invites chi JOIN users u JOIN channels c
												WHERE (c.workspace_id = chi.workspace_id) AND (chi.chi_receiver = u.email)
													AND (chi.chi_sender = '$email') AND (chi.channel_name = c.channel_name)
													AND (c.workspace_id = ws.workspace_id)
												ORDER BY chi.chi_invite_timedate DESC";
										$channelResult = $mysqli->query($sql1);
										while($row2 = $channelResult->fetch_assoc()){
											$chID = $row2['workspace_id'];
											$chWsName = $row2['workspace_name'];
											$chName = $row2['channel_name'];
											$chEmail = $row2['chi_receiver'];
											$chFirstName = $row2['u_first_name'];
											$chLastName = $row2['u_last_name'];
											$chNickname = $row2['u_nickname'];
											$chDate = $row2['chi_invite_timedate'];
											$time2=date('M-d-Y h:i a', strtotime($chDate));
											$chStatusVal = $row2['chi_status'];
											$chStatus = "";
											if($chStatusVal == 0){$chStatus = "pending";}
											elseif($chStatusVal == 1){$chStatus = "accepted";}
											elseif($chStatusVal == 2){$chStatus = "declined";}
											elseif($chStatusVal == 3){$chStatus = "already joined";}
										?>
										<tr>
											<td style="text-align:center;"><?php echo"$chID"; ?></td>
											<td style="text-align:center;"><?php echo"$chWsName"; ?></td>
											<td style="text-align:center;"><?php echo"$chName"; ?></td>
											<td style="text-align:center;"><?php echo"$chEmail"; ?></td>
											<td style="text-align:center;"><?php echo"$chFirstName"; ?></td>
											<td style="text-align:center;"><?php echo"$chLastName"; ?></td>
											<td style="text-align:center;"><?php echo"$chNickname"; ?></td>
											<td style="text-align:center;"><?php echo"$chStatus"; ?></td>
											<td style="text-align:center;"><?php echo"$time2"; ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>  
						</div>
						
					</div>
				</div>
				</div>
			</div>
			<br>
		</div>
		<br>
	</div>
	
</div>

<script>
$(document).ready(function(){
    $('.filterable .btn-filter').click(function(){
        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
		$filters.val('').prop('disabled', false);
		$filters.first().focus();
		$tbody.find('.no-result').remove();
		$tbody.find('tr').show();
    });

    $('.filterable .filters input').keyup(function(e){
        /* Ignore tab key */
        var code = e.keyCode || e.which;
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
		
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');
		if(!$table) $table = $panel;
        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
            var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
		
		
    });
});
</script>

<?php
$mysqli->close();
?>

</body>
</html>