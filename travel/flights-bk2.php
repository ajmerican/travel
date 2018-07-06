<?php
	include_once 'class/sabre/sabre.class.php';
	$obj  = new sabre();

	include_once 'header.php';
?>
<?php
	$from			= $lib->getRequest('from');

	preg_match('#\((.*?)\)#', $from, $match);
	$origin		= $match[1];

	$to				= $lib->getRequest('to');

	preg_match('#\((.*?)\)#', $to, $match);
	$destination	= $match[1];

	$dateStart	= date("Y-m-d", strtotime($lib->getRequest('date-start')));
	$dateEnd		= date("Y-m-d", strtotime($lib->getRequest('date-end')));
	//$dateEnd		= '2018-04-30';
	$class			= $lib->getRequest('class');
	$seats			= $lib->getRequest('seats');
	$childs			= $lib->getRequest('childs');

	$result = $obj->searchFlights($origin, $destination, $dateStart, $dateEnd);
?>
<!-- end:header-top -->
<div class="search-price">
	<div class="container">
		<div class="price">
			<span><b>YOUR SEARCH:</b> <?php echo $from; ?> - <?php echo $to; ?> <?php echo $seats; ?> Persons DEPART: <?php echo $dateStart; ?> RETURN:<?php echo $dateEnd; ?></span>
			<div class="search-container">
				<input type="text" disabled="disabled" placeholder="CHANGE?" name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
			</div>
		</div>
	</div>
</div>
<div class="box-search">
	<div class="container">
		<div class="row">
			<form method="post" action="flights.php">
			<div class="col-xxs-12 col-xs-3 mt">
				<div class="input-field">
					<label for="from">From:</label>
					<input type="text" class="form-control airports" id="from-place" name="from" value="<?php echo $from; ?>" placeholder="Los Angeles, USA" autocomplete="off" />
				</div>
			</div>
			<div class="col-xxs-12 col-xs-3 mt">
				<div class="input-field">
					<label for="from">To:</label>
					<input type="text" class="form-control airports" id="to-place" name="to" value="<?php echo $to; ?>" placeholder="Tokyo, Japan" autocomplete="off" />
				</div>
			</div>
			<div class="col-xxs-12 col-xs-3 mt alternate">
				<div class="input-field">
					<label for="date-start">Check In:</label>
					<input type="text" class="form-control" id="date-start" name="date-start" value="<?php echo $lib->getRequest('date-start'); ?>" placeholder="mm/dd/yyyy"/>
				</div>
			</div>
			<div class="col-xxs-12 col-xs-3 mt alternate">
				<div class="input-field">
					<label for="date-end">Check Out:</label>
					<input type="text" class="form-control" id="date-end" name="date-end" value="<?php echo $lib->getRequest('date-end'); ?>" placeholder="mm/dd/yyyy"/>
				</div>
			</div>
			<div class="col-xxs-12 col-xs-3 mt">
				<section>
					<label for="class">Class:</label>
					<select class="cs-select cs-skin-border" name="class">
						<option value="economy" <?php echo ($class == 'economy') ? 'selected="selected"' : ''; ?>>Economy</option>
						<option value="first" <?php echo ($class == 'first') ? 'selected="selected"' : ''; ?>>First</option>
						<option value="business" <?php echo ($class == 'business') ? 'selected="selected"' : ''; ?>>Business</option>
					</select>
				</section>
			</div>
			<div class="col-xxs-12 col-xs-3 mt">
				<section>
					<label for="class">Adult:</label>
					<select class="cs-select cs-skin-border" name="seats">
						<option value="1" <?php echo ($seats == '1') ? 'selected="selected"' : ''; ?>>1</option>
						<option value="2" <?php echo ($seats == '2') ? 'selected="selected"' : ''; ?>>2</option>
						<option value="3" <?php echo ($seats == '3') ? 'selected="selected"' : ''; ?>>3</option>
						<option value="4" <?php echo ($seats == '4') ? 'selected="selected"' : ''; ?>>4</option>
					</select>
				</section>
			</div>
			<div class="col-xxs-12 col-xs-3 mt">
				<section>
					<label for="class">Children:</label>
					<select class="cs-select cs-skin-border" name="childs">
						<option value="0" <?php echo ($seats == 0) ? 'selected="selected"' : ''; ?>>0</option>
						<option value="1" <?php echo ($seats == '1') ? 'selected="selected"' : ''; ?>>1</option>
						<option value="2" <?php echo ($seats == 2) ? 'selected="selected"' : ''; ?>>2</option>
						<option value="3" <?php echo ($seats == 3) ? 'selected="selected"' : ''; ?>>3</option>
						<option value="4" <?php echo ($seats == 4) ? 'selected="selected"' : ''; ?>>4</option>
					</select>
				</section>
			</div>
			<div class="col-xxs-12 col-xs-3 mt">
				<input type="submit" class="btn btn-primary btn-block" value="Search Flight">
			</div>
		</form>
		</div>
	</div>
</div>
<?php
	if(isset($result['FareInfo'][0]))
	{
		foreach ($result['FareInfo'] as $key => $value) {
			$flight	= $value['AirItinerary']['OriginDestinationOptions']['OriginDestinationOption'][0]['FlightSegment'][0];
			$flightPrice	= $value['AirItineraryPricingInfo']['PTC_FareBreakdowns']['PTC_FareBreakdown'];
?>
<div class="price-section">
	<div class="container">
		<div class="flight">
			<h1><i class="icon-airplane"></i> <?php echo $origin; ?> to <?php echo $destination; ?><br/><span><?php echo date('l, j F, Y', strtotime($value['DepartureDateTime'])); ?></span></h1>
		</div>
		<div class="row">
		<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 ass">
		<div class="arrival-time">
			<ul>
					<li class="one"><b><?php echo date('h:i', strtotime($value['DepartureDateTime'])); ?></b> <span><?php echo $value['AirlineCodes'][0]; ?></span> <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACWSURBVGhD7djBCYNAFEXRwQoM2IUL+zENWIp9ZeFOUoe2EIhPdCC4cGHAmf+5By6IC+GBiBoAAAAA3KhS9XZo10ONalbNesKiQg3qu2d6TKs+ijG5OY6ZFGNywJgzneoT9VZxyN9jXur3Yqm7PMbNEDe3ViounlyMyIXLESZfGp/K/AhXH1alMv+pG7n4+QAAAADcJYQFgzq1QcGQyMkAAAAASUVORK5CYII="></li>
						<li class="one"><b><?php echo date('h:i', strtotime($flight['ArrivalDateTime'])); ?></b> <span><?php echo $flight['ArrivalAirport']['LocationCode']; ?></span></li>
						<li><span>02.15</span></li>
						<li><span>Non-stop</span></li>
						<li><span>Flight <?php echo $flight['FlightNumber']; ?></span></li>
						<li class="two"><p><?php echo $value['AirItineraryPricingInfo']['FareInfos']['FareInfo'][0]['TPA_Extensions']['SeatsRemaining']['Number']; ?> SEATS<br/>LEFT</p></li>
				</ul>
		</div>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ssa">
			<div class="arrival-time">
					<h2 class="price">$<?php echo $flightPrice['PassengerFare']['TotalFare']['Amount']; ?><br/><span>Base Fare:$<?php echo $flightPrice['PassengerFare']['BaseFare']['Amount']; ?><br/>incl Taxes:$<?php echo $flightPrice['PassengerFare']['Taxes']['TotalTax']['Amount']; ?></span></h2>
						<form>
							<div class="radio">
								<label><input type="radio" name="optradio"></label>
							</div>
						</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } } else{
	echo 'No flight fond of your search';
} ?>
<?php include_once 'footer.php'; ?>
