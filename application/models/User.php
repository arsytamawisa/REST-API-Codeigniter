<?php

Class User extends CI_Model
{

    public function get($id = null)
    {
        if ($id != null) 
        {
            $this->db->where('id', $id);
            return $this->db->get('users')->row();
        }

        return $this->db->get('users')->result();        
    }


    public function login($email)
    {
        $this->db->where('email', $email);
        return $this->db->get('users')->row();
    }


    public function store()
    {
    	// $data = $this->input->post();

        $data = [
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
        ];

        if ($this->db->insert('users', $data)) 
        {
          return [
             'id' 		=> $this->db->insert_id(),
             'success' 	=> true,
             'message' 	=> 'Data berhasil ditambahkan'
         ];
     }

 }


 public function is_valid()
 {
    $id         = $this->input->post('id');
    $password   = $this->input->post('password');
    $hash       = $this->get($id)->password;

    if (password_verify($password, $hash))
        return true; 

    return false;
}


public function delete($id)
{
    $this->db->where('id', $id);
    if ($this->db->delete('users')) 
    {
        return [
            'success'   => true,
            'message'   => 'Data berhasil dihapus'
        ];
    }
}

}