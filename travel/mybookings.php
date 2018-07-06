<?php
	require_once("config.php");
	include_once 'class/flight.class.php';
	$flight  = new flight();

	include_once 'header.php';
	include_once 'left.php';
?>
<?php
	$ret	= false;
	if($login->checklogin()){
				$booking	= $flight->getBookingByUserId();
				?>
				<ul class="booking-list">
				<?php
				foreach ($booking as $key => $value) {
				?>
					<li>
						<time datetime="<?php echo $value['time_from']; ?>">
							<span class="day"><?php echo date('d', strtotime($value['time_from'])); ?></span>
							<span class="month"><?php echo date('M', strtotime($value['time_from'])); ?></span>
							<span class="year"><?php echo date('Y', strtotime($value['time_from'])); ?></span>
							<span class="time"><?php echo date('h:i A', strtotime($value['time_from'])); ?></span>
						</time>
						<div class="info">
							<div class="row">
								<div class="col-sm-12">
									<h2 class="title">
										<?php if(isset($value['hotel_name']) && $value['hotel_name'] != '') { ?>
											<?php echo $value['hotel_name']; ?>
										<?php } else { ?>
											<?php echo $value['from_city']; ?> to <?php echo $value['to_city']; ?>
										<?php } ?>
									</h2>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<?php if(isset($value['hotel_name']) && $value['hotel_name'] != '') { ?>
										CheckIn:
									<?php } else { ?>
										Arrival:
									<?php } ?>
								</div>
								<div class="col-sm-9"><?php echo date('d M Y (H:i)', strtotime($value['time_from'])); ?></div>
							</div>
							<?php if(isset($value['hotel_name']) && $value['hotel_name'] != '') { ?>

							<?php } else { ?>
								<div class="row">
									<div class="col-sm-3">Reparture:</div>
									<div class="col-sm-9"><?php echo date('d M Y (H:i)', strtotime($value['time_to'])); ?></div>
								</div>
							<?php } ?>
							<div class="row">
								<div class="col-sm-3">
									<?php if(isset($value['hotel_name']) && $value['hotel_name'] != '') { ?>
										Hotem #:
									<?php } else { ?>
										Flight #:
									<?php } ?>
								</div>
								<div class="col-sm-9"><?php echo $value['flight_id']; ?></div>
							</div>
							<div class="row">
								<div class="col-sm-3"><a href="viewbooking.php?id=<?php echo $value['id']; ?>">View Detail</a></div>
								<div class="col-sm-9"></div>
							</div>
						</div>
					</li>
				<?php
				}
				?>
			</ul>
				<?php
	} else {
		$_SESSION['redirctURL']	= 'mybookings.php';
		include_once 'login.php';
	}
?>
<?php
include_once 'right.php';
include_once 'footer.php';
