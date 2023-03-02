<?php


class Users
{

    //defn properties

    public $name;
    public $email;
    public $password;
    public $user_id;
    public $project_name;
    public $description;
    public $status;


    private $conn;
    private $users_tbl;
    private $projects_tbl;




    public function __construct($db)
    {
        $this->conn = $db;
        $this->users_tbl = "tbl_users";
        $this->projects_tbl = "tbl_project";
    }





    public function create_user()
    {
        $user_query = "INSERT INTO " . $this->users_tbl . " (name, email, password) VALUES(:name, :email, :password)";

        $user_obj = $this->conn->prepare($user_query);

        $user_obj->bindparam(":name", $this->name, PDO::PARAM_STR);
        $user_obj->bindparam(":email", $this->email, PDO::PARAM_STR);
        $user_obj->bindparam(":password", $this->password, PDO::PARAM_STR);



        if ($user_obj->execute()) {

            return true;
        }

        return false;
    }





    public function check_email()
    {

        $email_query = "SELECT * FROM " . $this->users_tbl . " WHERE email = :email";

        $usr_obj = $this->conn->prepare($email_query);

        $usr_obj->bindparam(":email", $this->email, PDO::PARAM_STR);

        $data = $usr_obj->execute();

        if ($data) {
            /* this data is sent back */
            return $usr_obj->fetch(PDO::FETCH_ASSOC);
        }

        return array();
    }



    public function check_login()
    {

        $email_query = "SELECT * FROM " . $this->users_tbl . " WHERE email = :email";

        $usr_obj = $this->conn->prepare($email_query);

        $usr_obj->bindparam(":email", $this->email, PDO::PARAM_STR);


        $data = $usr_obj->execute();

        if ($data) {

            return $usr_obj->fetch(PDO::FETCH_ASSOC);
        }

        return array();
    }



    //to create projects
    public function create_project()
    {
        //query
        $project_query = "INSERT INTO " . $this->projects_tbl . " (user_id, name, description, status) VALUES( :user_id, :name, :description, :status)";

        //prepare
        $project_obj = $this->conn->prepare($project_query);

        //sanitize input variable
        $project_name = htmlspecialchars(strip_tags($this->project_name));
        $description = htmlspecialchars(strip_tags($this->description));
        $status = htmlspecialchars(strip_tags($this->status));


        //bind paramers
        $project_obj->bindparam("user_id", $this->user_id);
        $project_obj->bindparam(":name", $project_name);
        $project_obj->bindparam(":description", $description);
        $project_obj->bindparam("status", $status);

        if ($project_obj->execute()) {

            return true;
        }

        return false;
    }




    //used to list all projects
    // public function get_all_projects()
    // {
    //     $project_query = "SELECT * FROM " . $this->projects_tbl . " ORDER BY id DESC";
    //     $project_obj = $this->conn->prepare($project_query);

    //     $project_obj->execute();
    //     $result = $project_obj->fetch(PDO::FETCH_ASSOC);
    //     print_r($result);
    // }



    public function get_all_projects()
    {
        try {
            $project_query = "SELECT * FROM " . $this->projects_tbl . " ORDER BY id DESC";
            $project_obj = $this->conn->prepare($project_query);

            $project_obj->execute();
            $result = $project_obj->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                print_r($result);
            } else {
                echo "No projects found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    //used to list all projects
    public function get_user_all_projects()
    {
        $project_query = "SELECT * FROM " . $this->projects_tbl . " WHERE USER_ID=:user_id ORDER BY id DESC";
        $project_obj = $this->conn->prepare($project_query);

        $project_obj->bindparam("user_id", $this->user_id);
        $project_obj->execute();
        $result = $project_obj->fetchAll(PDO::FETCH_ASSOC);
        print_r($result);
    }
}
