<?php
class Student_model extends CI_Model
{

    public $first_name;
    public $last_name;
    public $grade;
    public $email;

    public function get_entries()
    {
        $query = $this->db->get('students');
        if (count($query->result()) > 0) {
            return $query->result();
        }
    }

    public function insert_entry($data)
    {
        return $this->db->insert('students', $data);
    }

    public function update_entry($data)
    {
        return $this->db->update('students', $data, array('id' => $data['id']));
    }
    public function delete_entry($id)
    {
        return $this->db->delete('students', array('id' => $id));
    }
    public function single_entry($id)
    {
        $this->db->select('*');
        $this->db->from('students');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if (count($query->result()) > 0) {
            return $query->row();
        }
    }
}
