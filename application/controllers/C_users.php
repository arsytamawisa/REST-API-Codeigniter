<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH .'/libraries/JWT.php';
use \Firebase\JWT\JWT;

Class C_users extends CI_Controller 
{
    private $secret = 'this is key secret';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user');

        // ALLOW CORS
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, Content-Range, Content-Disposition, Content-Description');
    }


    public function index()
    {
      return $this->response($this->user->get());
  }


  public function response($data)
  {
    $this->output
    ->set_content_type('application/json')
    ->set_status_header(200)
    ->set_output(json_encode($data))
    ->_display(); 
    exit;
}


public function user($id = null)
{
    // METHOD GET
    if ($id != null && $_SERVER['REQUEST_METHOD'] == 'GET') 
    {
        return $this->response($this->user->get($id));
    }

    // METHOD DELETE 
    elseif ($id != null && $_SERVER['REQUEST_METHOD'] == 'DELETE') 
    {
        if ($id_token = $this->check_token()) 
        {
            if ($id_token == $id) 
            {
                return $this->response($this->user->delete($id));
            }
            else
            {
                return $this->response([
                    'success' => false,
                    'message' => 'user tidak bisa menghapus akun dari id lain',
                ]);
            }
        }
    }

    // METHOD PUT 
    elseif ($id != null && $_SERVER['REQUEST_METHOD'] == 'PUT') 
    {
        $data = json_decode(file_get_contents('php://input'));

        if ($id_token = $this->check_token()) 
        {
            if ($id_token == $id) 
            {
                return $this->response($this->user->update($id, $data));
            }
            else
            {
                return $this->response([
                    'success' => false,
                    'message' => 'user tidak bisa menghapus akun dari id lain',
                ]);
            }
        }
    }

    else
    {
        return $this->response($this->user->store());
    }
}


public function login()
{
    $date = new DateTime();

    if (!$this->user->is_valid()) 
    {
        return $this->response([
            'success' => false,
            'message' => 'email atau password salah',
        ]);
    }

    $user               = $this->user->get($this->input->post('id'));
    $payload['id']      = $user->id;
    $payload['email']   = $user->email;
    $payload['iat']     = $date->getTimestamp();
    $payload['exp']     = $date->getTimestamp() + 60*60*24;
    $output['id_token'] = JWT::encode($payload, $this->secret);
    $this->response($output);
}


public function check_token()
{
    $jwt = $this->input->get_request_header('Authorization');

    try 
    {
        $decode = JWT::decode($jwt, $this->secret, array('HS256'));
        return $decode->id;
    } 
    catch(Exception $error)
    {
        return $this->response([
            'success' => false,
            'message' => 'gagal, error token',
        ]);
    }

}


public function delete($id)
{
    if ($id_token = $this->check_token()) 
    {
        if ($id_token == $id) 
        {
            return $this->response($this->user->delete($id));
        }
        else
        {
            return $this->response([
                'success' => false,
                'message' => 'user tidak bisa menghapus akun dari id lain',
            ]);
        }
    }
}



}