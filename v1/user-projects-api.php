<?php
ini_set("display_errors", 1);

// include vendor
require_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;


//headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");


//include files
include("../config/database.php");
include("../classes/Users.php");



//objects
$db = new Database();

$connection = $db->connect();

$user_obj = new Users($connection);


if ($_SERVER['REQUEST_METHOD'] === "GET") {


    $header = getallheaders();

    $jwt = $header['Authorization'];

    try {
        $secret_key = "owt123";

        $decoded_data = JWT::decode($jwt, new Key($secret_key, 'HS256'));

        $user_obj->user_id = $decoded_data->data->id;






        $projects = $user_obj->get_user_all_projects();
        $row = count($projects);
        if ($row > 0) {
            $projects_arr = array();
            while ($row = $projects->fetch(PDO::FETCH_ASSOC)) {
                $projects_arr[] = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "status" => $row['status'],
                    "created_at" => $row['created_at']
                );
            }



            http_response_code(200); //ok
            echo json_encode(array(
                "status" => 1,
                "message" => $projects_arr
            ));
        } else {

            http_response_code(404); //no data found
            echo json_encode(array(
                "status" => 0,
                "message" => "No Projects found"
            ));
        }
    } catch (Exception $ex) {



        http_response_code(500); //no data found
        echo json_encode(array(
            "status" => 0,
            "message" => $ex->getMessage()
        ));
    }
}
