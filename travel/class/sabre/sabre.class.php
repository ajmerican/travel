<?php
  include_once dirname(__FILE__) . '/configuration/SACSConfig.php';

  class sabre{
    private static $token = null;
    private static $expirationDate = 0;
    private $config;

    public function __construct() {
        $this->config = SACSConfig::getInstance();
    }

    public function searchAirPorts($seasrch_key) {
        $paras = array(
                "query" => $seasrch_key,
                "category" => 'AIR',
                "limit"   => 10
        );
        $url = "/v1/lists/utilities/geoservices/autocomplete";

        $result = $this->executeGetCall($url, $paras);

        return $result;
    }

    private function executeGetCall($path, $request) {
        $result = curl_exec($this->prepareCall('GET', $path, $request));
        return json_decode($result,true);
    }

    private function prepareCall($callType, $path, $request) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $callType);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = $this->buildHeaders();
        switch ($callType) {
        case 'GET':
            $url = $path;
            if ($request != null) {
                $url = $this->config->getRestProperty("environment").$path.'?'.http_build_query($request);
            }
            curl_setopt($ch, CURLOPT_URL, $url);
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_URL, $this->config->getRestProperty("environment").$path);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            array_push($headers, 'Content-Type: application/json');
            break;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        return $ch;
    }

    private function buildHeaders() {
        $headers = array(
            'Authorization: Bearer '.$this->getToken()->access_token,
            'Accept: */*'
        );
        return $headers;
    }

    private function getToken() {
        if (sabre::$token == null || time() > sabre::$expirationDate) {
            sabre::$token = $this->callForToken();
            sabre::$expirationDate = time() + sabre::$token->expires_in;
        }
        return sabre::$token;
    }

    private function callForToken() {
        $ch = curl_init($this->config->getRestProperty("environment")."/v2/auth/token");
        $vars = "grant_type=client_credentials";
        $headers = array(
            'Authorization: Basic '.$this->buildCredentials(),
            'Accept: */*',
            'Content-Type: application/x-www-form-urlencoded'
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }

    private function buildCredentials() {
        $credentials = $this->config->getRestProperty("formatVersion").":".
                $this->config->getRestProperty("userId").":".
                $this->config->getRestProperty("group").":".
                $this->config->getRestProperty("domain");
        $secret = base64_encode($this->config->getRestProperty("clientSecret"));
        return base64_encode(base64_encode($credentials).":".$secret);
    }

    public function searchFlights($origin, $destination, $departuredate, $returndate, $lengthofstay = '3,4,5,6,7', $minfare=null, $maxfare=null, $pointofsalecountry=null) {
        //$url = "/v2/shop/flights/fares";
        $url = "/v1/shop/flights";

        $paras = array(
                "origin"      => $origin,
                "destination" => $destination,
                "departuredate" => $departuredate,
                "lengthofstay"=> $lengthofstay,
                //"limit"       => 5,
                //"offset"      => 1
        );

        if($returndate){
          $paras['returndate'] = $returndate;
        }

        if($minfare){
          $paras['minfare'] = $minfare;
        }

        if($maxfare){
          $paras['maxfare'] = $maxfare;
        }

        if($pointofsalecountry){
          $paras['pointofsalecountry'] = $pointofsalecountry;
        }

        $result = $this->executeGetCall($url, $paras);
        if(0){
          echo '<pre>';
          print_r($result);
          echo '</pre>';
          echo '<hr />';
        }
        if(isset($result['FareInfo'][0]['Links'][0]['href'])){
          //$url = $result['FareInfo'][0]['Links'][0]['href'];
          //$result = $this->executeGetCall($url, null);
          if(0){
            echo '<pre>';
            print_r($result);
            echo '</pre>';
            echo '<hr />';
          }
          //$result = $this->executePostCall("/v1.8.6/shop/flights?mode=live", //$this->getRequest($origin, $destination, $departuredate));
          if(0){
            echo '<pre>';
            print_r($result);
            echo '</pre>';
          }
        }

        return $result;
    }

    public function executePostCall($path, $request) {
        $result = curl_exec($this->prepareCall('POST', $path, $request));
        return json_decode($result);
    }

    private function getRequest($origin, $destination, $departureDate) {
        $request = '{
            "OTA_AirLowFareSearchRQ": {
		"OriginDestinationInformation": [
			{
                            "DepartureDateTime": "'.$departureDate.'T00:00:00",
                            "DestinationLocation": {
				                        "LocationCode": "'.$destination.
                            '"},
                            "OriginLocation": {
                                "LocationCode": "'.$origin.
                            '"},
                            "RPH":"1"
			}
		],
		"POS": {
                    "Source": [
                        {
                            "RequestorID": {
                                "CompanyName": {
                                    "Code": "TN"
				},
				"ID": "REQ.ID",
				"Type": "0.AAA.X"
                            }
			}
                    ]
		},
		"TPA_Extensions": {
                    "IntelliSellTransaction": {
                        "RequestType": {
                            "Name": "5ITINS"
			}
                    }
		},
		"TravelerInfoSummary": {
                    "AirTravelerAvail": [
                        {
                            "PassengerTypeQuantity": [
                                {
                                    "Code": "ADT",
                                    "Quantity": 1
				}
                            ]
			}
                    ]
		}
            }
        }';
        return $request;
    }

    public function searchHotels($origin, $destination, $departuredate, $returndate, $lengthofstay = '3,4,5,6,7', $minfare=null, $maxfare=null, $pointofsalecountry=null) {
      $res = '<OTA_HotelAvailRS xmlns="http://webservices.sabre.com/sabreXML/2011/10" Version="2.3.0" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:stl="http://services.sabre.com/STL/v01">
    <stl:ApplicationResults status="Complete">
        <stl:Success timeStamp="2015-08-03T09:35:56-05:00" />
    </stl:ApplicationResults>
    <AdditionalAvail Ind="true" />
    <AvailabilityOptions>
        <AvailabilityOption RPH="001">
            <BasicPropertyInfo AreaID="006E" ChainCode="OM" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0031838" HotelName="OMNI MANDALAY HOTEL" Latitude="32.866860" Longitude="-96.938500">
                <Address>
                    <AddressLine>221 E LAS COLINAS BLVD</AddressLine>
                    <AddressLine>IRVING TX 75039</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="1-972-556-0729" Phone="1-972-556-0800" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>LAS COLINAS</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>5 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="false" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="true" />
                    <DryClean Ind="true" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="true" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="false" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="false" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="true" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="true" />
                    <Pets Ind="true" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="true" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="false" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="517.00" Min="149.00" />
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="002">
            <BasicPropertyInfo AreaID="012SE" ChainCode="HI" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0088772" HotelName="HOLIDAY INN EXP STES WEST" Latitude="32.764718" Longitude="-96.895128">
                <Address>
                    <AddressLine>4321 COMMUNICATIONS DRIVE</AddressLine>
                    <AddressLine>DALLAS TX 75211</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="1-214-331-0522" Phone="1-214-331-0505" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>DALLAS TX</Text>
                </LocationDescription>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="true" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="false" />
                    <DryClean Ind="false" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="true" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="true" />
                    <HighSpeedInternet Ind="false" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="true" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="false" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="false" />
                    <Pets Ind="false" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="false" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="true" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="true" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="134.00" Min="114.00" />
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="003">
            <BasicPropertyInfo AreaID="002NE" ChainCode="EA" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0058393" HotelName="HOMESTEAD DFW-AIRPORT-NORTH" Latitude="32.915920" Longitude="-97.014040">
                <Address>
                    <AddressLine>7825 HEATHROW DRIVE</AddressLine>
                    <AddressLine>IRVING TX 75063</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="972-929-4801" Phone="972-929-3333" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>2MI N OF DFW AIRPOR</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>2 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="false" />
                    <BusinessCenter Ind="false" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="false" />
                    <DryClean Ind="true" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="false" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="false" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="false" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="false" />
                    <Pets Ind="true" />
                    <Pool Ind="false" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="false" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="false" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="94.99" Min="42.74" />
                <RoomRate GuaranteeSurchargeRequired="G" RateLevelCode="RAC" XPM_GuaranteeRequired="6">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="06" Option="P" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                    <RateTypeCode>A1Q</RateTypeCode>
                </RoomRate>
                <RoomRate GuaranteeSurchargeRequired="G" RateLevelCode="COR">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="06" Option="P" />
                    </AdditionalInfo>
                    <RateTypeCode>A1Q</RateTypeCode>
                </RoomRate>
                <RoomRate GuaranteeSurchargeRequired="G" RateLevelCode="RAD">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="06" Option="P" />
                    </AdditionalInfo>
                    <HotelRateCode>RAD</HotelRateCode>
                    <RateTypeCode>N1Q</RateTypeCode>
                    <RateTypeCode>A1Q</RateTypeCode>
                    <RateTypeCode>NSQ</RateTypeCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="004">
            <BasicPropertyInfo AreaID="002NE" ChainCode="HG" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0043350" HotelName="HOMEWOOD STES IRVING DFW ARPT" Latitude="32.918050" Longitude="-97.017200">
                <Address>
                    <AddressLine>7800 DULLES DR</AddressLine>
                    <AddressLine>IRVING TX 75063</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="1-972-929-2251" Phone="1-972-929-2202" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>AIRPORT FROM APT N</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>3 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="true" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="false" />
                    <DryClean Ind="true" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="true" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="true" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="false" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="false" />
                    <Pets Ind="false" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="false" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="true" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="159.00" Min="99.00" />
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="005">
            <BasicPropertyInfo AreaID="003NW" ChainCode="HI" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0051645" HotelName="HOLIDAY INN EX STES DFW" Latitude="32.921962" Longitude="-97.080023">
                <Address>
                    <AddressLine>309 STATE HWY 114 WEST</AddressLine>
                    <AddressLine>GRAPEVINE TX 76051</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="817-442-5960" Phone="817-442-5919" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>GRAPEVINE</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>2 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="true" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="false" />
                    <DryClean Ind="false" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="true" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="false" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="false" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="true" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="true" />
                    <Pets Ind="false" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="false" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="false" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="129.99" Min="92.00" />
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="006">
            <BasicPropertyInfo AreaID="013SE" ChainCode="ES" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DAL" HotelCode="0009867" HotelName="EMBASSY SUITES DALLAS MARKET" Latitude="32.808436" Longitude="-96.844890">
                <Address>
                    <AddressLine>2727 STEMMONS FREEWAY</AddressLine>
                    <AddressLine>DALLAS TX 75207</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="1-214-631-3025" Phone="1-214-630-5332" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>MARKET CENTER</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>3 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="true" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="true" />
                    <Dining Ind="true" />
                    <DryClean Ind="true" />
                    <EcoCertified Ind="true" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="true" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="true" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="true" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="true" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="false" />
                    <Pets Ind="false" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="true" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="true" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="236.52" Min="129.00" />
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="007">
            <BasicPropertyInfo AreaID="005E" ChainCode="EA" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0042006" HotelName="HOMESTEAD DALLAS-LAS COLINAS" Latitude="32.881352" Longitude="-96.961089">
                <Address>
                    <AddressLine>5315 CARNABY STREET</AddressLine>
                    <AddressLine>IRVING TX 75038</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="972-756-0553" Phone="972-756-0458" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>NW BUSINESS DISTRIC</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>2 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="false" />
                    <BusinessCenter Ind="false" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="false" />
                    <Dining Ind="false" />
                    <DryClean Ind="false" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="false" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="false" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="false" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="false" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="false" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="false" />
                    <OutdoorPool Ind="false" />
                    <Pets Ind="true" />
                    <Pool Ind="false" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="false" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="false" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RateRange CurrencyCode="USD" Max="114.99" Min="49.39" />
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
        <AvailabilityOption RPH="008">
            <BasicPropertyInfo AreaID="009S" ChainCode="WG" Distance="M" GEO_ConfidenceLevel="1" HotelCityCode="DFW" HotelCode="0016395" HotelName="WINGATE BY WYNDHAM ARLINGTON" Latitude="32.762990" Longitude="-97.066360">
                <Address>
                    <AddressLine>1024 BROOKHOLLOW PLAZA DRIVE</AddressLine>
                    <AddressLine>ARLINGTON TX 76006</AddressLine>
                </Address>
                <ContactNumbers>
                    <ContactNumber Fax="1-817-6409922" Phone="1-817-6408686" />
                </ContactNumbers>
                <DirectConnect>
                    <Alt_Avail Ind="false" />
                    <DC_AvailParticipant Ind="true" />
                    <DC_SellParticipant Ind="true" />
                    <RatesExceedMax Ind="false" />
                    <UnAvail Ind="false" />
                </DirectConnect>
                <LocationDescription Code="G">
                    <Text>I 30 EX 29</Text>
                </LocationDescription>
                <Property Rating="NTM">
                    <Text>3 CROWN</Text>
                </Property>
                <PropertyOptionInfo>
                    <ADA_Accessible Ind="false" />
                    <AdultsOnly Ind="false" />
                    <BeachFront Ind="false" />
                    <Breakfast Ind="true" />
                    <BusinessCenter Ind="true" />
                    <BusinessReady Ind="false" />
                    <Conventions Ind="false" />
                    <Dataport Ind="false" />
                    <Dining Ind="true" />
                    <DryClean Ind="true" />
                    <EcoCertified Ind="false" />
                    <ExecutiveFloors Ind="false" />
                    <FitnessCenter Ind="true" />
                    <FreeLocalCalls Ind="false" />
                    <FreeParking Ind="true" />
                    <FreeShuttle Ind="false" />
                    <FreeWifiInMeetingRooms Ind="false" />
                    <FreeWifiInPublicSpaces Ind="false" />
                    <FreeWifiInRooms Ind="false" />
                    <FullServiceSpa Ind="false" />
                    <GameFacilities Ind="false" />
                    <Golf Ind="false" />
                    <HighSpeedInternet Ind="true" />
                    <HypoallergenicRooms Ind="false" />
                    <IndoorPool Ind="false" />
                    <InRoomCoffeeTea Ind="false" />
                    <InRoomMiniBar Ind="false" />
                    <InRoomRefrigerator Ind="false" />
                    <InRoomSafe Ind="false" />
                    <InteriorDoorways Ind="false" />
                    <Jacuzzi Ind="false" />
                    <KidsFacilities Ind="false" />
                    <KitchenFacilities Ind="false" />
                    <MealService Ind="false" />
                    <MeetingFacilities Ind="true" />
                    <NoAdultTV Ind="false" />
                    <NonSmoking Ind="true" />
                    <OutdoorPool Ind="true" />
                    <Pets Ind="false" />
                    <Pool Ind="true" />
                    <PublicTransportationAdjacent Ind="false" />
                    <RateAssured Ind="true" />
                    <Recreation Ind="false" />
                    <RestrictedRoomAccess Ind="false" />
                    <RoomService Ind="false" />
                    <RoomService24Hours Ind="false" />
                    <RoomsWithBalcony Ind="false" />
                    <SkiInOutProperty Ind="false" />
                    <SmokeFree Ind="false" />
                    <SmokingRoomsAvail Ind="false" />
                    <Tennis Ind="false" />
                    <WaterPurificationSystem Ind="false" />
                    <Wheelchair Ind="true" />
                </PropertyOptionInfo>
                <RoomRate RateLevelCode="RAC">
                    <AdditionalInfo>
                        <CancelPolicy Numeric="00" />
                    </AdditionalInfo>
                    <HotelRateCode>RAC</HotelRateCode>
                </RoomRate>
                <SpecialOffers Ind="false" />
            </BasicPropertyInfo>
        </AvailabilityOption>
    </AvailabilityOptions>
</OTA_HotelAvailRS>';

      $xml = simplexml_load_string($res);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);
      //echo '<pre>';
      return $array['AvailabilityOptions']['AvailabilityOption'];
      //echo '</pre>';
    }


    public function getHotelDetail($id) {
      $res = '<GetHotelContentRS xmlns:ns9="http://services.sabre.com/hotel/content/v1">
    <ns6:ApplicationResults xmlns:ns6="http://services.sabre.com/STL_Payload/v02_02" status="Complete">
        <ns6:Success timeStamp="2016-07-26T14:26:44.566-05:00" />
    </ns6:ApplicationResults>
    <HotelContentInfos>
        <HotelContentInfo>
            <HotelInfo HotelCode="1100" HotelName="InterContinental Budapest" ChainCode="IC" ChainName="Intercontinental Hotels" MarketerCode="INTERCON" MarketerName="Intercontinental" Status="Active" Latitude="47.497626" Longitude="19.047647" CountryCode="HU" />
            <HotelDescriptiveInfo>
                <PropertyInfo Floors="9" Rooms="380">
                    <PropertyTypeInfo>
                        <PropertyType>Convention Center</PropertyType>
                        <PropertyType/>
                    </PropertyTypeInfo>
                    <PropertyFeatures>
                        <PropertyFeature>ACCESS</PropertyFeature>
                    </PropertyFeatures>
                    <Policies>
                        <Policy>
                            <Text Type="CheckOut">1200</Text>
                        </Policy>
                        <Policy>
                            <Text Type="CheckIn">1400</Text>
                        </Policy>
                    </Policies>
                    <PropertyQualityInfo>
                        <PropertyQuality>Luxury</PropertyQuality>
                    </PropertyQualityInfo>
                    <PropertyTaxes>
                        <PropertyTax Info="TAX2">3.00 PCT</PropertyTax>
                        <PropertyTax Info="TAX1">20.00 PCT</PropertyTax>
                    </PropertyTaxes>
                    <RoomTraits>
                        <RoomTrait Info="NOSMK">Non-smoking rooms available</RoomTrait>
                    </RoomTraits>
                    <RequestableAmenities>
                        <Amenity Info="ROLLAWAY">Rollaway adult</Amenity>
                        <Amenity Info="CRIB">Crib charge</Amenity>
                    </RequestableAmenities>
                </PropertyInfo>
                <LocationInfo Latitude="47.497626" Longitude="19.047647">
                    <Address>
                        <AddressLine1>APACZAI CSERE JANOS U. 12-14</AddressLine1>
                        <AddressLine2>BUDAPEST HU 1052</AddressLine2>
                        <CityName>Budapest</CityName>
                        <StateProv/>
                        <PostalCode>1052</PostalCode>
                        <CountryName Code="HU" />
                    </Address>
                    <Contact Phone="361-327-6333" Fax="361-327-6357" />
                </LocationInfo>
                <Amenities>
                    <Amenity Code="15" Description="Car Rental" AdditionalCharge="true" />
                    <Amenity Code="215" Description="Convention Center" AdditionalCharge="true" />
                    <Amenity Code="76" Description="Restaurant" AdditionalCharge="true" />
                    <Amenity Code="96" Description="Dry Cleaning" AdditionalCharge="true" />
                    <Amenity Code="34" Description="Executive Floor" AdditionalCharge="true" />
                    <Amenity Code="24" Description="Conference facilities" AdditionalCharge="true" />
                    <Amenity Code="68" Description="Parking" AdditionalCharge="true" />
                    <Amenity Code="71" Description="Pool" AdditionalCharge="true" />
                    <Amenity Code="44" Description="Recreation facilities" AdditionalCharge="true" />
                </Amenities>
                <Descriptions>
                    <Description>
                        <Text Type="Dining">PROPERTY HAS 1 RESTAURANTS ON-SITEADDITIONAL MEAL PLAN DESCRIPTION - KIDS UNDER 6 EAT FREE FROM KIDS MENU WITH PAYING ADULT. BUFFET BREAKFAST AT EUR 28 PER PERSON.CORSO LOUNGETYPE OF RESTAURANT - BAR/LOUNGETYPE OF CUISINE - INTERNATIONALMEALS SERVED - BREAKFAST - LUNCH - DINNER - DESSERTRESTAURANT DESCRIPTION - IF IT IS JUST A COCKTAIL COFFEE OR OUR WELL-KNOWN PASTRIES THE GUEST DESIRES THEN THE NEW BAR AND CAFE WILL SATISFY THEIR THIRST AND SWEET TOOTHCORSO RESTAURANTTYPE OF RESTAURANT - FULL SERVICE RESTAURANTTYPE OF CUISINE - INTERNATIONALMEALS SERVED - BREAKFAST - BRUNCH/BUFFET - LUNCH - DINNER- DESSERTRESTAURANT DESCRIPTION - OUR NEWLY RENOVATED CORSO RESTAURANT HAS A NEW FRESH AND EXCITING ENVIRONMENT WHERE GUESTS CAN DINE CASUALLY WHILE WATCHING THE HOTELS AWARD-WINNING CHEFS CREATE THEIR MASTERPIECES IN THE DISPLAY COOKING ARENA. THE SLOGAN IS WHERE EAST MEETS WEST INDICATES A CROSSROAD OF EASTERN AND WESTERN INFLUENCES OF FINE FLAVORS WHICH WE CALL MODERN HUNGARIAN.</Text>
                    </Description>
                </Descriptions>
                <Airports>
                    <Airport AirportOrCityCode="BUD" DirectionId="W" DistanceFromHotel="12" UOM="MI" AirportOrCityDescription="Budapest" AirportOrCityCountryCode="HU" Primary="true" />
                </Airports>
                <AcceptedCreditCards>
                    <CreditCard Code="AX">AMERICAN EXPRESS</CreditCard>
                    <CreditCard Code="CA">MASTERCARD</CreditCard>
                    <CreditCard Code="DC">DINERS CLUB CARD</CreditCard>
                    <CreditCard Code="DS">DISCOVER CARD</CreditCard>
                    <CreditCard Code="JC">JCB CREDIT CARD</CreditCard>
                    <CreditCard Code="VI">VISA</CreditCard>
                </AcceptedCreditCards>
            </HotelDescriptiveInfo>
            <HotelMediaInfo>
                <ImageItems>
                    <ImageItem Id="231019" Ordinal="0" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/mxlkkksgatwtqwautoiu.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="20">
                            <Description>
                                <Text Language="en">Miscellaneous</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231023" Ordinal="2" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/ssahc2c8jahovvkvnxzz.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="21">
                            <Description>
                                <Text Language="en">Guest room amenity</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231027" Ordinal="3" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/n1uroxpvecwymzfjbqc6.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="21">
                            <Description>
                                <Text Language="en">Guest room amenity</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231031" Ordinal="4" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/sp6qjryes4odpquvpuii.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="21">
                            <Description>
                                <Text Language="en">Guest room amenity</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231035" Ordinal="5" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/q7rcdx9kvff2wjeswryv.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="21">
                            <Description>
                                <Text Language="en">Guest room amenity</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231039" Ordinal="6" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/zpqbbat7reogvar9iug2.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="14">
                            <Description>
                                <Text Language="en">Recreational facility</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231043" Ordinal="7" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/shv1ut3e48cdunffr431.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="1">
                            <Description>
                                <Text Language="en">Exterior view</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="230999" Ordinal="8" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/tnkeukoi2jor4neo4plv.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="1">
                            <Description>
                                <Text Language="en">Exterior view</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231083" Ordinal="9" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/hfwwbfequwoe5ahcde5b.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="2">
                            <Description>
                                <Text Language="en">Lobby view</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                    <ImageItem Id="231087" Ordinal="10" Format="JPG" LastModifedDate="2015-11-19-06:00">
                        <Images>
                            <Image Url="http://res.cloudinary.com/srinim/image/upload/t_vcmp_thumb/hotel/i/1100/xo1eid0dm8q6bh87dcsu.jpg" Type="THUMBNAIL" Height="100" Width="150" />
                        </Images>
                        <Category CategoryCode="22">
                            <Description>
                                <Text Language="en">Property amenity</Text>
                            </Description>
                        </Category>
                        <AdditionalInfo/>
                    </ImageItem>
                </ImageItems>
            </HotelMediaInfo>
        </HotelContentInfo>
    </HotelContentInfos>
</GetHotelContentRS>';

      $xml = simplexml_load_string($res);
      $json = json_encode($xml);
      $array = json_decode($json,TRUE);
      //echo '<pre>';
      return $array['HotelContentInfos']['HotelContentInfo'];
      //echo '</pre>';
    }

    function bookFlights($origin, $destination, $departuredate, $returndate, $flightNumber, $flightCode, $ResBookDesigCode){
      //POST https://api.sabre.havail.com/v2.0.0/passenger/records?mode=create
      $url = "/v2.0.0/passenger/records?mode=create";

      $json = '{
    "CreatePassengerNameRecordRQ": {
        "version": "2.0.0",
        "targetCity": "G7HE",
        "TravelItineraryAddInfo": {
            "AgencyInfo": {
                "Address": {
                    "AddressLine": "SABRE TRAVEL",
                    "CityName": "SOUTHLAKE",
                    "CountryCode": "US",
                    "PostalCode": "76092",
                    "StateCountyProv": {
                        "StateCode": "TX"
                    },
                    "StreetNmbr": "3150 SABRE DRIVE",
                    "VendorPrefs": {
                        "Airline": {
                            "Hosted": true
                        }
                    }
                },
                "Ticketing": {
                    "TicketType": "7TAW"
                }
            },
            "CustomerInfo": {
                "ContactNumbers": {
                    "ContactNumber": [
                        {
                            "NameNumber": "1.1",
                            "Phone": "817-555-1212",
                            "PhoneUseType": "H"
                        },
                        {
                            "NameNumber": "2.1",
                            "Phone": "928-666-2323",
                            "PhoneUseType": "B"
                        }
                    ]
                },
                "PersonName": [
                    {
                        "NameNumber": "1.1",
                        "NameReference": "ABC123",
                        "PassengerType": "ADT",
                        "GivenName": "SERVICES",
                        "Surname": "PLATFORM"
                    },
                    {
                        "NameNumber": "2.1",
                        "NameReference": "DEF456",
                        "PassengerType": "ADR",
                        "GivenName": "SERVICES",
                        "Surname": "PLATOFRM"
                    }
                ]
            }
        },
        "AirBook": {
            "OriginDestinationInformation": {
                "FlightSegment": [
                    {
                        "ArrivalDateTime": "'.$departuredate.'",
                        "DepartureDateTime": "'.$returndate.'",
                        "FlightNumber": "'.$flightNumber.'",
                        "NumberInParty": "2",
                        "ResBookDesigCode": "'.$ResBookDesigCode.'",
                        "Status": "NN",
                        "DestinationLocation": {
                            "LocationCode": "'.$destination.'"
                        },
                        "MarketingAirline": {
                            "Code": "'.$flightCode.'",
                            "FlightNumber": "'.$flightNumber.'"
                        },
                        "MarriageGrp": "O",
                        "OriginLocation": {
                            "LocationCode": "'.$origin.'"
                        }
                    }
                ]
            }
        },
        "AirPrice": {
            "PriceRequestInformation": {
                "Retain": true,
                "OptionalQualifiers": {
                    "FOP_Qualifiers": {
                        "BasicFOP": {
                            "Type": "CA"
                        }
                    },
                    "PricingQualifiers": {
                        "PassengerType": [
                            {
                                "Code": "ADT",
                                "Quantity": "1"
                            },
                            {
                                "Code": "ADR",
                                "Quantity": "1"
                            }
                        ]
                    }
                }
            }
        },
        "SpecialReqDetails": {
            "AddRemark": {
                "RemarkInfo": {
                    "FOP_Remark": {
                        "Type": "CHECK"
                    }
                }
            },
            "SpecialService": {
                "SpecialServiceInfo": {
                    "SecureFlight": [
                        {
                            "SegmentNumber": "A",
                            "PersonName": {
                                "DateOfBirth": "2001-01-01",
                                "Gender": "M",
                                "NameNumber": "1.1",
                                "GivenName": "NAME",
                                "Surname": "SURNAME"
                            },
                            "VendorPrefs": {
                                "Airline": {
                                    "Hosted": true
                                }
                            }
                        },
                        {
                            "SegmentNumber": "A",
                            "PersonName": {
                                "DateOfBirth": "2005-01-01",
                                "Gender": "F",
                                "NameNumber": "2.1",
                                "GivenName": "SECONDNAME",
                                "Surname": "SECONDSURNAME"
                            },
                            "VendorPrefs": {
                                "Airline": {
                                    "Hosted": true
                                }
                            }
                        }
                    ],
                    "Service": [
                        {
                            "SSR_Code": "OTHS",
                            "Text": "CC SERVICES PLATFORM"
                        },
                        {
                            "SSR_Code": "OTHS",
                            "Text": "CC SERVICES PLATFORM"
                        }
                    ]
                }
            }
        },
        "PostProcessing": {
            "RedisplayReservation": true,
            "EndTransaction": {
                "Source": {
                    "ReceivedFrom": "SP TEST"
                }
            }
        }
    }
}';


      $result = $this->executeGetCall($url, $paras);
      if(0){
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        echo '<hr />';
      }
      if(isset($result['FareInfo'][0]['Links'][0]['href'])){
        //$url = $result['FareInfo'][0]['Links'][0]['href'];
        //$result = $this->executeGetCall($url, null);
        if(0){
          echo '<pre>';
          print_r($result);
          echo '</pre>';
          echo '<hr />';
        }
        //$result = $this->executePostCall("/v1.8.6/shop/flights?mode=live", //$this->getRequest($origin, $destination, $departuredate));
        if(0){
          echo '<pre>';
          print_r($result);
          echo '</pre>';
        }
      }

      return $result;
    }
  }
