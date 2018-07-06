<?php
require_once dirname(__FILE__) . '/../workflow/Activity.php';
require_once dirname(__FILE__) . '/../rest/RestClient.php';

class utilityActivity implements Activity {

    private $seasrch_key;
    private $category;

    public function __construct($seasrch_key, $category) {
        $this->seasrch_key= $seasrch_key;
        $this->category   = $category;
    }

    public function run(&$sharedContext) {
        $sharedContext->addResult("seasrch_key", $this->seasrch_key);
        $sharedContext->addResult("category", $this->category);

        $call = new RestClient();
        $result = $call->executeGetCall("/v1/lists/utilities/geoservices/autocomplete", $this->getRequest($this->seasrch_key, $this->category));

        return $result;
    }

    private function getRequest($seasrch_key, $category) {
        $request = array(
                "query" => $seasrch_key,
                "category" => $category,
                "limit"   => 10
        );
        return $request;
    }
}
