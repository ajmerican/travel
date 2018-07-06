<?php
	include_once 'class/sabre/sabre.class.php';
	$obj  = new sabre();

	include_once 'header.php';
?>
<?php
	$city			= $lib->getRequest('city');
	$dateStart	= date("Y-m-d", strtotime($lib->getRequest('date-start')));
	$dateEnd		= date("Y-m-d", strtotime($lib->getRequest('date-end')));
	$rooms			= $lib->getRequest('rooms');
	$adults			= $lib->getRequest('adults');
	$childs			= $lib->getRequest('childs');

	$result = $obj->searchHotels($city, $dateStart, $dateEnd, $rooms);
	//$lib->debug($result);
?>
<!-- end:header-top -->
<div class="search-price">
	<div class="container">
		<div class="price">
			<span><b>YOUR SEARCH:</b> <?php echo $city; ?> <?php echo $adults; ?> Persons On <?php echo $dateStart; ?> Checkout:<?php echo $dateEnd; ?></span>
			<div class="search-container">
				<input type="text" disabled="disabled" placeholder="CHANGE?" name="search">
				<button type="submit"><i class="fa fa-search"></i></button>
			</div>
		</div>
	</div>
</div>
<div class="box-search">
	<div class="container">
		<form method="post" action="hotels.php">
			<div class="row">
				<div class="col-xxs-12 col-xs-12 mt">
					<div class="input-field">
						<label for="from">City:</label>
						<input type="text" class="form-control" name="city" placeholder="Los Angeles, USA"/>
					</div>
				</div>
				<div class="col-xxs-12 col-xs-6 mt alternate">
					<div class="input-field">
						<label for="date-start">Check In:</label>
						<input type="text" class="form-control" id="date-start" name="date-start" placeholder="mm/dd/yyyy"/>
					</div>
				</div>
				<div class="col-xxs-12 col-xs-6 mt alternate">
					<div class="input-field">
						<label for="date-end">Check Out:</label>
						<input type="text" class="form-control" id="date-end" name="date-end" placeholder="mm/dd/yyyy"/>
					</div>
				</div>
				<div class="col-sm-12 mt">
					<section>
						<label for="class">Rooms:</label>
						<select class="cs-select cs-skin-border" name="rooms">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</section>
				</div>
				<div class="col-xxs-12 col-xs-6 mt">
					<section>
						<label for="class">Adult:</label>
						<select class="cs-select cs-skin-border" name="adults">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
						</select>
					</section>
				</div>
				<div class="col-xxs-12 col-xs-6 mt">
					<section>
						<label for="class">Children:</label>
						<select class="cs-select cs-skin-border" name="childs">
							<option value="1">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
						</select>
					</section>
				</div>
				<div class="col-xs-12">
					<input type="submit" class="btn btn-primary btn-block" name="hotel-submit" value="Search Hotel">
				</div>
			</div>
		</form>
	</div>
</div>
<div class="price-section">
	<div class="container">
<?php
	if(count($result) > 0)
	{
		foreach ($result as $key => $value) {
?>
<div class="hotel-list">
	<div class="row">
    	<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
        	<div class="imge"><img src="http://vcmp-hotels.cert.sabre.com/image/upload/f_auto,q_auto:best,t_vcmp_thumb/hotel/i/1/xrub9iwrh6skrgdxpqmy.jpg" class="img-responsive">
            </div>
        </div>
        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
        	<div class="hotel-description">
            	<h4><a href="hoteldetail.php?id=<?php echo $value['BasicPropertyInfo']['@attributes']['HotelCode']; ?>"><?php echo $value['BasicPropertyInfo']['@attributes']['HotelName']; ?></a></h4>
                <p class="sm-desc"><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span><span class="glyphicon glyphicon-star"></span> <?php echo $value['BasicPropertyInfo']['Address']['AddressLine'][0]; ?> <i class="glyphicon glyphicon-map-marker"></i></p>
                <p><?php echo $value['BasicPropertyInfo']['LocationDescription']['Text']; ?></p>
								<p>ADA Accessible: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['ADA_Accessible']['@attributes']['Ind']; ?>, Adults Only: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['AdultsOnly']['@attributes']['Ind']; ?>, Beach Front: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['BeachFront']['@attributes']['Ind']; ?>, Breakfast: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['Breakfast']['@attributes']['Ind']; ?>, Business Center: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['BusinessCenter']['@attributes']['Ind']; ?>, Business Ready: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['BusinessReady']['@attributes']['Ind']; ?>, Conventions: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['Conventions']['@attributes']['Ind']; ?>, Dataport: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['Dataport']['@attributes']['Ind']; ?>, Dining: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['Dining']['@attributes']['Ind']; ?>, Dry Clean: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['DryClean']['@attributes']['Ind']; ?>, Eco Certified: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['EcoCertified']['@attributes']['Ind']; ?>, Executive Floors: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['ExecutiveFloors']['@attributes']['Ind']; ?>, Fitness Center: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FitnessCenter']['@attributes']['Ind']; ?>, Free Local Calls: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeLocalCalls']['@attributes']['Ind']; ?>, Free Parking: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeParking']['@attributes']['Ind']; ?>, Free Shuttle: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeShuttle']['@attributes']['Ind']; ?>, Free Wifi In Meeting Rooms: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeWifiInMeetingRooms']['@attributes']['Ind']; ?>, Free Wifi In Public Spaces: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeWifiInPublicSpaces']['@attributes']['Ind']; ?>, Free Wifi In Rooms: <?php echo $value['BasicPropertyInfo']['PropertyOptionInfo']['FreeWifiInRooms']['@attributes']['Ind']; ?>
							</p>
                <span class="view-detail"><i><a href="hoteldetail.php?id=<?php echo $value['BasicPropertyInfo']['@attributes']['HotelCode']; ?>">View Details >></a></i></span>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<form action="book.php" method="post">
						<input type="hidden" name="module" value="hotel">
						<input type="hidden" name="hotel_name" value="<?php echo $value['BasicPropertyInfo']['@attributes']['HotelName']; ?>">
						<input type="hidden" name="date" value="<?php echo $dateStart; ?>">
						<input type="hidden" name="time_to" value="<?php echo $dateEnd; ?>">
						<input type="hidden" name="flight_id" value="<?php echo $value['BasicPropertyInfo']['@attributes']['HotelCode']; ?>">
        		<div class="price-box">
            	<h2><?php echo isset($value['BasicPropertyInfo']['RateRange']['@attributes']['Min']) ? '<sup>$</sup>'.$value['BasicPropertyInfo']['RateRange']['@attributes']['Min'] : 'N/A'; ?></h2>
            </div>
            <div class="price-box-button"><input type="submit" name="book" value="Book"></div>
					</form>
        </div>
    </div>
</div>
<?php } } else{
	echo 'No Hotel fond of your search';
} ?>
</div>
</div>
<?php include_once 'footer.php'; ?>
