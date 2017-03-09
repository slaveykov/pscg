<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1>eBay accounts <small>add &amp; view</small></h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN TABLE PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">eBay accounts</span>
							</div>
							<div class="actions btn-set">
								<button type="button" onclick="window.location='account/dashboard'" name="back" class="btn btn-default btn-circle"><i class="fa fa-angle-left"></i> Back</button>
								<button type="button" onclick="window.location='link/addAccount'" class="btn green-haze btn-circle"><i class="fa fa-check"></i> Add account</button>
							</div>
						</div>
						<?php if(isset($status)): ?>
						<div class="note note-danger note-bordered">
							<p>
								<?php echo $status; ?>
							</p>
						</div>
						<?php endif;?>
						<div class="portlet-body">
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th>
									 Username
								</th>
								<th>
									 Added on
								</th>
								<th>
									 Last used
								</th>
								<th style="width:188px;">
									 Action
								</th>
							</tr>
							</thead>
							<tbody>
							<?php foreach($accounts as $key): $data = json_decode($key['key_data'], true); ?>
							<tr>
								<td>
									 <?php echo $data['username'] ?>
								</td>
								<td>
									 <?php echo $key['added_on'] ?>
								</td>
								<td>
									 <?php echo $key['last_used'] ?>
								</td>
								<td>
									 <button type="button" class="btn btn-danger btn-sm" onclick="redirect('link/deleteAccount/<?php echo $key['id']; ?>');">
									 <i class="fa fa-times"></i> Delete 
									 </button>
									  <button type="button" class="btn btn-success btn-sm" onclick="update('link/updateAccount/<?php echo $key['id']; ?>');">
									 <i class="fa fa-refresh"></i> Update 
									 </button>
								</td>
							</tr>
							<?php endforeach; ?>
							</tbody>
							</table>
						</div>
					</div>
					<!-- END TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<script>
function redirect(url) {
  if (confirm('Are you sure you want to delete this account?')) {
    window.location.href = url;
  }
  return false;
}
function update(url) {
  if (confirm('Are you sure you want to update this account?')) {
    window.location.href = url;
  }
  return false;
}
</script>