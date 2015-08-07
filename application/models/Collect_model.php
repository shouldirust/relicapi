<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Model for the Users api controller.  Gets users from the database
 */
 
class Collect_model extends CI_Model {
	
	public function __construct() {
		// Call the parent constructor
		parent::__construct();
	}
	
	
	/*
	 * getClientId
	 * 	Accepts the client's name, must be an exact match.
	 *  Returns the client's ID.
	 * 
	 */
	function getClientId($name) {
		// query the database using the name passed in
		$query = $this->db->get_where('clients', array('clientname' => $name));
		$row = $query->row_array(0);	
		
		// Return the ID of the first record from the query
		return $row['id'];
	}




	/*
	 * getHostId
	 * 	Accepts the client's ID and the hostname
	 * 	Returns the host's ID. 
	 * 
	 */
	function getHostId($id, $h) {
		// Use the new client id to get the hostname_id for this client.  In case the same hostname is at multiple clients
		$query = $this->db->get_where('hosts', array('hostname' => $h, 'client_id' => $id));
		$row = $query->row_array(0);
		$hostId = $row['id'];
		
		# If we didn't find the hostname in the database
		if ( !$hostId ) {
			# Create an data array to hold the value we are passing into the hosts table.
			$data = array(
				'hostname' => $h,
				'client_id' => $id
			);
			# Insert the new hostname into the database as a new host record.
			$this->db->insert('hosts', $data);
			
			# Get the id for the new hostname record
			$query = $this->db->get_where('hosts', array('hostname' => $h, 'client_id' => $id));
			$result = $query->row_array(1);
			$hostId = $result['id'];
		}
		
		// Return the host ID.
		return $hostId;
	}
	
	
	
	
	
	
	
	/*
	 * Get all users from the database
	 */
	public function get_users() {
        $query = $this->db->get('users');
		/*foreach ($query->result() as $row)
		{
			return $row;
		}*/
		return $query->result();
	}
	
	/*
	 * Get one user from the database
	 *
	 * $id = the id from the URL
	 */
	public function get_user($id) {
		$query = $this->db->get_where( 'users', array('id' => $id) );
		return $query->result();
	}
	
	
	/*
	 * Returns the client info
	 */
	public function get_client($clientName) {
		// Query the clients table for the record that matches with the value in $client
        $query = $this->db->get_where('clients', array('clientname' => $clientName));
		
		// Get the first row
		$row = $query->row_array(1);
			
		// Return the row
		return $row;
	}
	
	/*
	 * Returns the list of clients in the database
	 */
	public function get_clients() {
		// Query the clients table for the list of all clients
        $this->db->select('clientname');
        $query = $this->db->get('clients');
		
		// Return the row
		return $query->result();
	}
	

	/*
	 * Returns the ID of the row based on the clientname specified.
	 */
	public function get_client_id($clientName) {
		// Query the clients table for the record that matches with the value in $client
        $query = $this->db->get_where('clients', array('clientname' => $clientName));
		
        //$query = $this->db->get('users');
		/*foreach ($query->result() as $row)
		{
			return $row;
		}*/
		$row = $query->row_array(1);
			
		// Return the result
		return $row['id'];
	}	
	
	
	/*
	 * Return the id of the hostname and client provided
	 */
	public function get_hostname_id($client, $hostname) {
	
	}
	
	
	
	
	
	
	/*
	 * Put new storage data into the database
	 */
	public function post_storage($clientName, $hostName, $d1size, $d1free, $d2size, $d2free, $d3size, $d3free) {
		// get the client id
		$clientId = $this->getClientId($clientName);
		// Use the new client id to get the hostname_id for this client.
		$hostId = $this->getHostId($clientId, $hostName);		
		
		// Build the data set
		$data = array(
				'client_id' => $clientId,
				'hostname_id' => $hostId,
				'd1size' => $d1size,
				'd1free' => $d1free,
				'd2size' => $d2size,
				'd2free' => $d2free,
				'd3size' => $d3size,
				'd3free' => $d3free
		);
		
		// Insert the data set into the database
		$this->db->insert('stats_storage_history', $data);
	}






	/*
	 * Put new volume shadow copy data into the database
	 */
	public function post_vss($clientName, $hostName, $vssState, $vssShareCount) {
		// get the client id
		$clientId = $this->getClientId($clientName);
		// Use the new client id to get the hostname_id for this client.
		$hostId = $this->getHostId($clientId, $hostName);
		
		// Build the data set
		$data = array(
				'client_id' => $clientId,
				'hostname_id' => $hostId,
				'vss_state' => $vssState,
				'vss_share_count' => $vssShareCount,
		);
		
		// Insert the data set into the database
		$this->db->insert('stats_vss_history', $data);
	}




}