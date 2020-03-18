<?php
class ActivityBean extends MasterBean {
	public function post_validation() {
		$errors 		= array();
		$data 			= $this->get_request("data");
		$activity_no		= isset( $data["activity_no"] ) ? trim( $data["activity_no"] ) : "";
		$activity_date		= isset( $data["activity_date"] ) ? trim( $data["activity_date"] ) : "";
                $place                  = isset( $data["place"] ) ? trim( $data["place"] ) : "";
                $division               = $this->get_request("division");                
                $subject_details        = isset( $data["subject_details"] ) ? trim( $data["subject_details"] ) : "";
                $remarks                = isset( $data["remarks"] ) ? trim( $data["remarks"] ) : "";        
		$error_counter 	= 0;
		
		if( $activity_no == "" ) {
			$this->error_messages[ "activity_no_error" ] = "<div class=\"errors\">$featured_image Activity no is required.</div>";
			$error_counter++;
		}
		if( ! $activity_date ) {
			$this->error_messages[ "activity_date_error" ] = "<div class=\"errors\">Activity date is empty.</div>";
			$error_counter++;
		}
                if( ! $place ) {
			$this->error_messages[ "place_error" ] = "<div class=\"errors\">Place is empty.</div>";
			$error_counter++;
		}
                if( ! $division ) {
			$this->error_messages[ "division_error" ] = "<div class=\"errors\">Division is empty.</div>";
			$error_counter++;
		}
                if( ! $subject_details ) {
			$this->error_messages[ "subject_details_error" ] = "<div class=\"errors\">Subject details is empty.</div>";
			$error_counter++;
		}
                if( ! $remarks ) {
			$this->error_messages[ "remarks_error" ] = "<div class=\"errors\">Remarks is empty.</div>";
			$error_counter++;
		}
            
		
		return ( $error_counter ) ? FALSE : TRUE;
	}
        
        public function incident_validation() {
		$errors 		= array();
		$data 			= $this->get_request("data");
		$incident_no		= isset( $data["incident_no"] ) ? trim( $data["incident_no"] ) : "";
		$date_of_incident	= isset( $data["date_of_incident"] ) ? trim( $data["date_of_incident"] ) : "";
                $time_of_incident	= isset( $data["time_of_incident"] ) ? trim( $data["time_of_incident"] ) : "";
                $incident_category_id	= isset( $data["incident_category_id"] ) ? trim( $data["incident_category_id"] ) : "";
               // $reportable             = isset( $data["reportable"] ) ? trim( $data["reportable"] ) : "";
               // $investigation_req      = isset( $data["investigation_req"] ) ? trim( $data["investigation_req"] ) : "";                
               // $investigation_done     = isset( $data["investigation_done"] ) ? trim( $data["investigation_done"] ) : "";
                $remarks                = isset( $data["remarks"] ) ? trim( $data["remarks"] ) : "";                
		$error_counter 	= 0;
		
		if( $incident_no == "" ) {
			$this->error_messages[ "incident_no_error" ] = "<div class=\"errors\">Incident no is required.</div>";
			$error_counter++;
		}
		if( ! $date_of_incident ) {
			$this->error_messages[ "date_of_incident_error" ] = "<div class=\"errors\">Incident date is empty.</div>";
			$error_counter++;
		}
                if( ! $time_of_incident ) {
			$this->error_messages[ "time_of_incident_error" ] = "<div class=\"errors\">Incident time is empty.</div>";
			$error_counter++;
		}
                if( ! $incident_category_id ) {
			$this->error_messages[ "incident_category_id_error" ] = "<div class=\"errors\">Incident category is empty.</div>";
			$error_counter++;
		}
              /*  if( ! $reportable ) {
			$this->error_messages[ "reportable_error" ] = "<div class=\"errors\">Reportable is empty.</div>";
			$error_counter++;
		}
                if( ! $investigation_req ) {
			$this->error_messages[ "investigation_req_error" ] = "<div class=\"errors\">Investigation required is empty.</div>";
			$error_counter++;
		}
                if( ! $investigation_done ) {
			$this->error_messages[ "investigation_done_error" ] = "<div class=\"errors\">Investion done is empty.</div>";
			$error_counter++;
		}*/
              
                if( ! $remarks ) {
			$this->error_messages[ "remarks_error" ] = "<div class=\"errors\">Remarks is empty.</div>";
			$error_counter++;
		}
		
		return ( $error_counter ) ? FALSE : TRUE;
	}
}

