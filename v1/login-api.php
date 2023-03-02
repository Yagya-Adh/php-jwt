<?php
ini_set("display_errors", 1);


// include vendor
require_once '../vendor/autoload.php';

use \Firebase\JWT\JWT;


//headers
header("Access-Control-Allow-Origin:*");
header("Access-Control_Allow-Methods: POST");
header("Content-type: application/json; charset=utf-8");



//including files

include_once("../config/database.php");
include_once("../classes/Users.php");

//object
$db = new Database();

$connection = $db->connect();

$user_obj = new Users($connection);


if ($_SERVER['REQUEST_METHOD'] === "POST") {


    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email) && !empty($data->password)) {

        $user_obj->email = $data->email;
        // $user_obj->password = $data->password;

        $user_data = $user_obj->check_login();

        if (!empty($user_data)) {

            $name = $user_data['name'];
            $email = $user_data['email'];
            $password = $user_data['password'];


            if (password_verify($data->password, $password)) {
                //normal password,  //hashed password

                /* payload info */
                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 1220;
                $aud = "myusers";
                $user_arr_data = array(
                    "id" => $user_data['id'],
                    "name" => $user_data['name'],
                    "email" => $user_data['email']
                );

                $payload_info = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $user_arr_data
                );

                /* secret key */
                $secret_key = "owt123";

                /* 
                 we can choose any algorithm type like these:
                    'ES384','ES256', 'ES256K', 'HS256','HS384', 'HS512', 'RS256', 'RS384', and 'RS512' 
                */

                /* jwt encode */
                $jwt = JWT::encode($payload_info, $secret_key, 'HS256');


                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "jwt" => $jwt,
                    "message" => "User logged in successfully"

                ));
            } else {


                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Invalid credentials"
                ));
            }
        } else {

            http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "Invalid credentials"
            ));
        }
    } else {

        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed"
        ));
    }
}
