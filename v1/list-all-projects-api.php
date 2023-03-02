<?php
ini_set("display_errors", 1);

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
    $projects = $user_obj->get_all_projects();

    $row = count($projects);


    if (isset($row)) {
        $projects_arr = array();
        foreach ($projects as $project) {
            $projects_arr[] = array(
                "id" => $project['id'],
                "name" => $project['name'],
                "description" => $project['description'],
                "status" => $project['status'],
                "created_at" => $project['created_at']
            );
        }

        http_response_code(200); //ok
        echo json_encode(array(
            "status" => 1,
            "data" => $projects_arr
        ));
    } else {
        http_response_code(404); //no data found
        echo json_encode(array(
            "status" => 0,
            "message" => "No Projects found"
        ));
    }
}








// ini_set("display_errors", 1);

// //headers
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET");



// //include files
// include("../config/database.php");
// include("../classes/Users.php");



// //objects
// $db = new Database();

// $connection = $db->connect();

// $user_obj = new Users($connection);


// if ($_SERVER['REQUEST_METHOD'] === "GET") {

//     $projects = $user_obj->get_all_projects();

//     $row = $projects->$user_obj->$count;

//     if ($row > 0) {
//         $projects_arr = array();
//         while ($row->fetch(PDO::FETCH_ASSOC)) {
//             $projects_arr[] = array(
//                 "id" => $row['id'],
//                 "name" => $row['name'],
//                 "description" => $row['description'],
//                 "status" => $row['status'],
//                 "created_at" => $row['created_at']
//             );
//         }



//         http_response_code(200); //ok
//         echo json_encode(array(
//             "status" => 1,
//             "message" => $projects_arr
//         ));
//     } else {

//         http_response_code(404); //no data found
//         echo json_encode(array(
//             "status" => 0,
//             "message" => "No Projects found"
//         ));
//     }
// }
