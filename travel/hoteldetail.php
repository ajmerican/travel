<?php
	include_once 'class/sabre/sabre.class.php';
	$obj  = new sabre();

	include_once 'header.php';
?>
<?php
	$id			= $lib->getRequest('id');

	$result = $obj->getHotelDetail($id);
	//$lib->debug($result);

	//http://devitems.com/preview/hotel-atr/room-details.html
	//
?>
<div class="price-section">
	<div class="container">
<?php
	if(count($result) > 0)
	{
?>
	<div class="row">
  	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
			<!-- Title -->
          <h1 class="mt-4"><?php echo $result['HotelInfo']['@attributes']['HotelName']; ?></h1>
          <!-- Author -->
          <p class="lead">
            By <a href="#"><?php echo $result['HotelInfo']['@attributes']['ChainName']; ?></a>
          </p>
					<hr />
					<img class="img-fluid rounded" src="<?php echo $result['HotelMediaInfo']['ImageItems']['ImageItem'][0]['Images']['Image']['@attributes']['Url']; ?>" alt="">
					<hr />
					<h2>Detail</h2>
					<p><?php echo $result['HotelDescriptiveInfo']['Descriptions']['Description']['Text']; ?></p>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
      <div class="hotel-details">
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
				<h3>Address</h3>
				<hr />
				<p><?php echo $result['HotelDescriptiveInfo']['LocationInfo']['Address']['AddressLine1']; ?><br />
				<?php echo $result['HotelDescriptiveInfo']['LocationInfo']['Address']['AddressLine2']; ?><br />
			<?php echo $result['HotelDescriptiveInfo']['LocationInfo']['Address']['CityName']; ?> - <?php echo $result['HotelDescriptiveInfo']['LocationInfo']['Address']['PostalCode']; ?></p>
			<p>Phone: <?php echo $result['HotelDescriptiveInfo']['LocationInfo']['Contact']['@attributes']['Phone']; ?></p>
				<h3>Amenities</h3>
				<ul>
					<?php foreach($result['HotelDescriptiveInfo']['Amenities']['Amenity'] AS $key => $val) { ?>
						<li><i class="glyphicon glyphicon-ok"></i> <?php echo $val['@attributes']['Description']; ?></li>
					<?php } ?>
				</ul>
      </div>
    </div>
  </div>

<?php } else{
	echo 'No Hotel fond of your search';
} ?>
</div>
</div>
<?php include_once 'footer.php'; ?>
