<style>
.feeds li:hover {
	background-color: #f3f3f3;
	color: #757575;
}

.feeds li:last-child {
	margin-bottom: 5px;
}
.desc b{
	font-weight:normal;
}
.desc{
	font-weight:normal;
}
.feeds li .col2 {
    float: left;
    width: 90px; 
}
</style>
<?php
if(isset($popup_reminder)){
	echo $popup_reminder;
}
?>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
	<!-- BEGIN PAGE HEAD -->
	<div class="page-head">
		<div class="container">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1>
					Dashboard <small>statistics &amp; reports</small>
				</h1>
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
				<div class="col-lg-12">
				<div class="alert alert-danger" style="display:none;">
			Part of Walmart Products are provided from walmart api as Out Of Stock but they are not, we work with Walmart to fix this issue.
			<br />
Част от продуктите на Walmart се подават от тях като OOS но реално не са, Работи се по отстраняването на проблема;
				</div>
				</div>
				<?php
				if (!empty($services)) {
				?>
				<div class="col-lg-12" <?php if(isset($display_services)){}else{?>style="display:none;"<?php } ?>>
				<div class="alert alert-danger">
				 Warning! These services maybe expire these days. Please, renew it.<br />
				<?php
				foreach($services as $serv):
				?>
				
				<b><?php echo $serv['name'];?> | Expire at: <?php echo $serv['next_due_date'];?></b> <br />
				<?php endforeach; ?>
				</div>
				</div>
				<?php } ?>
				
				
				<?php 
				if($premium['premium_until'] != null):
				$now = new DateTime();
				$expDatePlan = new DateTime($premium['premium_until']);
				$expDatePlan = $expDatePlan->diff($now);
				$premium_until_days = (int) $expDatePlan->format("%a");
				if($premium_until_days < 6){
					?>
					<div class="col-lg-12">
					<div class="alert alert-danger">
						Warning! Your plan will be expire after <b><?php echo $premium_until_days;?> days</b>. Please, renew it.
					</div>
					</div>
					<?php
				}
				endif;
				?>
				
				
				<?php
				if (sizeof($accounts) == 0) {
					?>
					<div class="col-lg-12">
					<div class="alert alert-danger">
						The next step is adding eBay account. <a href="link/accounts"
							style="color: #e73d4a;">Click here to add new account.</a>
					</div>
				</div>
					<?php
				}
				?>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 effect1">
					<a href="products/listProducts">
					<div class="dashboard-stat2">
						<div class="display">
							<div class="number">
								<h3 class="font-green-sharp">
									<span data-counter="counterup"
										data-value="<?php echo $total; ?>"><?php echo $total; ?></span>
								</h3>
								<small>Supplier Products</small>
							</div>
							<div class="icon">
								<i class="icon-pie-chart"></i>
							</div>
						</div>
						<?php
						if(empty($premium['product_limit'])){
							$premium['product_limit'] = 10;
						}
						$c = $total / $premium['product_limit'];
						$c = $c * 100;
						$c = (int) $c;
						?>
						<div class="progress-info">
							<div class="progress">
								<span style="width: <?php echo $c;?>%;"
									class="progress-bar progress-bar-success green-sharp"> <span
									class="sr-only"><?php echo $c;?>% progress</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">Limit of plan</div>
								<div class="status-number"><?php echo $c;?>%</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 effect2">
				<a href="link/listProducts">
					<div class="dashboard-stat2 ">
						<div class="display">
							<div class="number">
								<h3 class="font-blue-sharp">
									<span data-counter="counterup"
										data-value="<?php echo $ebay; ?>"><?php echo $ebay; ?></span>
								</h3>
								<small>Ebay Products</small>
							</div>
							<div class="icon">
								<i class="icon-basket"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: 100%;"
									class="progress-bar progress-bar-success blue-sharp"> <span
									class="sr-only"></span>
								</span>
							</div>
							<div class="status">
								<div class="status-title"></div>
								<div class="status-number"></div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 effect3">
				<a href="link/listProducts?ebay_account=all&keyword=&linked=true">
					<div class="dashboard-stat2 ">
						<div class="display">
							<div class="number">
								<h3 class="font-red-haze">
									<span data-counter="counterup"
										data-value="<?php echo $linked; ?>"><?php echo $linked; ?></span>
								</h3>
								<small>Linked eBay Products</small>
							</div>
							<div class="icon">
								<i class="fa fa-link"></i>
							</div>
						</div>
						<div class="progress-info">
						<?php
						if(empty($premium['link_limit'])){
							$premium['link_limit'] = 10;
						}
						$c = $linked / $premium['link_limit'];
						$c = $c * 100;
						$c = (int) $c;
						?>
							<div class="progress">
								<span style="width: <?php echo $c; ?>%;"
									class="progress-bar progress-bar-success red-haze"> <span
									class="sr-only"><?php echo $c; ?>% limit of plan</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">limit of plan</div>
								<div class="status-number"><?php echo $c; ?>%</div>
							</div>
						</div>
					</div>
					</a>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 effect4">
				<a href="billing/services">
					<div class="dashboard-stat2 ">
						<div class="display">
							<div class="number">
							<?php
							if(empty($premium['premium_until'])){
								$premium['premium_until'] = 0;
							}
							?>
								<h3 class="font-purple-soft">
									<span data-counter="counterup"
										data-value="<?php echo $premium['link_limit']; ?>"><?php echo $premium['link_limit']; ?></span>
								</h3>
								<small><?php if($premium['premium_until'] != null):?>
								<?php
									$now = new DateTime();
									$expDatePlan = new DateTime($premium['premium_until']);
									$expDatePlan = $expDatePlan->diff($now);
									
									echo $expDatePlan->format("%a days, %h hours");
									
									$c = $expDatePlan->format("%a") / 30;
									$c = $c * 100;
									$c = (int) $c;
									$c = 100 - $c;
									?>
							<?php else:?>
							Plan doesn't expire
							<?php endif;?></small>
							</div>
							<div class="icon">
								<i class="fa fa-money"></i>
							</div>
						</div>
						<div class="progress-info">
							<div class="progress">
								<span style="width: <?php echo $c; ?>%;"
									class="progress-bar progress-bar-success purple-soft"> <span
									class="sr-only"><?php echo $c; ?>% progres of expire</span>
								</span>
							</div>
							<div class="status">
								<div class="status-title">progres of expire</div>
								<div class="status-number"><?php echo $c; ?>%</div>
							</div>
						</div>
					</div>
					</a>
				</div>
				
				<div class="col-md-12">
				<!--<div class="alert alert-danger">
						We Find problem with Walmart, part of the products that are In Stock are show as OOS, we work on this problem and will be fixed asap <br />
						Локализиран е проблем с API на Walmart, част от продуктите се отчитат като OOS, работим по-проблема и ще бъде отстранен в най-кратки срокове 
								</div>	-->
									<div class="row">

						<div class="col-md-6">
							<div class="portlet light">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-red"></i> <span
											class="caption-subject font-red bold uppercase">Sold products</span>
										<span class="caption-helper">weekly stats...</span>
									</div>
									<div class="actions">
										<div class="btn-group btn-group-devided" data-toggle="buttons">
											<label
												class="btn btn-transparent green btn-outline btn-circle btn-sm active">
												<input name="options" class="toggle" id="option1"
												type="radio">Today & Total
											</label>
										</div>
									</div>
								</div>
								<div class="portlet-body" style="height: 336px;overflow:hidden;">
									<div class="row number-stats margin-bottom-30">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="stat-left">
												<div class="stat-chart">
													<div class="sparkline_bar"></div>
												</div>
												<div class="stat-number">
													<div class="title">Today</div>
													<div class="number"><?php echo $sales_statistic['today']; ?></div>
												</div>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="stat-right">
												<div class="stat-chart">
													<div class="sparkline_bar2"></div>
												</div>
												<div class="stat-number">
													<div class="title">Total</div>
													<div class="number"><?php echo $sales_statistic['total']; ?></div>
												</div>
											</div>
										</div>
									</div>
									<div class="table-scrollable table-scrollable-borderless scroll-today">
										<table class="table table-hover table-light">
											<thead>
												<tr class="uppercase">
													<th colspan="2">Buyer</th>
													<th>Hour</th>
													<th>Earnings</th>
													<th>Paid</th>
													<th>Shipped</th>
													<th>View</th>
												</tr>
											</thead>
											<tbody id="today_buyers">
											<?php foreach($sales_statistic['today_buyers'] as $sale): ?>
												<tr>
													<td class="fit">
													<img class="user-pic rounded" src="./assets/global/img/no-avatar-ff.png"></td>
													<td><a href="account/notify/<?php echo $sale['notification_id'];?>" class="primary-link"><?php echo $sale['name'];?></a></td>
													<td><?php echo date("g:i", strtotime($sale['date']));?></td>
													<td><span class="bold theme-font">$<?php echo $sale['total'];?></span></td>
													<td><?php 
													if($sale['paid']==1){
														echo '<span class="badge badge-success">Paid</span>';
													}else{
														echo '<span class="badge badge-danger">Unpaid</span>';
													}
													?></td>
													<td><?php 
													if($sale['shipped']==1){
														echo '<span class="badge badge-success">Shipped</span>';
													}else{
														echo '<span class="badge badge-danger">Unshipped</span>';
													}
													?></td>
													<td><center><a href="account/notify/<?php echo $sale['notification_id'];?>" style="font-size: 17px;color: rgb(54, 198, 211);"><i class="fa fa-eye"></i></a></center></td>
												</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
										<?php
											if(empty($sales_statistic['today_buyers'])){
												echo '<br /><div class="alert alert-info">No information.</div>';
											}
											?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
						<!-- BEGIN TABLE PORTLET-->
						<div class="portlet light">
							<div class="portlet-title tabbable-line">
								<div class="caption">
									<i class="icon-bell font-green-sharp"></i> <span
										class="caption-subject font-green-sharp bold uppercase">Last Notifications</span>
								</div>
								<ul class="nav nav-tabs">
                                                <li class="active">
                                                    <a aria-expanded="true" href="#ebay" class="active" data-toggle="tab"> EBAY </a>
                                                </li>
                                                <li class="">
                                                    <a aria-expanded="false" href="#system" data-toggle="tab"> SYSTEM </a>
                                                </li>
                                            </ul>
							</div>
							
							
						<div class="portlet-body" style="height:338px;overflow: hidden;padding-top:10px;">
						<div class="tab-content">
						 <div class="tab-pane active" id="ebay">
							<?php
							if (empty($ebayNotify)){
								echo '<div class="alert alert-danger">No notifications.</div>';
							}else{
							?>
							<ul class="feeds ebay-notify">
							<?php
							foreach ($ebayNotify as $note) :
							?>
							<a href="account/notify/<?php echo $note['id'];?>">
												<li>
													<div class="col1" style="width: 96%;">
														<div class="cont" style="margin-right: 0px;">
															<div class="cont-col1">
																<div
																	class="label label-sm label-<?php echo notifyTypes($note['color']); ?> tooltips"
																	data-original-title="<?php echo notifyTypes($note['type']); ?>"
																	style="padding: 5px;">
																	<i class="<?php echo notifyTypes($note['icon']); ?>"></i>
																</div>
															</div>
															<div class="cont-col2">
																<div class="desc">
															<?php 
															echo substr(strip_tags($note['message']),0,44);
															?>.. <small style="font-weight:bold;">[show more]</small>
														</div>
															</div>
														</div>
													</div>
													<div class="col2">
														<div class="date"><?php echo time_elapsed($note['time']); ?></div>
													</div>
											</li>
								</a>
								<?php endforeach; ?>
								</ul>
								<?php } ?>
							</div>
							 <div class="tab-pane" id="system">
								<?php if (empty($systemNotify)) {
								echo '<div class="alert alert-danger">No notifications.</div>';
								}else{?>
								<ul class="feeds system-notify">
									<?php
									foreach ($systemNotify as $note) :
										?>
										<a href="account/notify/<?php echo $note['id'];?>">
												<li>
													<div class="col1" style="width: 96%;">
														<div class="cont" style="margin-right: 0px;">
															<div class="cont-col1">
																<div
																	class="label label-sm label-<?php echo notifyTypes($note['color']); ?> tooltips"
																	data-original-title="<?php echo notifyTypes($note['type']); ?>"
																	style="padding: 5px;">
																	<i class="<?php echo notifyTypes($note['icon']); ?>"></i>
																</div>
															</div>
															<div class="cont-col2">
																<div class="desc">
															<?php echo $note['message'];?>
														</div>
															</div>
														</div>
													</div>
													<div class="col2">
														<div class="date"><?php echo time_elapsed($note['time']); ?></div>
													</div>
											</li>
											</a>
										<?php endforeach; ?>
									</ul>
								<?php } ?>
							</div>
							</div>
						
						</div>
						</div>
						<!-- END TABLE PORTLET-->
					</div>
						
					</div>
				</div>
			</div>

			<div class="row margin-top-10">
				<div class="col-md-12 col-sm-12 effect7">
					<!-- BEGIN PORTLET-->
					<div class="portlet light ">
						<div class="portlet-title">
							<div class="caption caption-md">
								<i class="icon-bar-chart theme-font hide"></i> <span
									class="caption-subject theme-font bold uppercase">Products
									updated</span> <span class="caption-helper hide">weekly
									stats...</span>
							</div>
							<div class="actions">
								<div class="btn-group btn-group-devided" data-toggle="buttons">
									<label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="">Past 24 hours
									</label>
								</div>
							</div>
						</div>
						<div class="portlet-body" id="products-updated-content">
						
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>

	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<script type="text/javascript">

<?php if(isset($sales_statistic['hours'])){ ?>

<?php
$ss_hours = array();
foreach($sales_statistic['hours'] as $c){
	$ss_hours[] = $c;
}
?>

var sparkline = <?php echo json_encode($ss_hours);?>;
<?php } ?>

<?php if(isset($sales_statistic['month'])){ ?>

<?php
$ss_month = array();
foreach($sales_statistic['month'] as $c2){
	$ss_month[] = $c2;
}
?>

var sparkline2 = <?php echo json_encode($ss_month);?>;
<?php } ?>

</script>
<?php
function showInFooter(){
?>
<script type="text/javascript">
$(document).ready(function () {
	
	if(sparkline !=''){
		$(".sparkline_bar").sparkline(sparkline,{
			type:"bar",
			width:"100",
			barWidth:5,
			height:"55",
			barColor:"#35AA47",
			negBarColor:"#35AA47"
		});
	}

	if(sparkline2 !=''){
		$(".sparkline_bar2").sparkline(sparkline2,{
			type:"bar",
			width:"100",
			barWidth:5,
			height:"55",
			barColor:"#ffb848",
			negBarColor:"#e02222"
		});
	}
	
    $('.ebay-notify, .system-notify').slimScroll({
        height: '325px'
    });  
	
	$('.scroll-today').slimScroll({
        height: '220px'
    });
	
	function ShowProductsUpdatedHTML(data){
		
		var html = '';
		
		if(data.status == true){
			
			var head = '<div class="row number-stats margin-bottom-30">' 
						+ '<div class="col-md-6 col-sm-6 col-xs-6">' 
								+ '	<div class="stat-left">' 
								+ '		<div class="stat-number">' 
								+ '			<div class="title">Total products</div>' 
								+ '			<div class="number">' 
											+ data.total 
								+ '			</div>' 
							+ '			</div>' 
									+ '</div>' 
							+ '	</div>' 
								+ '<div class="col-md-6 col-sm-6 col-xs-6">' 
							+ '		<div class="stat-right">' 
									+ '	<div class="stat-number">' 
										+ '	<div class="title">Updated recently</div>' 
										+ '	<div class="number">' 
												 + data.total_updated 
									+ '		</div>' 
								+ '		</div>' 
								+ '	</div>' 
							+ '	</div>' 
							+ '</div>' 
							+ '<div class="table-scrollable table-scrollable-borderless">' 
							+ '	<table class="table table-hover table-light">' 
							+ '		<thead>' 
								+ '		<tr class="uppercase">' 
								+ '			<th>Date</th>' 
								+ '			<th>Store</th>' 
								+ '			<th>ID</th>' 
								+ '			<th>Name</th>' 
									+ '		<th>Old Price</th>' 
									+ '		<th>New Price</th>' 
									+ '		<th>Change</th>' 
									+ '		<th>Curr</th>' 
									+ '		<th>Extra details</th>' 
								+ '			<th>Stock</th>' 
									+ '	</tr>' 
								+ '	</thead>';
				var content = '';
				data.products.updated.forEach(function(row) {
					content = content + '<tr>'
						+ ' <td>'+row.changed_on+'</td>'
						+ ' <td>'+row.friendly_name+'</td>'
						+ ' <td>'+row.remote_id+'</td>'
						+ ' <td><a href="'+row.url+'" class="primary-link">'+row.title+'</a></td>'
						+ ' <td>'+row.old_price+'</td>'
						+ ' <td><span class="bold theme-font">'+row.price+'</span></td>'
						+ ' <td>'+row.change_price+'</td>'
						+ ' <td>'+row.currency+'</td>'
						+ ' <td>'+row.extra+'</td>'
						+ ' <td><i class="fa fa-'+row.stock_icon+'"></i></td>'
					+ '</tr>';
				});
				var footer = '</table><br /><a href="Account/ProductsUpdated">Click here to view all updated products...</a>';

				html = head + content + footer;
								
		} else {
			html = '<div class="alert alert-danger">Failed to loading data...</div>';
		}
		
		$("#products-updated-content").html(html).hide().slideDown();
	}
	
	
	function GetProductsUpdated(){
		$("#products-updated-content").html('Loading...');
		$.ajax({
			 type: 'POST',
			 url: 'account/ajax_dashboard/products_updated',
			 data: "",
			 dataType: 'json',
			 success: function(data) {
				 ShowProductsUpdatedHTML(data);  
			 },
			 dataType: "json"
		});
	}

	GetProductsUpdated();
	
	function GetTodayBuyers(){
		//$("#today_buyers").html('<tr><td></td> </tr>');
	}
	GetTodayBuyers();
	
});
</script>
<?php
}
?>