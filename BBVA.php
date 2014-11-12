<?php
/** 
* BBVA the easy way
* @author @gobiernofacil <howdy@gobiernofacil.com>
*/

class BBVA{
  /*
  * config data
  * -------------------------------------------------------------
  */

  // ENDPOINTS
  const MERCHANTS_CATS_ENDPOINT = "https://apis.bbvabancomer.com/datathon/info/merchants_categories";
  const ZIP_RANK_ENDPOINT       = "https://apis.bbvabancomer.com/datathon/info/zipcodes";
  const TILES_RANK_ENDPOINT     = "https://apis.bbvabancomer.com/datathon/info/tiles";

  // BASE_ENDPOINTS
  const BASE_TILE_ENDPOINT = "https://apis.bbvabancomer.com/datathon/tiles/%lat%/%lng%/";
  const BASE_ZIP_ENDPOINT  = "https://apis.bbvabancomer.com/datathon/zipcodes/%zipcode%/";

  // OPTION ENDPOINTS
  const BASIC_STATS           = "basic_stats";
  const CUSTOMER_ZIPCODES     = "customer_zipcodes";
  const AGE_DISTRIBUTION      = "age_distribution";
  const GENDER_DISTRIBUTION   = "gender_distribution";
  const PAYMENT_DISTRIBUTION  = "payment_distribution";
  const CATEGORY_DISTRIBUTION = "category_distribution";
  const CONSUMPTION_PATTERN   = "consumption_pattern";
  const CARDS_CUBE            = "cards_cube";
  const PAYMENTS_CUBE         = "payments_cube";

  // CREDENTIALS
  public $app_id;
  public $key;

  // MORE STUFF
  public $ch;

  // DEFAULT SETTINGS
  public $settings = [
    'page_size' => 10, 
    'date_min'  => '20140101', 
    'date_max'  => '20140331', 
    'group_by'  => 'month', 
    'by'        => 'incomes'
  ];

  /*
  * constructor
  * -------------------------------------------------------------
  */
  function __construct($app_id, $key){
    $this->app_id = $app_id;
    $this->key    = $key;
  }

  /**
  * base functions
  * -------------------------------------------------------------
  */
  public function get_categories(){
    return $this->make_conn(self::MERCHANTS_CATS_ENDPOINT);
  }

  public function top_zips(){
    return $this->make_conn(self::ZIP_RANK_ENDPOINT);
  }

  public function top_tiles(){
    return $this->make_conn(self::TILES_RANK_ENDPOINT);
  }

  /**
  * tiles functions
  * -------------------------------------------------------------
  */
  public function basic_stats_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::BASIC_STATS, $lat, $lng, $query);
  }

  public function customer_zipcodes_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::CUSTOMER_ZIPCODES, $lat, $lng, $query);
  }

  public function age_distribution_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::AGE_DISTRIBUTION, $lat, $lng, $query);
  }

  public function gender_distribution_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::GENDER_DISTRIBUTION, $lat, $lng, $query);
  }

  public function payment_distribution_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::PAYMENT_DISTRIBUTION, $lat, $lng, $query);
  }

  public function category_distribution_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::CATEGORY_DISTRIBUTION, $lat, $lng, $query);
  }

  public function consumption_pattern_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::CONSUMPTION_PATTERN, $lat, $lng, $query);
  }

  public function cards_cube_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::CARDS_CUBE, $lat, $lng, $query);
  }

  public function payments_cube_by_tile($lat, $lng, $query = []){
    return $this->get_tile_data(self::PAYMENTS_CUBE, $lat, $lng, $query);
  }

  /**
  * zipcode functions
  * -------------------------------------------------------------
  */
  public function basic_stats_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::BASIC_STATS, $zipcode, $query);
  }

  public function customer_zipcodes_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::CUSTOMER_ZIPCODES, $zipcode, $query);
  }

  public function age_distribution_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::AGE_DISTRIBUTION, $zipcode, $query);
  }

  public function gender_distribution_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::GENDER_DISTRIBUTION, $zipcode, $query);
  }

  public function payment_distribution_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::PAYMENT_DISTRIBUTION, $zipcode, $query);
  }

  public function category_distribution_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::CATEGORY_DISTRIBUTION, $zipcode, $query);
  }

  public function consumption_pattern_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::CONSUMPTION_PATTERN, $zipcode, $query);
  }

  public function cards_cube_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::CARDS_CUBE, $zipcode, $query);
  }

  public function payments_cube_by_zipcode($zipcode, $query = []){
    return $this->get_zip_data(self::PAYMENTS_CUBE, $zipcode, $query);
  }

  /*
  * helpers
  * -------------------------------------------------------------
  */
  private function get_tile_data($endpoint, $lat, $lng, $query){
    $url = strtr(self::BASE_TILE_ENDPOINT . $endpoint, ["%lat%" => $lat, "%lng%" => $lng]);
    return $this->make_conn($url, array_merge($this->settings, $query));
  }

  private function get_zip_data($endpoint, $zipcode, $query){
    $url = str_replace("%zipcode%", $zipcode, self::BASE_ZIP_ENDPOINT . $endpoint);
    return $this->make_conn($url, array_merge($this->settings, $query));
  }

  private function make_conn($url, $params = []){
    $this->ch = curl_init();
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->ch, CURLOPT_USERPWD, "{$this->app_id}:{$this->key}");
    curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // set the params, if avaliable
    $url = empty($params) ? $url : $url . '?' . @http_build_query($params);
    curl_setopt($this->ch, CURLOPT_URL, $url);

    // finish the thing
    $response = curl_exec($this->ch);
    curl_close($this->ch);
    return $response;
  }
}