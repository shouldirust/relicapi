<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Relic Collector REST API
 * The interface responsible for listening for data from the remote collectors.
 * Interacts with the Collect_model to insert queries into the database. 
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Russ Shouldice
 * @link		http://www.rtek.ca
 *
 * Based on the REST_Controller example by Phil Sturgeon http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Collect extends REST_Controller {
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
		
		// Load the Collect model
		$this->load->model('Collect_model');
    }
    

  
	
	
	/*
	 * Return the list of users from the users table
	 * 
	 * Example: /api/collect/users
	 */
    function users_get()
    {
		// Get all users from the database and stick it into an array.
        $users = $this->Collect_model->get_users('users');
				
		// Send the response back
        if($users) {
            $this->response($users, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any users!'), 404);
        }
        
    }	
	
	
	
	/*
	 * Looks up the client by name.
	 * 
	 * Example: /api/collect/client/name/<clientName> 
	 * 
	 */
	function client_get()
    {
		// Put the value from the name field into a variable.
		$name = $this->get('name');
			
		// Checks if there is a value in the name field
		if(!$name) {
			$this->response(array('error' => 'A client name was not specified in the URL (e.g. /collect/client/name/<name>'), 400);
		}			
			
		// Get all users from the database and stick it into an array.
	    $client = $this->Collect_model->get_client($name);
		
		// Send the response back
        if($client) {
            $this->response($client, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any data!'), 404);
        }
    }	
	
	
	/*
	 * Returns the ID of the client by name.
	 */
	function client_id_get()
    {
		// Put the value from the name field into a variable.
		$name = $this->get('name');
			
		// Checks if there is a value in the name field
		if(!$name) {
			$this->response(array('error' => 'A client name was not specified in the URL (e.g. /collect/client/name/#'), 400);
		}			
			
		// Get the ID the client from the database
	    $client_id = $this->Collect_model->get_client_id($name);

		// Send the response back
        if($client_id) {
            $this->response($client_id, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any data!'), 404);
        }
    }	
	


	/*
	 * Get the complete list of clients
	 * 
	 * Example: /api/collect/clients
	 * 
	 */
	function clients_get()
    {
		// Get the names of all clients from the database and stick it into an array.
	    $clients = $this->Collect_model->get_clients();
		
		// Send the response back
        if($clients) {
            $this->response($clients, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'Couldn\'t find any data!'), 404);
        }
    }



	
	
	/*
	 *
	 */
    function user_get()
    {
        // Checks if there is a value in the 'id' field
		if(!$this->get('id')) {
        	//$this->response(NULL, 400);
			$this->response(array('error' => 'A user id was not specified in the URL (e.g. /collect/user/id/#'), 400);
        }

		// Get individual user from the database using the id
		$user = $this->Users_model->get_user( $this->get('id') );
		
		// Send the response back
        if($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(array('error' => 'User could not be found'), 404);
        }
    }
	
	
	
	
	
	/*
	 * Accepts storage data from remote collector a uses the Collect_model to posts new storage data into the database.
	 * 
	 * It is expecting an a json encoded array with the following keys.
	 * 		clientName,
	 * 		hostName,
	 * 		d1size,
	 * 		d1free,
	 * 		d2size,
	 * 		d2free,
	 * 		d3size,
	 * 		d3free
	 * 
	 */
    function storage_post() {
		// build the data set based on post data from the collector
		$clientName = $this->post('clientname');
		$hostName = $this->post('hostname');
		$d1size = $this->post('d1size');
		$d1free = $this->post('d1free');
		$d2size = $this->post('d2size');
		$d2free = $this->post('d2free');
		$d3size = $this->post('d3size');
		$d3free = $this->post('d3free');

		// Send the data to the model.
		$this->Collect_model->post_storage( $clientName, $hostName, $d1size, $d1free, $d2size, $d2free, $d3size, $d3free );
    }	
	
	
	
	/*
	 * Accepts vss data from remote collector a uses the Collect_model to posts new data into the database.
	 * 
	 * It is expecting an a json encoded array with the following keys.
	 * 		clientName,
	 * 		hostName,
	 * 		vssState,
	 * 		d1free,
	 * 
	 */
    function vss_post() {
		// build the data set based on post data from the collector
		$clientName = $this->post('client');
		$hostName = $this->post('hostname');
		$vssState = $this->post('vssState');
		$vssShareCount = $this->post('vssShareCount');

		// Send the data to the model.
		$this->Collect_model->post_vss( $clientName, $hostName, $vssState, $vssShareCount );
    }
	
	
	
	
	
	
	
	
	
	
	
	
    function user_delete()
    {
    	//$this->some_model->deletesomething( $this->get('id') );
        $message = array('id' => $this->get('id'), 'message' => 'DELETED!');
        
        $this->response($message, 200); // 200 being the HTTP response code
    }
    
	



	public function send_post()
	{
		var_dump($this->request->body);
	}


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}