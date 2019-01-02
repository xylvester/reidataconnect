(function() {

	var states=[],counties=[],cities=[],owner_types=[],owner_types2=[];

	function numberWithCommas(number) {
	    var parts = number.toString().split(".");
	    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	    return parts.join(".");
	}

	function toTitleCase(str) {
        return str.replace(
            /\w\S*/g,
            function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }

	function init() {
		getStatistics();
		getCounties();
		getOwnerTypes();
		getOwnerTypes2();

		$('#selectCounty').on('change', function() {
			$('#selectCity').empty();
			getCities(this.value);
		});

		$('#doSearchProperties').click( function() {
		     $.ajax({
		       method: "POST",
		       url: window.location.href + '/index.php/wp-json/rdc-api/v1/search-properties',
		       data : $('form#interactive-search').serialize(),
		       contentType: 'applcation/json',
		       beforeSend: function (xhr){
		           console.log('beforeSend');
		        	if ( $.fn.DataTable.isDataTable( '#data-preview' ) ) {
						$('#data-preview').DataTable().clear();
						$('#data-preview').DataTable().destroy();
					}
		       },
		       success: function(response){
		           console.log("success" + response);
		           $('#data-preview').DataTable( {
					    data: response,
					    columns: [
					        { title: 'Report Date', data: 'report_date' },
					        { title: 'Last Sale Date', data: 'last_sale_dt' },
					        { title: 'House Value', data: 'house_value' },
					        { title: 'Land Value', data: 'land_value' },
					        { title: 'Total Value', data: 'tot_value' },
					        { title: 'Sq Ft', data: 'sq_ft' },
					        { title: 'Property Address', data: 'prpty_adrs' },
					        { title: 'Property City', data: 'prpty_city' },
					        { title: 'Property Street', data: 'prpty_st' },
					        { title: 'Property Zip', data: 'prpty_zip' },
					        { title: 'Legal Description', data: 'legal_desc' },
					        { title: 'Bedrooms', data: 'bedrooms' },
					        { title: 'Bathrooms', data: 'bathrooms' },
					        { title: 'Lot', data: 'lot' },
					        { title: 'Year Built', data: 'yr_built' },
					        { title: 'County', data: 'county' },
					        { title: 'Years Ownership', data: 'yrs_ownership' },
					        { title: 'Owner Name', data: 'owner_name' },
					        { title: 'Owner Type', data: 'owner_type' },
					        { title: 'Owner Type 2', data: 'owner_type2' },
					        { title: 'Co-party', data: 'co_party' },
					        { title: 'Owner Address', data: 'owner_address' },
					        { title: 'Owner Other Address', data: 'owner_other_adrs' },
					        { title: 'Owner City', data: 'owner_city' },
					        { title: 'Owner Street', data: 'owner_st' },
					        { title: 'Owner Zip', data: 'owner_zip' },
					        { title: 'Property Count', data: 'prpty_cnt' }
					    ]
					} );
		           $('#dataPreviewModal').modal('show');
		           $('#data-preview_wrapper').css("overflow", "auto");
		       },
		       fail: function (response){
		           console.log("fail" + response);
		       }
		    });
		});
	}

	function getStatistics() {

		var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/statistics";
	  	$.getJSON( url).done(function( data ) {
	      $.each( data, function( key, item ) {
	        switch(key) {
	        	case "absentee_owner_prpty_count":
	        		$("#absentee-owner-properties").empty();
	        		$( "#absentee-owner-properties" ).text( numberWithCommas(item) );
	        		break;
	        	case "market_count":
	        		$("#market-count").empty();
	        		$( "#market-count" ).text( numberWithCommas(item) );
	        		break;
	        	case "not_local_owners_count":
	        		$("#not-local-owners-count").empty();
	        		$( "#not-local-owners-count" ).text( numberWithCommas(item) );
	        		break;
	        	case "not_local_owners_count":
	        		$("#not-local-owners-count").empty();
	        		$( "#not-local-owners-count" ).text( numberWithCommas(item) );
	        		break;
	        	case "out_of_st_owners_pct":
	        		$("#out-of-st-owners-pct").empty();
	        		$( "#out-of-st-owners-pct" ).text( (100*item).toFixed(2) + "%" );
	        		break;
	        }
	      });
	    });
	}

	/*function getStates() {
		var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/states";
	  	$.getJSON( url).done(function( data ) {

	  		var $item = $("#selectState");
			$.each(data, function() {
				states.push({
					id: this.id,
					name: this.name
				});
			    $item.append($("<option />").val(this.id).text( toTitleCase(this.name) ));
			});

	    });
	}*/

	function getCounties() {

  		var $item = $("#selectCounty");

		var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/counties";
	  	$.getJSON( url).done(function( data ) {

			$.each(data, function() {
				counties.push({
					name: this.name
				});
			    $item.append($("<option />").val(this.name).text( toTitleCase(this.name) ));
			});

	    });

	}

	function getCities(county) {

  		var $item = $("#selectCity");
  		if( cities.some(function(o){return o && o["county"].toUpperCase() === county.toUpperCase()}) ) {

  			cities.forEach(function() {
				$item.append($("<option />").val(this.name).text(toTitleCase(this.name)));
			});

  		} else {

			var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/cities/" + county;
		  	$.getJSON( url).done(function( data ) {

				$.each(data, function() {
					cities.push({
						county: this.county,
						name: this.city
					});
				    $item.append($("<option />").val(this.name).text( toTitleCase(this.name) ));
				});

		    });
  		}
	}

	function getOwnerTypes() {

		var $item = $("#selectOwnerType");
		if( owner_types !== undefined && owner_types.length != 0 ) {

			owner_types.forEach(function() {
				$item.append($("<option />").val(this.name).text(this.name));
			});

		} else {

			var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/owner-types";
		  	$.getJSON( url).done(function( data ) {

				$.each(data, function() {
					owner_types.push({
						name: this.name
					});
				    $item.append($("<option />").val(this.name).text( toTitleCase(this.name) ));
				});

		    });
		}

	}

	function getOwnerTypes2() {

		var $item = $("#selectOwnerType2");
		if( owner_types2 !== undefined && owner_types2.length != 0 ) {

			owner_types2.forEach(function() {
				$item.append($("<option />").val(this.name).text( toTitleCase(this.name) ));
			});

		} else {

			var url = "http://35.237.174.43/reidataconnect/index.php/wp-json/rdc-api/v1/owner-types2";
		  	$.getJSON( url).done(function( data ) {

		  		var $item = $("#selectOwnerType2");
				$.each(data, function() {
					owner_types2.push({
						name: this.name
					});
				    $item.append($("<option />").val(this.name).text( toTitleCase(this.name) ));
				});

		    });
		}
	}

	init();
  
})();
