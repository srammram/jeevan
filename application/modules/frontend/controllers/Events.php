<?php

/* * ************************
  Project Name	: POS
  Created on		: 19 Feb, 2016
  Last Modified 	: 19 Feb, 2016
  Description		: Page contains dashboard related functions.

 * ************************* */
defined('BASEPATH') or exit('No direct script access allowed');

class Events extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->module = "events";
        $this->module_label = "events";
        $this->module_labels = "events";
        $this->folder = "events/";
        $this->table = "sramcms_routeplan";
        $this->booking_table = "sramcms_event_users";
        $this->load->helper('emailtemplate');
    }

    /* this method used to show all dashboard all details... */

    public function index() {

        $data = array();
        $data['module_label'] = $this->module_label;
        $data['module_labels'] = $this->module_label;
        $data['module'] = $this->module;
		$data['meta_title']   =  get_meta_text('Jeevanacharya Tour Plan - Travel Program');
		$data['meta_keyword'] = get_meta_text('Jeevanacharya Tour Plan, Jeevanacharya Travel Program');
		$data['meta_content'] = get_meta_text('Tentative Tour Programme of Jeevanacharya, watch and get connected with his way of  life.');
        $this->loadBlocks();
        $data = array_merge($data, $this->view_data);
        $getplandetails = $this->Mydb->custom_query("SELECT * FROM $this->table WHERE is_active=1 AND is_visible = 1 ORDER BY start_date ASC");
        $data['records'] = $getplandetails;
        $this->layout->display_frontend($this->folder . '/events', $data);
    }

    /* Get event data */

    public function get_event_data() {
		
        $response = array();
        $event_url = frontend_url() . $this->module . '/get_event_booking/';
		 $today = date('Y-m-d');
        $result = $this->Mydb->get_all_records("id, trip_name AS title, CONCAT('" . $event_url . "',id) AS url,(UNIX_TIMESTAMP(start_date) * 1000) AS start, (UNIX_TIMESTAMP(end_date) * 1000) AS end", $this->table, array('is_active' => '1', 'is_delete' => '0'));
	  
       
        if (!empty($result)) {
            $response['success'] = 1;
            $response['result'] = $result;
        }
		
        echo json_encode($response);
		
		
    }

    /* Event booking popup modal window */

    public function get_event_booking($event_id = null, $booking_date = null) {

        if (!empty($event_id)) {

            $result = $this->Mydb->get_record('*', $this->table, array('id' => $event_id));
            $data = array();
            $data['module_label'] = $this->module_label;
            $data['module_labels'] = $this->module_label;
            $data['module'] = $this->module;
            $now = get_date_formart(current_date(), 'Y-m-d');
            if (($result['available_date'] < $booking_date || $result['available_date'] == $booking_date) && ($now < $booking_date)) {
                $data['show_booking_form'] = "yes";
            }
            $data['booking_date'] = $booking_date;
            $data['records'] = $result;
			
            $this->load->view($this->folder . '/events-booking', $data);
        }
    }

    /* Event booking form Register */

    public function user_booking_appointment() {
        form_check_ajax_request();
        if (!empty($_POST)) {
            $response = array();
            $event_id = $this->input->post('event_id');
            $check_exist = $this->Mydb->get_record('*', $this->booking_table, array('event_id' => $event_id, 'email' => $this->input->post('email'), 'booked_date' => $this->input->post('booked_date')));

            if (empty($check_exist)) {


                $purpose_of_appoint = json_encode($this->input->post('purpose'));
                $insert_data = array("name" => $this->input->post('firstname'),
                    "email" => $this->input->post('email'),
                    "phone_no" => $this->input->post('phonenumber'),
                    "event_id" => $event_id,
                    "booked_date" => $this->input->post('booked_date'),
                    "location_date" => $this->input->post('location_date'),
                    "location" => $this->input->post('location'),
                    "purpose_of_appointment" => $purpose_of_appoint ? $purpose_of_appoint : '',
                    "message" => $this->input->post('message'),
                    "created_on" => current_date(),
                    "created_ip" => get_ip(),
                    "created_by" => get_admin_id() ? get_admin_id() : '',
                    "is_active" => '1',
                );


                $result = $this->Mydb->insert($this->booking_table, $insert_data);
                /* Create notification for admin */
                if(get_all_users()){
                	foreach (get_all_users() as $admin_user){
                		$msg = "Appointment has been booked";
                		create_notification($admin_user['admin_id'], $module_type = "event", $msg, $from_user_id = get_admin_id(), $module_id = $event_id);
                	}
                }
                
                if (!empty($result)) {
                    $this->send_notification_email_to_admin($insert_data);
                    $this->send_acknowledgement_email_to_user($insert_data);

                    $response['status'] = "success";
                    $response['message'] = "Appointment has been booked successfully !";
                } else {
                    $response['status'] = "failure";
                    $response['message'] = "Appointment has not been booked successfully !";
                }
            } else {
                $response['status'] = "failure";
                $response['message'] = "Appointment has been already booked!";
            }
        }
        echo json_encode($response);
        exit;
    }

    /* Get group route table */

    public function getroute_by_map_id() {
        $map_id = $this->input->post('map_id');
        if ($map_id != '') {
            $getplandetails = $this->Mydb->custom_query("select * from $this->table where id='$map_id' and is_active =1 and is_visible = 1");
            $plan_details = explode('-', $getplandetails[0]['plan_details']);
            $response['startvalue'] = $plan_details[0];
            $response['endvalue'] = $plan_details[1];
            $destinations = $getplandetails[0]['destinations'];
            $explodedestinations = explode('|*|', $destinations);
            $response['destinations'] = array();
            $rows = array();
            foreach ($explodedestinations as $destination):
                $rows['location'] = $destination;
                array_push($response['destinations'], $rows);
            endforeach;
        } else {
            $curdate = date('Y-m-d');
            $getplandetails = $this->Mydb->custom_query("SELECT * FROM $this->table WHERE is_active =1 AND is_visible = 1 AND  '$curdate' between start_date and end_date  ORDER BY start_date ASC");
            if (empty($getplandetails)) {
                $getplandetails = $this->Mydb->custom_query("SELECT * from $this->table WHERE is_active=1 AND is_visible = 1  ORDER BY start_date ASC");
            }
            $plan_details = explode('-', $getplandetails[0]['plan_details']);
            $response['startvalue'] = $plan_details[0];
            $response['endvalue'] = $plan_details[1];
            $destinations = $getplandetails[0]['destinations'];
            $explodedestinations = explode('|*|', $destinations);
            $response['destinations'] = array();
            $rows = array();
            foreach ($explodedestinations as $destination):
                $rows['location'] = $destination;
                array_push($response['destinations'], $rows);
            endforeach;
        }

        echo json_encode($response);
    }

    public function send_acknowledgement_email_to_user($user_data) {
        $get_event_data = $this->Mydb->get_record("trip_name, destinations", $this->table, array("id" => $user_data['event_id']));
        $chk_arr = array('[NAME]', '[EVENT_NAME]', '[EVENT_INFO]');
        $rep_arr = array($user_data['name'], $get_event_data['trip_name'], stripslashes(str_replace('|*|', ' >>> ', $get_event_data['destinations'])));
        $response_email = send_email($user_data['email'], $template_slug = "appointment-user-acknowledgement", $chk_arr, $rep_arr, $attach_file = array(), $path = '', $subject = '', $cc = '', $html_template = 'email_template');
        return $response_email;
    }

    public function send_notification_email_to_admin($user_data) {
        $get_event_data = $this->Mydb->get_record("trip_name, destinations", $this->table, array("id" => $user_data['event_id']));
        $chk_arr = array('[NAME]', '[EMAIL]', '[PHONE_NO]', '[PURPOSE_OF_APPOINTMENT]', '[MESSAGE]', '[EVENT_NAME]', '[EVENT_INFO]');
        $rep_arr = array($user_data['name'], $user_data['email'], $user_data['phone_no'], $user_data['purpose_of_appointment'], $user_data['message'], $get_event_data['trip_name'], stripslashes(str_replace('|*|', ' >>> ', $get_event_data['destinations'])));
        $response_email = send_email($this->config->item('to_email', 'siteSettings'), $template_slug = "appointment-notification-to-admin", $chk_arr, $rep_arr, $attach_file = array(), $path = '', $subject = '', $cc = '', $html_template = 'email_template');
        return $response_email;
    }

}
