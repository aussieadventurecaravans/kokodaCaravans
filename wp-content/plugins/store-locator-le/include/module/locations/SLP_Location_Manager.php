<?php

if ( ! class_exists( 'SLP_Location_Manager' ) ):


	/**
	 * Class SLP_Location_Manager
	 *
	 * Generic Location manager
	 *
	 * @package   StoreLocatorPlus\Location\Manager
	 * @author    Lance Cleveland <lance@charlestonsw.com>
	 * @copyright 2016 Charleston Software Associates, LLC
	 *
	 * @since     4.6
	 *
	 */
	class SLP_Location_Manager extends SLPlus_BaseClass_Object {

		/**
		 * Recalculate the initial distance for all locations.
		 */
		public function recalculate_initial_distance() {

			if ( ! $this->slplus->currentLocation->is_valid_lat( $this->slplus->smart_options->map_center_lat->value ) ) {
				return;
			}
			if ( ! $this->slplus->currentLocation->is_valid_lng( $this->slplus->smart_options->map_center_lng->value ) ) {
				return;
			}

			$location_table = $this->slplus->database->info['table'];
			$prepared_sql =	$this->slplus->database->db->prepare(
				"UPDATE {$location_table} SET sl_initial_distance =  ( %d * acos( cos( radians( %f ) ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians( %f ) ) + sin( radians( %f ) ) * sin( radians( sl_latitude ) ) ) )",
				( $this->slplus->smart_options->distance_unit->value === 'miles' ) ? SLPlus::earth_radius_mi : SLPlus::earth_radius_km,
				$this->slplus->smart_options->map_center_lat->value ,
				$this->slplus->smart_options->map_center_lng->value ,
				$this->slplus->smart_options->map_center_lat->value
				);

			$this->slplus->database->db->query( $prepared_sql );
		}
	}

endif;