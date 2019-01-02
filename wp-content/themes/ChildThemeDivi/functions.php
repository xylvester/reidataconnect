<?php
/*
=== Load parent Styles ===
*/
function dc_enqueue_styles() {
	wp_enqueue_style( 'divi-parent', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'divi-parent' ) );
}
add_action( 'wp_enqueue_scripts', 'dc_enqueue_styles' );

function get_statistics_data() {
	global $wpdb;
	$query = $wpdb->prepare( "SELECT absentee_owner_prpty_count, market_count, not_local_owners_count, out_of_st_owners_pct FROM rdc_v_statistics" );
	$stat_obj =  $wpdb->get_row( $query );
 
  return wp_send_json( $stat_obj );
}


function get_states() {
	global $wpdb;
	$query = $wpdb->prepare( "SELECT * FROM states" );
	$states_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($states_row as $row) {
		$obj = array(
			"id"	=> $row->id,
			"abbr"	=> $row->abbr,
			"name"	=> $row->name,
		);
		array_push($results,$obj);
	}
 
  return wp_send_json( $results );
}

function get_counties() {
	global $wpdb;
	$sql = "SELECT * FROM rdc_v_counties";
	$args  = array();

	$query = $wpdb->prepare( $sql, $args );
	$states_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($states_row as $row) {
		$obj = array(
			"name"		=> $row->county,
		);
		array_push($results,$obj);
	}
 
  return wp_send_json( $results );
}

function get_cities($data) {
	global $wpdb;
	$sql = "SELECT * FROM rdc_v_cities";
	$args  = array();

	if( isset( $data[ 'county' ] ) ) {
		$sql .= " WHERE upper(county) = upper(%s)";
		$args[] = $data[ 'county' ];
	}

	$query = $wpdb->prepare( $sql, $args );
	$states_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($states_row as $row) {
		$obj = array(
			"county"		=> $row->county,
			"name"		=> $row->city,
		);
		array_push($results,$obj);
	}
 
  return wp_send_json( $results );
}

function get_owner_types() {
	global $wpdb;
	$sql = "SELECT * FROM rdc_v_owner_types";

	$query = $wpdb->prepare( $sql );
	$states_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($states_row as $row) {
		$obj = array(
			"name"		=> $row->owner_type,
		);
		array_push($results,$obj);
	}
 
  return wp_send_json( $results );
}

function get_owner_types2() {
	global $wpdb;
	$sql = "SELECT * FROM rdc_v_owner_types2";

	$query = $wpdb->prepare( $sql );
	$states_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($states_row as $row) {
		$obj = array(
			"name"		=> $row->owner_type2,
		);
		array_push($results,$obj);
	}
 
  return wp_send_json( $results );
}

function do_search_properties($request) {
	global $wpdb;
	$query_string = $request->get_body();
	parse_str($query_string, $parameters);
	$sql = "SELECT * FROM rdc_properties WHERE";
	$args  = array();

	if($parameters['selectCounty'] !== "") {
		$sql .= " upper(county) = upper(%s)";
		$args[] = $parameters[ 'selectCounty' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['selectCity'] !== "") {
		$sql .= "  upper(prpty_city) = upper(%s)";
		$args[] = $parameters[ 'selectCity' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['selectOwnerType'] !== "") {
		$sql .= "  upper(owner_type) = upper(%s)";
		$args[] = $parameters[ 'selectOwnerType' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['selectOwnerType2'] !== "") {
		$sql .= "  upper(owner_type2) = upper(%s)";
		$args[] = $parameters[ 'selectOwnerType2' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['minBedrooms'] !== "") {
		$sql .= "  bedrooms >= CAST(%s AS UNSIGNED)";
		$args[] = $parameters[ 'minBedrooms' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['maxBedrooms'] !== "") {
		$sql .= "  bedrooms <= CAST(%s AS UNSIGNED)";
		$args[] = $parameters[ 'maxBedrooms' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['minBathrooms'] !== "") {
		$sql .= "  bathrooms >= CAST(%s AS UNSIGNED)";
		$args[] = $parameters[ 'minBathrooms' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['maxBathrooms'] !== "") {
		$sql .= "  bathrooms <= CAST(%s AS UNSIGNED)";
		$args[] = $parameters[ 'maxBathrooms' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['minSqFeet'] !== "") {
		$sql .= "  sq_ft >= CAST(%s AS DECIMAL(10,5))";
		$args[] = $parameters[ 'minSqFeet' ];
	} else {
		$sql .= " 1 = 1";
	}
	$sql .= " AND";
	if($parameters['maxSqFeet'] !== "") {
		$sql .= "  sq_ft <= CAST(%s AS DECIMAL(10,5))";
		$args[] = $parameters[ 'maxSqFeet' ];
	} else {
		$sql .= " 1 = 1";
	}

	$sql .= ";";

	$query = $wpdb->prepare( $sql, $args );
	$properties_row =  $wpdb->get_results( $query );
	$results = array();
	foreach ($properties_row as $row) {
		array_push($results,$row);
	}
 
 	return wp_send_json( $results );
}


add_action( 'rest_api_init', function () {
	register_rest_route( 'rdc-api/v1', '/statistics', array(
		'methods' => 'GET',
		'callback' => 'get_statistics_data',
	) );
	register_rest_route( 'rdc-api/v1', '/states', array(
		'methods' => 'GET',
		'callback' => 'get_states',
	) );
	register_rest_route( 'rdc-api/v1', '/counties', array(
		'methods' => 'GET',
		'callback' => 'get_counties',
	) );
	register_rest_route( 'rdc-api/v1', '/counties/(?P<state_id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'get_counties',
		'args' => array(
            'state_id' => array( 
                'validate_callback' => function( $param, $request, $key ) {
                    return is_numeric( $param );
                }
            ),
        ),
	) );
	register_rest_route( 'rdc-api/v1', '/cities', array(
		'methods' => 'GET',
		'callback' => 'get_cities',
	) );
	register_rest_route( 'rdc-api/v1', '/cities/(?P<county>\w+)', array(
		'methods' => 'GET',
		'callback' => 'get_cities',
		'args' => array(
            'county_id' => array( 
                'validate_callback' => function( $param, $request, $key ) {
                    return is_string( $param );
                }
            ),
        ),
	) );
	register_rest_route( 'rdc-api/v1', '/owner-types', array(
		'methods' => 'GET',
		'callback' => 'get_owner_types',
	) );
	register_rest_route( 'rdc-api/v1', '/owner-types2', array(
		'methods' => 'GET',
		'callback' => 'get_owner_types2',
	) );

	register_rest_route( 'rdc-api/v1', '/search-properties', array(
		'methods' => 'POST',
		'callback' => 'do_search_properties',
	) );
} );