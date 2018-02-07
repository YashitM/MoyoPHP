<?php
date_default_timezone_set("Asia/Kolkata");
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require_once '../firebase/push.php';
require_once '../firebase/firebase.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($_POST["Authorization"])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $_POST["Authorization"];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);

        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}


/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 * url - /register
 * method - POST
 * params - fb_id,name, email, mobile, gender,dob,ref_number, ref_status
 */
$app->post('/register', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id','name', 'email', 'mobile','gender','ref_status','company'));

    $response = array();

    // reading post params
    $fb_id = $app->request->post('fb_id');
    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $mobile = $app->request->post('mobile');
    $gender = $app->request->post('gender');
    $dob = $app->request->post('dob');
    $ref_number = $app->request->post('ref_number');
    $ref_status = $app->request->post('ref_status');
    $company = $app->request->post('company');
    // validating email address
    // validateEmail($email);

    $db = new DbHandler();
    if(empty($_POST['ref_number'])){
        
        $res = $db->createUser($fb_id,$name,$email,$mobile,$gender,$dob,$ref_status,$ref_number,$company);

        if ($res == USER_CREATED_SUCCESSFULLY) {
            $user = $db->getUserByFb_id($fb_id);

            if ($user != NULL) {
                $response["error"] = false;
                $response['name'] = $user['name'];
                $response['email'] = $user['email'];
                $response['mobile'] = $user['mobile'];
                $response['gender'] = $user['gender'];
                $response['dob'] = $user['dob'];
                $response['apiKey'] = $user['api_key'];
                $response['createdAt'] = $user['created_at'];
                $response['ref_status'] = $user['ref_status'];
                $response['company'] = $user['company'];
                $response["message"] = "You are successfully registered";

            } else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "An error occurred. Please try again";
            }

        } else if ($res == USER_CREATE_FAILED) {
            $response["error"] = true;
            $response["message"] = "Oops! An error occurred while registering";
        } else if ($res == USER_ALREADY_EXISTED) {

            $user = $db->getUserByFb_id($fb_id);

            if ($user != NULL) {
                $response["error"] = false;
                $response['name'] = $user['name'];
                $response['email'] = $user['email'];
                $response['mobile'] = $user['mobile'];
                $response['gender'] = $user['gender'];
                $response['dob'] = $user['dob'];
                $response['apiKey'] = $user['api_key'];
                $response['createdAt'] = $user['created_at'];
                $response['ref_status'] = $user['ref_status'];
                // $response['company'] = $user['company'];
                $response["message"] = "Sorry, this email already existed";
            } else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "An error occurred. Please try again";
            }
        }
        // echo json response
        echoRespnse(201, $response);          
    }
    else {
       $fcm_id = $db->getFcmIdByRefNumber($ref_number);

          if ($fcm_id != NULL) {
             $response["error"] = false;
              // $response['fcm_id'] = $fcm_id['fcm_id'];
              if($fcm_id['fb_id'] != null)
              {
                  $res = $db->createUser($fb_id,$name,$email,$mobile,$gender,$dob,$ref_status,$ref_number, $company);

                  if ($res == USER_CREATED_SUCCESSFULLY) {
                       $user = $db->getUserByFb_id($fb_id);

                       if ($user != NULL && $fcm_id['fcm_id'] !=null) {
                          $firebase = new Firebase();
                          $push = new Push();
                          $payload = array();
                          $payload['team'] = 'India';
                          $payload['score'] = '5.6';
                          $title="Carz Ride On";
                          $message=$name." used your number as reference";
                          $push->setTitle($title);
                          $push->setMessage($message);
                          $push->setImage('');
                          $push->setIsBackground(FALSE);
                          $push->setPayload($payload);
                          $json = $push->getPush();

                          $responses = $firebase->send($fcm_id['fcm_id'], $json);

                          $response["error"] = false;
                          $response['name'] = $user['name'];
                          $response['email'] = $user['email'];
                          $response['mobile'] = $user['mobile'];
                          $response['gender'] = $user['gender'];
                          $response['dob'] = $user['dob'];
                          $response['apiKey'] = $user['api_key'];
                          $response['createdAt'] = $user['created_at'];
                          $response['ref_status'] = $user['ref_status'];
                          //$response['company'] = $user['company'];
                          $response["message"] = "You are successfully registered";
                      } else {
                          // unknown error occurred
                          $response['error'] = true;
                          $response['message'] = "An error occurred. Please try again";
                      }
                  } else if ($res == USER_CREATE_FAILED) {
                      $response["error"] = true;
                      $response["message"] = "Oops! An error occurred while registering else";

                  } else if ($res == USER_ALREADY_EXISTED) {
                      $user = $db->getUserByFb_id($fb_id);
                      if ($user != NULL) {
                          $response["error"] = false;
                          $response['name'] = $user['name'];
                          $response['email'] = $user['email'];
                          $response['mobile'] = $user['mobile'];
                          $response['gender'] = $user['gender'];
                          $response['dob'] = $user['dob'];
                          $response['apiKey'] = $user['api_key'];
                          $response['createdAt'] = $user['created_at'];
                          $response['ref_status'] = $user['ref_status'];
                          $response['company'] = $user['company'];
                          $response["message"] = "Sorry, this email already existed";
                      } else {
                          // unknown error occurred
                          $response['error'] = true;
                          $response['message'] = "An error occurred. Please try again";
                      }
                  }
              }
              else{
                  $response['error'] = true;
                  $response['message'] = "reference number doesn't exisits";
              }
          }else {
              // unknown error occurred
              $response['error'] = true;
              $response['message'] = "reference number doesn't exisits";
          }
        echoRespnse(201, $response);
    }
 });

    /**
     * User Login
     * url - /login
     * method - POST
     * params - email, password
     */
    $app->post('/login', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('email', 'password'));

        // reading post params
        $email = $app->request()->post('email');
        $password = $app->request()->post('password');
        $response = array();

        $db = new DbHandler();
        // check for correct email and password
        if ($db->checkLogin($email, $password)) {
            // get the user by email
            $user = $db->getUserByEmail($email);

            if ($user != NULL) {
                $response["error"] = false;
                $response['name'] = $user['name'];
                $response['email'] = $user['email'];
                $response['apiKey'] = $user['api_key'];
                $response['createdAt'] = $user['created_at'];
            } else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "An error occurred. Please try again";
            }
        } else {
            // user credentials are wrong
            $response['error'] = true;
            $response['message'] = 'Login failed. Incorrect credentials';
        }

        echoRespnse(200, $response);
    });

    /*
     * ------------------------ METHODS WITH AUTHENTICATION ------------------------
     */

    /**
     * Listing all tasks of particual user
     * method GET
     * url /tasks
     */
    $app->get('/tasks', 'authenticate', function() {
        global $user_id;
        $response = array();
        $db = new DbHandler();

        // fetching all user tasks
        $result = $db->getAllUserTasks($user_id);

        $response["error"] = false;
        $response["tasks"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            $tmp["id"] = $task["id"];
            $tmp["task"] = $task["task"];
            $tmp["status"] = $task["status"];
            $tmp["createdAt"] = $task["created_at"];
            array_push($response["tasks"], $tmp);
        }

        echoRespnse(200, $response);
    });

    /** fetching alerts using mobile number and fb_id
     * metod POST
     * url /fetchuserdetailsbyfbid
     * params mobile_number
     */

    $app->post('/fetchingalerts',  function() use ($app) {
        // check for required params
        verifyRequiredParams(array('mobile','fb_id'));

        $response = array();

        $mobile = $app->request->post('mobile');
        $fb_id = $app->request->post('fb_id');

        $db = new DbHandler();

        $result = $db->fetchalerts($mobile,$fb_id);

        if ($result != NULL) {
            $response["error"] = false;
            $response["users"] = array();

            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                /* $tmp["id"] = $task["id"];
                 $tmp["source_distance"] = $task["source_distance"];
                 $tmp["destination_distance"] = $task["destination_distance"];
                 $tmp["fb_id"] = $task[]*/


                if($task["ref_status"] == 1)
                {
                    $task["ref_status"] = "Pending";
                }
                else if($task["ref_status"] == 2)
                {
                    $task["ref_status"] = "Accepted";
                }

                else if($task["ref_status"] == 3)
                {
                    $task["ref_status"] = "Rejected";
                }

                array_push($response["users"], $task);
            }

            echoRespnse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "The requested resource doesn't exists";
            echoRespnse(404, $response);
        }

    });



    /** fetching user posted rides using fb_id
     * metod POST
     * url /fetchinguserpostedrides
     * params fb_id
     */

    $app->post('/fetchinguserpostedrides',  function() use ($app) {
        // check for required params
        verifyRequiredParams(array('fb_id'));

        $response = array();


        $fb_id = $app->request->post('fb_id');

        $db = new DbHandler();

        $result = $db->fetchinguserpostedrides($fb_id);

        if ($result != NULL) {
            $response["error"] = false;
            $response["users"] = array();

            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                /* $tmp["id"] = $task["id"];
                 $tmp["source_distance"] = $task["source_distance"];
                 $tmp["destination_distance"] = $task["destination_distance"];
                 $tmp["fb_id"] = $task[]*/


                array_push($response["users"], $task);
            }

            echoRespnse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "The requested resource doesn't exists";
            echoRespnse(404, $response);
        }

    });



/** fetching user  rides using fb_id
 * metod POST
 * url /fetchinguserpostedrides
 * params fb_id
 */

$app->post('/fetchinguserrides',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id'));

    $response = array();


    $fb_id = $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->fetchinguserrides($fb_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            /* $tmp["id"] = $task["id"];
             $tmp["source_distance"] = $task["source_distance"];
             $tmp["destination_distance"] = $task["destination_distance"];
             $tmp["fb_id"] = $task[]*/


            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});


/** fetching user  rides using fb_id
 * metod POST
 * url /fetchinguserpostedrides
 * params fb_id
 */

$app->post('/fetchingridersinfo',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('ride_id'));

    $response = array();


    $ride_id = $app->request->post('ride_id');

    $db = new DbHandler();

    $result = $db->fetchingridersinfo($ride_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            /* $tmp["id"] = $task["id"];
             $tmp["source_distance"] = $task["source_distance"];
             $tmp["destination_distance"] = $task["destination_distance"];
             $tmp["fb_id"] = $task[]*/


            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});

/** fetching user mobile number using fb_id
 * metod POST
 * url /fetchingusermobile
 * params fb_id
 */

$app->post('/fetchingusermobile',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id'));

    $response = array();

    $fb_id= $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->fetchingusermobile($fb_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});

/** fetching user company using fb_id
 * metod POST
 * url /fetchingusercompany
 * params fb_id
 */

$app->post('/fetchingusercompany',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id'));

    $response = array();

    $fb_id= $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->fetchingusercompany($fb_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});


/** fetching user  rides using fb_id
 * metod POST
 * url /fetchingusercompletedetails
 * params fb_id
 */

$app->post('/fetchingusercompletedetails',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id'));

    $response = array();


    $fb_id= $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->fetchingusercompletedetails($fb_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            /* $tmp["id"] = $task["id"];
             $tmp["source_distance"] = $task["source_distance"];
             $tmp["destination_distance"] = $task["destination_distance"];
             $tmp["fb_id"] = $task[]*/


            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});


/** fetching user  rides using fb_id
 * metod POST
 * url /fetchinguserpostedrides
 * params fb_id
 */

$app->post('/acceptorrejectrefernce',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('status','fb_id'));

    $response = array();


    $status = $app->request->post('status');
    $fb_id = $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->acceptorrejectrefernce($status,$fb_id);

    if ($result) {

        $fcm_id = $db->fetchFcmId($fb_id);


        if ($fcm_id != NULL) {
            $response["error"] = false;

            $firebase = new Firebase();
            $push = new Push();
            $payload = array();
            $payload['team'] = 'India';
            $payload['score'] = '5.6';
            $title="Carz Ride On";
            $message="";
            if($status ==2 )
            {
                $message = "your reference request has been accepted";
            }
            else
            {
                $message = "your reference request has been rejected";
            }

            $push->setTitle($title);
            $push->setMessage($message);
            $push->setImage('');
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);
            $json = $push->getPush();

            $responses = $firebase->send($fcm_id, $json);

            $response["error"] = false;
            $response["status"] = $status;
            echoRespnse(200, $response);


        }
        else
        {

            $response['error'] = false;
            $response['message'] = "An error occurred. Please try again";
            echoRespnse(200, $response);
        }

    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});



/** accepting or rejecting the ride
 * metod POST
 * url /acceptorrejectride
 * params status,ride_id
 */

$app->post('/acceptorrejectride',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('status','ride_id','fb_id'));

    $response = array();


    $status = $app->request->post('status');
    $ride_id = $app->request->post('ride_id');
    $fb_id= $app->request->post('fb_id');
    $db = new DbHandler();

    if($status ==1 )
    {
        $result = $db->updateseatsavailable($ride_id, $fb_id);

        if ($result) {

            acceptorrejectFunction($status,$ride_id,$fb_id);

        }
        else {
            $response["error"] = true;
            $response["message"] = "Failed to create task. Please try again";
            echoRespnse(200, $response);
        }
    }
    else
    {
        acceptorrejectFunction($status,$ride_id,$fb_id);
    }

});

function acceptorrejectFunction($status,$ride_id,$fb_id)
{
    $db = new DbHandler();
    $result = $db->acceptorrejectride($status,$ride_id);

    if ($result) {

        $fcm_id = $db->fetchFcmId($fb_id);


        if ($fcm_id != NULL) {
            $response["error"] = false;


            $response["error"] = false;
            $response["status"] = $status;
            $response["ride_id"] = $ride_id;
            echoRespnse(200, $response);

            $firebase = new Firebase();
            $push = new Push();
            $payload = array();
            $payload['team'] = 'India';
            $payload['score'] = '5.6';
            $title="Carz Ride On";
            $message="";
            if($status ==1 )
            {
                $message = "your ride has been accepted";
            }
            else
            {
                $message = "your ride has been rejected";
            }

            $push->setTitle($title);
            $push->setMessage($message);
            $push->setImage('');
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);
            $json = $push->getPush();

            $responses = $firebase->send($fcm_id, $json);



        }
        else
        {

            $response['error'] = false;
            $response['message'] = "An error occurred. Please try again";
            echoRespnse(200, $response);
        }

    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }
}

/** fetching ride alerts using mobile number and fb_id
 * metod POST
 * url /fetchuserdetailsbyfbid
 * params mobile_number
 */

$app->post('/fetchingridealerts',  function() use ($app) {
    // check for required params
    verifyRequiredParams(array('mobile','fb_id'));

    $response = array();

    $mobile = $app->request->post('mobile');
    $fb_id = $app->request->post('fb_id');

    $db = new DbHandler();

    $result = $db->fetchridealerts($mobile,$fb_id);

    if ($result != NULL) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $result->fetch_assoc()) {
            $tmp = array();
            /* $tmp["id"] = $task["id"];
             $tmp["source_distance"] = $task["source_distance"];
             $tmp["destination_distance"] = $task["destination_distance"];
             $tmp["fb_id"] = $task[]*/


            array_push($response["users"], $task);
        }

        echoRespnse(200, $response);
    } else {
        $response["error"] = true;
        $response["message"] = "The requested resource doesn't exists";
        echoRespnse(404, $response);
    }

});



    /** fetcing user details by facebook id
     * metod POST
     * url /fetchuserdetailsbyfbid
     * params fb_id
     */

    $app->post('/fetchuserdetailsbyfbid',  function() use ($app) {
        // check for required params
        verifyRequiredParams(array('fb_id'));

        $response = array();

        $fb_id = $app->request->post('fb_id');

        $db = new DbHandler();

        $user = $db->getUserByFb_id($fb_id);

        if ($user != NULL) {
            $response["error"] = false;
            $response['name'] = $user['name'];
            $response['email'] = $user['email'];
            $response['mobile'] = $user['mobile'];
            $response['gender'] = $user['gender'];
            $response['dob'] = $user['dob'];
            $response['apiKey'] = $user['api_key'];
            $response['createdAt'] = $user['created_at'];
            $response['ref_status'] = $user['ref_status'];

        } else {
            // unknown error occurred
            $response['error'] = true;
            $response['message'] = "An error occurred. Please try again";
        }
        echoRespnse(200, $response);
    });



    /**
     * Listing single task of particual user
     * method GET
     * url /tasks/:id
     * Will return 404 if the task doesn't belongs to user
     */
    $app->get('/tasks/:id', 'authenticate', function($task_id) {
        global $user_id;
        $response = array();
        $db = new DbHandler();

        // fetch task
        $result = $db->getTask($task_id, $user_id);

        if ($result != NULL) {
            $response["error"] = false;
            $response["id"] = $result["id"];
            $response["task"] = $result["task"];
            $response["status"] = $result["status"];
            $response["createdAt"] = $result["created_at"];
            echoRespnse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "The requested resource doesn't exists";
            echoRespnse(404, $response);
        }
    });


    /**
     * Creating new ride in db
     * method POST
     * params - api_key,car_model,seats,start_time,cost,source,destionation
     * url - /tasks/
     */
    $app->post('/rides', 'authenticate', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('car_model','seats','start_time','cost','source_latitiude','source_longitude','destination_latitude','destination_longitude','ride_date'));

        $response = array();

        $car_model = $app->request->post('car_model');
        $cost = $app->request->post('cost');
        $seats = $app->request->post('seats');
        $start_time = $app->request->post('start_time');
        $source_latitiude = $app->request->post('source_latitiude');
        $source_longitude = $app->request->post('source_longitude');
        $destination_latitude = $app->request->post('destination_latitude');
        $destination_longitude = $app->request->post('destination_longitude');
        $source = $app->request->post('source');
        $destination = $app->request->post('destination');
        $ride_date= $app->request->post('ride_date');
        $message= $app->request->post('message');

        if(is_null($message))
        {
            $message= "";
        }
        global $user_id;
        $db = new DbHandler();

        // creating new ride
        $task_id = $db->createRide($user_id, $car_model,$seats,$cost,$source_latitiude,$source_longitude,$destination_latitude,$destination_longitude,$start_time,$source,$destination,$ride_date,$message);

        if ($task_id != NULL) {
            $response["error"] = false;
            $response["message"] = "Task created successfully";
            $response["task_id"] = $task_id;
            echoRespnse(201, $response);




        } else {
            $response["error"] = true;
            $response["message"] = "Failed to create task. Please try again";
            echoRespnse(200, $response);
        }
    });


    /**
     * fetching near by riders
     * metod POST
     * params - source_latitude,source_longitude,destination_latitiude,destination_longitude
     * url - /fetchriders/
     */


    $app->post('/fetchriders', 'authenticate', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('source_latitiude','source_longitude','destination_latitude','destination_longitude','ride_date'));

        $response = array();


        $source_latitiude = $app->request->post('source_latitiude');
        $source_longitude = $app->request->post('source_longitude');
        $destination_latitude = $app->request->post('destination_latitude');
        $destination_longitude = $app->request->post('destination_longitude');
        $ride_date= $app->request->post('ride_date');

        global $user_id;

        $db = new DbHandler();

        // fetch riders
        $result = $db->getNearByRiders($user_id,$source_latitiude, $source_longitude,$destination_latitude,$destination_longitude,$ride_date);

        if ($result != NULL) {
            $response["error"] = false;
            $response["users"] = array();

            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                /* $tmp["id"] = $task["id"];
                 $tmp["source_distance"] = $task["source_distance"];
                 $tmp["destination_distance"] = $task["destination_distance"];
                 $tmp["fb_id"] = $task[]*/


                array_push($response["users"], $task);
            }

            echoRespnse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "The requested resource doesn't exists";
            echoRespnse(404, $response);
        }



    });


    /** saving fcm id
     * method POST
     * params - fcm_id
     * url - /updateFcmID
     */
    $app->post('/updateFcmID', 'authenticate', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('fcm_id'));

        $response = array();
        $fcm_id = $app->request->post('fcm_id');

        global $user_id;
        $db = new DbHandler();

        // creating new task
        $result = $db->updateFcmID($user_id, $fcm_id);

        if ($result) {
            $response["error"] = false;
            $response["message"] = "FCM ID updated successfully";
            $response["fcm_id"] = $fcm_id;
            echoRespnse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Failed to create task. Please try again";
            echoRespnse(200, $response);
        }
    });

    /** update seats available for a ride and place a ride
     * method POST
     * params - ride_id,fb_id
     * url - placeride
     */

    $app->post('/placeride', 'authenticate', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('ride_id','fb_id','user_name'));

        $response = array();
        $fb_id = $app->request->post('fb_id');
        $ride_id = $app->request->post('ride_id');
        $user_name = $app->request->post('user_name');
        $message= $app->request->post('message');
        global $user_id;
        $db = new DbHandler();


        if(is_null($message))
        {
            $message = "";
        }


        // creating new task
        /*   $result = $db->updateseatsavailable($ride_id, $fb_id);

        if ($result) {*/

        $userride = $db->createUserRide($user_id,$ride_id,$message);
        if($userride!=NULL)
        {
            $fcm_id = $db->fetchFcmId($fb_id);
            $response["error"] = false;
            $response["message"] = "ride placed successfully";
            $response["ride_id"] = $fcm_id;

            $firebase = new Firebase();
            $push = new Push();
            $payload = array();
            $payload['team'] = 'India';
            $payload['score'] = '5.6';
            $title="You got a ride";
            $message=$user_name." wanna ride with you";
            $push->setTitle($title);
            $push->setMessage($message);
            $push->setImage('');
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);
            $json = $push->getPush();

            $responses = $firebase->send($fcm_id, $json);
            echoRespnse(201, $response);

        }

        /*   } else {
               $response["error"] = true;
               $response["message"] = "Failed to create task. Please try again";
               echoRespnse(200, $response);
           }       */
    });

/**
 * Creating new task in db
 * method POST
 * params - name
 * url - /tasks/
 */
$app->post('/contactus', 'authenticate', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('type','message'));

    $response = array();
    $type = $app->request->post('type');
    $message = $app->request->post('message');

    global $user_id;
    $db = new DbHandler();
    $file_path = "images/";
    // creating new task
    $task_id = $db->createContactus($type,$message,$user_id);

    if ($task_id != NULL) {

        if(isset($_FILES['uploaded_file'])&& $_FILES["uploaded_file"]["error"] == 0)
        {

            $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);

            try
            {
                if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {

                    $result = $db->updateAttachmentUrl($file_path,$task_id);
                    if ($result) {
                        // task updated successfully
                        $response["error"] = false;
                        $response["message"] = "Thanks for your support";
                        echoRespnse(201, $response);
                    }else {
                        // task failed to update
                        $response["error"] = false;
                        $response["message"] = "Thanks for your support";
                        echoRespnse(201, $response);
                    }
                }
                else
                {
                    $response["error"] = false;
                    $response["message"] = "Thanks for your support";
                    echoRespnse(201, $response);
                }
            }
            catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

        }
        else
        {
            $response["error"] = false;
            $response["message"] = "Thanks for your supportss";
            echoRespnse(201, $response);
        }

        $user = $db->getUserByFb_id($user_id);
        sendContactUsMail($type,$user,$message,$file_path);
    }
    else
    {
        $response["error"] = true;
        $response["message"] = "Something went wrong";
        echoRespnse(201, $response);
    }

});



    /**
     * Creating new task in db
     * method POST
     * params - name
     * url - /tasks/
     */
    $app->post('/tasks', 'authenticate', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('task'));

        $response = array();
        $task = $app->request->post('task');

        global $user_id;
        $db = new DbHandler();

        // creating new task
        $task_id = $db->createTask($user_id, $task);

        if ($task_id != NULL) {
            $response["error"] = false;
            $response["message"] = "Task created successfully";
            $response["task_id"] = $task_id;
            echoRespnse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Failed to create task. Please try again";
            echoRespnse(200, $response);
        }
    });

    /**
     * Updating existing task
     * method PUT
     * params task, status
     * url - /tasks/:id
     */
    $app->put('/tasks/:id', 'authenticate', function($task_id) use($app) {
        // check for required params
        verifyRequiredParams(array('task', 'status'));

        global $user_id;
        $task = $app->request->put('task');
        $status = $app->request->put('status');

        $db = new DbHandler();
        $response = array();

        // updating task
        $result = $db->updateTask($user_id, $task_id, $task, $status);
        if ($result) {
            // task updated successfully
            $response["error"] = false;
            $response["message"] = "Task updated successfully";
        } else {
            // task failed to update
            $response["error"] = true;
            $response["message"] = "Task failed to update. Please try again!";
        }
        echoRespnse(200, $response);
    });

    /**
     * Deleting task. Users can delete only their tasks
     * method DELETE
     * url /tasks
     */
    $app->delete('/tasks/:id', 'authenticate', function($task_id) use($app) {
        global $user_id;

        $db = new DbHandler();
        $response = array();
        $result = $db->deleteTask($user_id, $task_id);
        if ($result) {
            // task deleted successfully
            $response["error"] = false;
            $response["message"] = "Task deleted succesfully";
        } else {
            // task failed to delete
            $response["error"] = true;
            $response["message"] = "Task failed to delete. Please try again!";
        }
        echoRespnse(200, $response);
    });

    /**
     * Verifying required params posted or not
     */
    function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;
        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $app = \Slim\Slim::getInstance();
            $response["error"] = true;
            $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            echoRespnse(400, $response);
            $app->stop();
        }
    }

    /**
     * Validating email address
     */
    function validateEmail($email) {
        $app = \Slim\Slim::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["error"] = true;
            $response["message"] = 'Email address is not valid';
            echoRespnse(400, $response);
            $app->stop();
        }
    }


    function sendContactUsMail($type,$user,$message,$file_path)
    {
        $to = "support@carzrideon.com, pradeep.moyosolutions@gmail.com";
        $subject = "You got a ".$type;

        $message = "
        <html>
        <head>
            <title>".$type."</title>
        </head>
        <body>
            <p>".$type.": ".$message."</p>";


        $message = $message ."<table>
            <tr>

                <th>User Name</th>

                <th>User Mobile</th>
                <th>Screen shot path</th>


            </tr>
            <tr>
                <td>".$user['name']."</td>

                <td>".$user['mobile']."</td>
                <td>http://carzrideon.com/rideon/v1/".$file_path."</td>

            </tr>
        </table>";


        $message = $message."</body>
        </html>
        ";

// Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
        $headers .= 'From: <support@carzrideon.com>' . "\r\n";
        $headers .= 'Cc: pradeep.moyosolutions@gmail.com' . "\r\n";



        mail($to,$subject,$message,$headers);
    }


    /** Cancel ride
     * method POST
     * params - ride_id, fb_id
     * url - /cancelRide
     */
    $app->post('/cancelRide', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('ride_id','fb_id'));

        $response = array();

        $ride_id = $app->request->post('ride_id');
        $fb_id= $app->request->post('fb_id');
        $db = new DbHandler();

        $result = $db->updateRideStatusToCancel($ride_id, $fb_id);

        if ($result) {
            cancelRideNotifyToUsers($ride_id);
        }else {
            $response["error"] = true;
            $response["message"] = "Failed to cancel ride. Please try again";
            echoRespnse(404, $response);
        }
    });


/**
 * Cancel ride notify to travellers
 * @param String ride_id
 */
function cancelRideNotifyToUsers($ride_id){

    $db = new DbHandler();
    $usersToNotify = $db->cancelRideQueryNotifyToUsers($ride_id);

    if ($usersToNotify) {
        $response["error"] = false;
        $response["users"] = array();

        // looping through result and preparing tasks array
        while ($task = $usersToNotify->fetch_assoc()) {
            $tmp = array();
            //$tmp["ride_id"] = $task["ride_id"];
            //$tmp["fb_id"] = $task["fb_id"];

            $response["error"] = false;
            $response["message"] = "Ride cancelled successfully";
            $response["ride_id"] = $ride_id;
            $response["fb_id"] = $task["fb_id"];
            echoRespnse(201, $response);

            $fcm_id = $db->fetchFcmId($task["fb_id"]);

            if ($fcm_id != NULL) {
                $response["error"] = false;
                $response["ride_id"] = $ride_id;
                echoRespnse(200, $response);

                $firebase = new Firebase();
                $push = new Push();
                $payload = array();
                $payload['team'] = 'India';
                $payload['score'] = '5.6';
                $title="Carz Ride On";
                $message = "Your ride has been cancelled";

                $push->setTitle($title);
                $push->setMessage($message);
                $push->setImage('');
                $push->setIsBackground(FALSE);
                $push->setPayload($payload);
                $json = $push->getPush();

                $responses = $firebase->send($fcm_id, $json);
            }
            else
            {
                $response['error'] = false;
                $response['message'] = "An error occurred in fb id. Please try again";
                echoRespnse(200, $response);
            }
            array_push($response["users"], $tmp);
        }
        echoRespnse(200, $response);

    }
    else
    {
        $response['error'] = false;
        $response['message'] = "An error occurred. Please try again";
        echoRespnse(200, $response);
    }

}

    /**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    function echoRespnse($status_code, $response) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json');

        echo json_encode($response);
    }


    /** Ratings
     * method POST
     * params - rate
     * url - /ratings
     */
    $app->post('/ratings', function() use ($app) {
        // check for required params
        verifyRequiredParams(array('rate','fb_id'));

        $response = array();

        $rate = $app->request->post('rate');
        $fb_id = $app->request->post('fb_id');
        $db = new DbHandler();

        $result = $db->insertRatings($rate, $fb_id);

        if ($result) {
            $response["error"] = true;
            $response["rate"] = $rate;
            $response["fb_id"] =$fb_id;
            $response["message"] = "Thanks for your valuable ratings";
        }else {
            $response["error"] = true;
            $response["rate"] = $rate;
            $response["fb_id"] =$fb_id;
            $response["message"] = "Failed to rate user experience. Please try again";
        }
        echoRespnse(201, $response);

    });


/**
 * Creating new task in db
 * method POST
 * params - name
 * url - /tasks/
 */
$app->post('/aadharAttach', 'authenticate', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('fb_id'));

    $response = array();

    global $user_id;
    $fb_id = $app->request->post('fb_id');
    $db = new DbHandler();
    $file_path = "aadhar/";
    // creating new task

        if(isset($_FILES['uploaded_file'])&& $_FILES["uploaded_file"]["error"] == 0)
        {
            $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
            try
            {
                if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {

                    $result = $db->updateAadhar($file_path,$fb_id);
                    if ($result) {
                        // task updated successfully
                        $response["error"] = false;
                        $response["message"] = "Thanks for your support";
                        echoRespnse(201, $response);
                    }else {
                        // task failed to update
                        $response["error"] = false;
                        $response["message"] = "Thanks for your support";
                        echoRespnse(201, $response);
                    }
                }
                else
                {
                    $response["error"] = false;
                    $response["message"] = "Thanks for your support";
                    echoRespnse(201, $response);
                }
            }
            catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

        }
        else
        {
            $response["error"] = false;
            $response["message"] = "Thanks for your supportss";
            echoRespnse(201, $response);
        }

});



    $app->run();
    ?>