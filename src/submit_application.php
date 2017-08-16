<?php

    function verifyCapcha($capcha, $secret_key){
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        
        $data = array(
            'secret' => $secret_key,
            'response' => $capcha
        );

        $options = array(
            'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success = json_decode($verify);

        return $captcha_success->success;
    }

    ob_start();

    $google_capcha = '6Le5chcUAAAAAMymO7ubZVMb8CLNp9ePCI6n55OA';

    $result = (object) array(
        'success' => false,
        'message' => "Application could not be submitted. Please try again."
    );

    // lets set us up a connection
    $mysqli = new mysqli('localhost', 'id944324_animalscied', 'qwerty17', 'id944324_applications');

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    // create our sql statement
    $stmt = $mysqli->prepare("INSERT INTO student_applications (first_name, last_name, email, street, city, school_name, school_district, shirt_size, allergies, post_highschool_plans, modivation, as_background, tour_interest, current_school_year, phone_number, secondary_contact, as_experience) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    // bind params, and i guess pretend to be a sssssssnake
    $stmt->bind_param('sssssssssssssssss', $first_name,
                                       $last_name,
                                       $email,
                                       $street,
                                       $city,
                                       $school_name,
                                       $school_district,
                                       $shirt_size,
                                       $allergies,
                                       $post_highschool_plans,
                                       $modivation,
                                       $as_background,
                                       $tour_interest,
                                       $current_school_year,
                                       $phone_number,
                                       $secondary_contact,
                                       $as_experience);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

        $capcha = isset($_POST['capcha']) ? $_POST['capcha'] : null;


        if(verifyCapcha($capcha, $google_capcha)){

            // lets get all of the submitted 
            $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
            $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $street = isset($_POST['street']) ? $_POST['street'] : null;
            $city = isset($_POST['city']) ? $_POST['city'] : null;
            $school_name = isset($_POST['school_name']) ? $_POST['school_name'] : null;
            $school_district = isset($_POST['school_district']) ? $_POST['school_district'] : null;
            $shirt_size = isset($_POST['shirt_size']) ? $_POST['shirt_size'] : null;
            $post_highschool_plans = isset($_POST['career_goals']) ? $_POST['career_goals'] : null;
            $modivation = isset($_POST['modivation']) ? $_POST['modivation'] : null;
            $as_background = isset($_POST['as_background']) ? $_POST['as_background'] : null;
            $tour_interest = isset($_POST['tour_interest']) ? $_POST['tour_interest'] : null;
            $allergies = isset($_POST['allergies']) ? $_POST['allergies'] : null;
            // new params
            $current_school_year = isset($_POST['current_school_year']) ? $_POST['current_school_year'] : null;
            $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
            $secondary_contact = isset($_POST['secondary_contact']) ? $_POST['secondary_contact'] : null;
            $as_experience = isset($_POST['as_experience']) ? $_POST['as_experience'] : null;

            /* execute prepared statement */
            $statement_success = $stmt->execute();

            if($statement_success){
                $result->success = true;
                $result->message = "Application submitted successfully!";
            } else {
                $result->success = false;
                $result->message = $statement_success;
            }
        } else {
            $result->message = "Google says you're a robot";
        }

        //printf("%d Row inserted.\n", $stmt->affected_rows);

    }
    /* close statement and connection */
    $stmt->close();

    /* close connection */
    $mysqli->close();

    // ob_clean();
    if (ob_get_length()) ob_end_clean();
    
    header('Content-Type: application/json');
    echo json_encode($result);

?>