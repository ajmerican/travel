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
		$id			= $lib->getRequest('id');
		$booking	= $flight->getBookingById($id);
		$booking	= $booking[0];
		$cust			= $flight->getCustomerById($booking['u_id']);
		$cust			= $cust[0]
?>
<div class="booked">
	<div class="flight-logo">
		<?php if(isset($booking['hotel_name']) && $booking['hotel_name'] != '') { ?>
			<img src="images/hotel.png" class="img-responsive">
		<?php } else { ?>
			<img src="images/flight.png" class="img-responsive">
		<?php } ?>
	</div>
    <div class="title"><h2>
			<?php if(isset($booking['hotel_name']) && $booking['hotel_name'] != '') { ?>
				<?php echo $booking['hotel_name']; ?>
			<?php } else { ?>
				<?php echo $booking['from_city']; ?> <img src="images/download.png"> <?php echo $booking['to_city']; ?>
				<h5>Trip ID : <?php echo $booking['id']; ?></h5>
			<?php } ?>
		</h2>

        <p>Booked by <?php echo $cust['first_name'].' '.$cust['last_name']; ?> on <?php echo date('D, j M Y at H:i', strtotime($booking['created_date'])); ?> hrs</p>
    </div>
</div>
<div class="booking-by">
	<span> <i class="fa fa-minus-circle"></i> To Cancel this trip please contact <a href="#">Customer Support</a></span>
</div>
<div class="cancel">
	<h3>
		<?php if(isset($booking['hotel_name']) && $booking['hotel_name'] != '') { ?>
			<?php echo $booking['hotel_name']; ?> @ <span><?php echo date('D, j M Y', strtotime($booking['time_from'])); ?></span>
		<?php } else { ?>
			<?php echo $booking['from_city']; ?> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACWSURBVGhD7djBCYNAFEXRwQoM2IUL+zENWIp9ZeFOUoe2EIhPdCC4cGHAmf+5By6IC+GBiBoAAAAA3KhS9XZo10ONalbNesKiQg3qu2d6TKs+ijG5OY6ZFGNywJgzneoT9VZxyN9jXur3Yqm7PMbNEDe3ViounlyMyIXLESZfGp/K/AhXH1alMv+pG7n4+QAAAADcJYQFgzq1QcGQyMkAAAAASUVORK5CYII=" class="img-responsive"/> <?php echo $booking['to_city']; ?> <span><?php echo date('D, j M Y', strtotime($booking['time_from'])); ?></span> <a href="#">Fare rules</a>
		<?php } ?>
		</h3>
    <ul class="flight-cancel">
    	<li>
				<?php if(isset($booking['hotel_name']) && $booking['hotel_name'] != '') { ?>
					<span>Hotel Code <br/> <?php echo $booking['flight_id']; ?></span>
				<?php } else { ?>
					<img src="images/Go-air-logo.png" class="" width="35"/> <span>Go Air <br/> <?php echo $booking['flight_id']; ?></span>
				<?php } ?>
				</li>
        <li><h2><?php echo $booking['from_city']; ?> <?php echo date('H:i', strtotime($booking['time_from'])); ?></h2><br/><span><?php echo date('D, j M Y', strtotime($booking['time_from'])); ?></span><br/><!--<small>Mumbai-Chatrapati shivaji Airport<br/>(BOM),Terminal Terminal 1B</small>--></li>
        <!--<li> <i class="fa fa-clock"></i> <br/><span>1h 35m</span></li>-->
        <li><h2><?php echo date('H:i', strtotime($booking['time_to'])); ?> <?php echo $booking['to_city']; ?></h2><br/><span><?php echo date('D, j M Y', strtotime($booking['time_to'])); ?></span><br/><!--<small>Banglore - Kempegowada<br/>international Airport (BLR)</small>--></li>
    </ul>
</div>
<?php if(isset($booking['hotel_name']) && $booking['hotel_name'] != '') { ?>
	
<?php } else { ?>
	<div class="travel-detail">
		<h4>Traveller Details</h4>
	    <h5><?php echo $booking['from_city']; ?> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACWSURBVGhD7djBCYNAFEXRwQoM2IUL+zENWIp9ZeFOUoe2EIhPdCC4cGHAmf+5By6IC+GBiBoAAAAA3KhS9XZo10ONalbNesKiQg3qu2d6TKs+ijG5OY6ZFGNywJgzneoT9VZxyN9jXur3Yqm7PMbNEDe3ViounlyMyIXLESZfGp/K/AhXH1alMv+pG7n4+QAAAADcJYQFgzq1QcGQyMkAAAAASUVORK5CYII=" class="img-responsive"/> <?php echo $booking['to_city']; ?></h5>
	    <table class="status">
	    	<thead>
	        	<tr>
	            	<td>Name</td>
	                <td>Sector</td>
	                <td>PNR</td>
	                <td>Ticket No</td>
	                <td>Seat No</td>
	                <td>Status</td>
	            </tr>
	        </thead>
	        <tr>
	        	<td><?php echo $cust['first_name'].' '.$cust['last_name']; ?></td>
	            <td><span><?php echo $booking['from_city']; ?> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACWSURBVGhD7djBCYNAFEXRwQoM2IUL+zENWIp9ZeFOUoe2EIhPdCC4cGHAmf+5By6IC+GBiBoAAAAA3KhS9XZo10ONalbNesKiQg3qu2d6TKs+ijG5OY6ZFGNywJgzneoT9VZxyN9jXur3Yqm7PMbNEDe3ViounlyMyIXLESZfGp/K/AhXH1alMv+pG7n4+QAAAADcJYQFgzq1QcGQyMkAAAAASUVORK5CYII="/> <?php echo $booking['to_city']; ?></span></td>
	            <td><?php echo $booking['pnr_no']; ?></td>
	            <td><?php echo $booking['portal_pnr_no']; ?></td>
	            <td>--</td>
	            <td><small>Completed</small></td>
	        </tr>
	    </table>
	</div>
<?php } ?>

<div class="payment-detail">
	<h2><b>Payment Details</b></h2>
    	<table>
    	<tr>
        	<td>Base fare</td>
            <td><span>Rs 199</span></td>
        </tr>
        <tr>
        	<td>Taxes & fees</td>
            <td><span>$ 189</span></td>
        </tr>
        <tr>
        	<td>Total</td>
            <td><span><b>$ 388</b></span><br/>$ 388 via credit card **** **** **** 1111</td>
        </tr>
    </table>
</div>
<div class="contact-detail">
	<h2><b>Contact Details</b></h2>
    	<table>
    	<tr>
        	<td>Name</td>
            <td><span><?php echo $cust['first_name'].' '.$cust['last_name']; ?></span></td>
        </tr>
        <tr>
        	<td>Phone</td>
            <td><span><?php echo $cust['mobile']; ?><small>Mobile</small></span></td>
        </tr>
        <tr>
        	<td>Email</td>
            <td><span><?php echo $cust['email']; ?></td>
        </tr>
    </table>
</div>
<?php
	} else {
		$_SESSION['redirctURL']	= 'mybookings.php';
		include_once 'login.php';
	}
?>
<?php
include_once 'right.php';
include_once 'footer.php';
