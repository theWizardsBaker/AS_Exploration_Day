<?php

if (isset($_POST['submit'])) {

    $google_capcha = '6Le5chcUAAAAAMymO7ubZVMb8CLNp9ePCI6n55OA';

    $result = (object) array(
        'success' => false,
        'message' => ''
    );

    // lets set us up a connection
    $mysqli = new mysqli('localhost', 'id944324_animalscied', 'qwerty17', 'id944324_applications');

    /* check connection */
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    // create our sql statement
    $stmt = $mysqli->prepare("INSERT INTO student_applications (first_name, last_name, email, street, city, school_name, school_district, shirt_size, allergies, post_highschool_plans, modivation, as_background, tour_interest) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    // bind params, and i guess pretend to be a sssssssnake
    $stmt->bind_param('sssssssssssss', $first_name,
                                       $last_name
                                       $email,
                                       $street
                                       $city,
                                       $school_name,
                                       $school_district,
                                       $shirt_size,
                                       $allergies,
                                       $post_highschool_plans,
                                       $modivation,
                                       $as_background,
                                       $tour_interest);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
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

        /* execute prepared statement */
        $statement_success = $stmt->execute();

        if($statement_success){
            $result['success'] = true;
            $result['message'] = "Application submitted successfully!";
        } else {
            $result['success'] = false;
            $result['message'] = "Application could not be submitted. Please try again.";
        }

        //printf("%d Row inserted.\n", $stmt->affected_rows);

    }
    /* close statement and connection */
    $stmt->close();

    /* close connection */
    $mysqli->close();

    echo json_encode($result)
}

?>