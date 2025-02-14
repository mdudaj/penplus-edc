<?php
require_once 'php/core/init.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

// $successMessage = null;
// $pageError = null;
// $errorMessage = null;
$numRec = 50;
if ($user->isLoggedIn()) {

    $profile = $override->getNews('clients', 'status', 1, 'id', $_GET['cid'])[0];
    $clients = $override->getNews('clients', 'status', 1, 'id', $_GET['cid']);

    $category = $override->getNews('main_diagnosis', 'status', 1, 'patient_id', $_GET['cid'])[0];

    if (Input::exists('post')) {
        if (Input::get('edit_client')) {
            $validate = $validate->check($_POST, array(
                'clinic_date' => array(
                    'required' => true,
                ),
                'firstname' => array(
                    'required' => true,
                ),
                'lastname' => array(
                    'required' => true,
                ),
                'dob' => array(
                    'required' => true,
                ),
            ));
            if ($validate->passed()) {
                try {
                    $attachment_file = Input::get('image');
                    if (!empty($_FILES['image']["tmp_name"])) {
                        $attach_file = $_FILES['image']['type'];
                        if ($attach_file == "image/jpeg" || $attach_file == "image/jpg" || $attach_file == "image/png" || $attach_file == "image/gif") {
                            $folderName = 'clients/';
                            $attachment_file = $folderName . basename($_FILES['image']['name']);
                            if (@move_uploaded_file($_FILES['image']["tmp_name"], $attachment_file)) {
                                $file = true;
                            } else { {
                                    $errorM = true;
                                    $errorMessage = 'Your profile Picture Not Uploaded ,';
                                }
                            }
                        } else {
                            $errorM = true;
                            $errorMessage = 'None supported file format';
                        } //not supported format
                    } else {
                        $attachment_file = '';
                    }
                    if (!empty($_FILES['image']["tmp_name"])) {
                        $image = $attachment_file;
                    } else {
                        $image = Input::get('client_image');
                    }
                    if ($errorM == false) {
                        $age = $user->dateDiffYears(date('Y-m-d'), Input::get('dob'));
                        $user->updateRecord('clients', array(
                            'hospital_id' => Input::get('hospital_id'),
                            'clinic_date' => Input::get('clinic_date'),
                            'firstname' => Input::get('firstname'),
                            'middlename' => Input::get('middlename'),
                            'lastname' => Input::get('lastname'),
                            'dob' => Input::get('dob'),
                            'age' => $age,
                            'gender' => Input::get('gender'),
                            'site_id' => $user->data()->site_id,
                            'staff_id' => $user->data()->id,
                            'client_image' => $attachment_file,
                            'comments' => Input::get('comments'),
                            'status' => 1,
                            'created_on' => date('Y-m-d'),
                        ), Input::get('id'));

                        $successMessage = 'Client Updated Successful';
                    }
                } catch (Exception $e) {
                    die($e->getMessage());
                }
            } else {
                $pageError = $validate->errors();
            }
        }
    }
} else {
    Redirect::to('index.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penplus Database | Info</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        #chart-vital {
            display: inline-block;
            position: relative;
            height: 400px;
            width: 100%;
        }

        #chart-hba1c {
            display: inline-block;
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidemenu.php'; ?>

        <?php if ($errorMessage) { ?>
            <div class="alert alert-danger">
                <h4>Error!</h4>
                <?= $errorMessage ?>
            </div>
        <?php } elseif ($pageError) { ?>
            <div class="alert alert-danger">
                <h4>Error!</h4>
                <?php foreach ($pageError as $error) {
                    echo $error . ' , ';
                } ?>
            </div>
        <?php } elseif ($successMessage) { ?>
            <div class="alert alert-success">
                <h4>Success!</h4>
                <?= $successMessage ?>
            </div>
        <?php } ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Summary</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="info.php?id=3&status=3">Go Back</a></li>
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">Patient Summary</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <?php if ($profile['gender'] == 1) { ?>
                                            <img class="profile-user-img img-fluid img-circle" src="dist/img/avatar5.png" alt="User profile picture">

                                        <?php } elseif ($profile['gender'] == 2) { ?>
                                            <img class="profile-user-img img-fluid img-circle" src="dist/img/avatar3.png" alt="User profile picture">

                                        <?php } ?>
                                    </div>
                                    <?php if ($user->data()->accessLevel == 1 || $user->data()->accessLevel == 2) { ?>
                                        <h3 class="profile-username text-center">
                                            <?= $profile['firstname'] . ' - ' . $profile['middlename'] . ' - ' . $profile['lastname']; ?>
                                        </h3>
                                    <?php
                                    } ?>

                                    <?php if ($category['cardiac'] == 1) { ?>
                                        <p class="text-muted text-center">Cardiac</p>

                                    <?php } elseif ($category['diabetes'] == 1) { ?>
                                        <p class="text-muted text-center">Diabtes</p>

                                    <?php } elseif ($category['sickle_cell'] == 1) { ?>
                                        <p class="text-muted text-center">Sickle Cell</p>

                                    <?php } else { ?>
                                        <p class="text-muted text-center">Not Diagnosised</p>
                                    <?php
                                    } ?>



                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>PATIENT ID</b> <a class="float-right"><?= $profile['study_id']; ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>AGE</b> <a class="float-right"><?= $profile['age'] . ' '; ?>years</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>SEX</b>
                                            <a class="float-right">
                                                <?php if ($profile['gender'] == 1) { ?>
                                                    <p class="text-muted text-center">Male</p>

                                                <?php } elseif ($profile['gender'] == 2) { ?>
                                                    <p class="text-muted text-center">Female</p>

                                                <?php } ?>
                                            </a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Site</b>
                                            <a class="float-right">
                                                <?php if ($profile['site_id'] == 1) { ?>
                                                    <p class="text-muted text-center">Kondoa</p>

                                                <?php } elseif ($profile['site_id'] == 2) { ?>
                                                    <p class="text-muted text-center">Karatu</p>

                                                <?php } ?>
                                            </a>
                                        </li>
                                    </ul>

                                    <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <!-- About Me Box -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">About Patient</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <strong><i class="fas fa-book mr-1"></i> Education</strong>

                                    <?php if ($profile['education_level'] == 1) { ?>
                                        <p class="text-muted">Not attended school</p>

                                    <?php } elseif ($profile['education_level'] == 2) { ?>
                                        <p class="text-muted">Primary</p>

                                    <?php } elseif ($profile['education_level'] == 3) { ?>
                                        <p class="text-muted">Secondary</p>

                                    <?php } elseif ($profile['education_level'] == 4) { ?>
                                        <p class="text-muted">Certificate</p>

                                    <?php } elseif ($profile['education_level'] == 5) { ?>
                                        <p class="text-muted">Diploma</p>

                                    <?php } elseif ($profile['education_level'] == 6) { ?>
                                        <p class="text-muted">Undergraduate degree</p>

                                    <?php } elseif ($profile['education_level'] == 7) { ?>
                                        <p class="text-muted">Postgraduate degree</p>

                                    <?php } elseif ($profile['education_level'] == 8) { ?>
                                        <p class="text-muted">N / A</p>

                                    <?php } ?>
                                    <hr>

                                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location / Address</strong>

                                    <p class="text-muted"><?= $profile['physical_address']; ?>, Tanzania</p>

                                    <hr>

                                    <strong><i class="fas fa-pencil-alt mr-1"></i> Employment status</strong>

                                    <?php if ($profile['employment_status'] == 1) { ?>
                                        <p class="text-muted">Employed</p>

                                    <?php } elseif ($profile['employment_status'] == 2) { ?>
                                        <p class="text-muted">Self-employed</p>

                                    <?php } elseif ($profile['employment_status'] == 3) { ?>
                                        <p class="text-muted">Employed but on leave of absence</p>

                                    <?php } elseif ($profile['employment_status'] == 4) { ?>
                                        <p class="text-muted">Unemployed</p>

                                    <?php } elseif ($profile['employment_status'] == 5) { ?>
                                        <p class="text-muted">Student</p>

                                    <?php } ?>

                                    <hr>

                                    <strong><i class="far fa-file-alt mr-1"></i> Notes / Comments</strong>

                                    <p class="text-muted"><?= $profile['comments']; ?></p>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#historys" data-toggle="tab">Hitory & Complication</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#symptoms" data-toggle="tab">Symptoms and Exams</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#vitals" data-toggle="tab">Vitals Signs</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#results" data-toggle="tab">Test and Results</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#medications" data-toggle="tab">Medications</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#hospitalizations" data-toggle="tab">Hospitalizations</a></li>
                                    </ul>
                                </div>
                                <!-- /.card-header -->
                                <?php

                                // $x = 1;
                                // foreach ($clients as $client) {
                                // $screening = $override->get('screening', 'patient_id', $client['id'])[0];
                                // $visit = $override->getCount('visit', 'client_id', $client['id']);
                                // $visits = $override->get('visit', 'client_id', $client['id'])[0];
                                // $end_study = $override->getNews('screening', 'status', 1, 'patient_id', $client['id'])[0];
                                // $termination = $override->firstRow1('visit', 'outcome', 'id', 'client_id', $client['id'], 'visit_code', 'TV')[0]['outcome'];
                                // $type = $override->get('main_diagnosis', 'patient_id', $client['id'])[0];
                                // $site = $override->get('site', 'id', $client['site_id'])[0];
                                // $summary = $override->getlastRow1('summary', 'status', 1, 'patient_id', $client['id'], 'id')[0];


                                // $category = 0;

                                // if ($type['cardiac'] == 1) {
                                //     $category =  $override->countData('cardiac', 'patient_id', $client['id'], 'status', 1);
                                // } elseif ($type['diabetes'] == 1) {
                                //     $category =  $override->countData('diabetic', 'patient_id', $client['id'], 'status', 1);
                                // } elseif ($type['sickle_cell'] == 1) {
                                //     $category =  $override->countData('sickle_cell', 'patient_id', $client['id'], 'status', 1);
                                // } else {
                                //     $category = 0;
                                // }


                                // $demographic = $override->countData('demographic', 'patient_id', $client['id'], 'status', 1);
                                // $vital = $override->countData('vital', 'patient_id', $client['id'], 'status', 1);
                                // $history = $override->countData('history', 'patient_id',  $client['id'], 'status', 1);
                                // $symptoms = $override->countData('symptoms', 'patient_id', $client['id'], 'status', 1);
                                // $diagnosis = $override->countData('main_diagnosis', 'patient_id', $client['id'], 'status', 1);
                                // $results = $override->countData('results', 'patient_id', $client['id'], 'status', 1);
                                // $hospitalization = $override->countData('hospitalization', 'patient_id', $client['id'], 'status', 1);
                                // $treatment_plan = $override->countData('treatment_plan', 'patient_id', $client['id'], 'status', 1);
                                // $dgns_complctns_comorbdts = $override->countData('dgns_complctns_comorbdts', 'patient_id', $client['id'], 'status', 1);
                                // $risks = $override->countData('risks', 'patient_id', $client['id'], 'status', 1);
                                // $hospitalization_details = $override->countData('hospitalization_details', 'patient_id', $client['id'], 'status', 1);
                                // $lab_details = $override->countData('lab_details', 'patient_id', $client['id'], 'status', 1);
                                // $summary = $override->countData('summary', 'patient_id', $client['id'], 'status', 1);
                                // $social_economic = $override->countData('social_economic', 'patient_id', $client['id'], 'status', 1);

                                // $Total_visit_available = 0;
                                // $Total_CRF_available = 0;
                                // $Total_CRF_required = 0;
                                // $progress = 0;

                                $Total_visit_available1 = intval($override->getCount0('visit', 'client_id', $_GET['cid'], 'seq_no', 1));
                                $Total_visit_available2 = intval($override->getCountStatus('visit', 'client_id', $_GET['cid'], 'seq_no', 1, 'visit_status', 1));
                                $Total_visit_available3 = intval($override->getCountStatus('visit', 'client_id', $_GET['cid'], 'seq_no', 1, 'visit_status', 2));
                                $Total_visit_available4 = intval($override->getCountStatus1('visit', 'client_id', $_GET['cid'], 'seq_no', 1, 'visit_status', 0, 'expected_date', date('Y-m-d')));
                                $Total_visit_available5 = intval($override->getCountStatus1('visit', 'client_id', $_GET['cid'], 'seq_no', 1, 'visit_status', 0, 'expected_date', date('Y-m-d')));



                                // $All_visits = $override->get0('visit', 'client_id', $client['id'], 'seq_no', 1);


                                // if ($Total_visit_available1 < 1) {
                                //     $Total_visit_available = 0;
                                //     $Total_CRF_available = 0;
                                //     $Total_CRF_available1 = 0;
                                //     $Total_CRF_available2 = 0;
                                //     $Total_CRF_required = 0;
                                // } elseif ($Total_visit_available1 == 1) {

                                //     if ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                //         $Total_visit_available = intval($Total_visit_available1);

                                //         $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                //             + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //             + intval($summary));

                                //         $Total_CRF_required = 15;
                                //     } elseif ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] > date('Y-m-d')) {
                                //         $Total_visit_available = intval($Total_visit_available1);

                                //         $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                //             + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //             + intval($summary));

                                //         $Total_CRF_required = 15;
                                //     } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                //         $Total_visit_available = intval($Total_visit_available1);

                                //         $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                //             + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //             + intval($summary));

                                //         $Total_CRF_required = 15;
                                //     } elseif ($visit_day['visit_status'] == 2) {
                                //         $Total_visit_available = 1;

                                //         $Total_CRF_available = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($vital) + intval($symptoms) + intval($diagnosis) + intval($results) + intval($hospitalization)
                                //             + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //             + intval($summary));

                                //         $Total_CRF_required = 1;
                                //     } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] > date('Y-m-d')) {
                                //         $Total_visit_available = 0;

                                //         $Total_CRF_available = 0;

                                //         $Total_CRF_required = 0;
                                //     }
                                // } elseif ($Total_visit_available1 > 1) {
                                //     foreach ($All_visits as $visit_day) {
                                //         if ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                //             $Total_visit_available++;
                                //             $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                //             $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                //                 + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //                 + intval($summary));

                                //             $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                //             $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                //             $Total_CRF_required = $Total_CRF_required + 10;
                                //         } elseif ($visit_day['visit_status'] == 1 && $visit_day['expected_date'] > date('Y-m-d')) {
                                //             $Total_visit_available++;
                                //             $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                //             $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                //                 + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //                 + intval($summary));

                                //             $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                //             $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                //             $Total_CRF_required = $Total_CRF_required + 10;
                                //         } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] <= date('Y-m-d')) {
                                //             $Total_visit_available++;

                                //             $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                //             $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                //                 + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //                 + intval($summary));

                                //             $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                //             $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                //             $Total_CRF_required = $Total_CRF_required + 10;
                                //         } elseif ($visit_day['visit_status'] == 2) {
                                //             $Total_visit_available++;

                                //             $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                //             $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                //                 + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //                 + intval($summary));

                                //             $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                //             $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                //             $Total_CRF_required = $Total_CRF_required + 1;
                                //         } elseif ($visit_day['visit_status'] == 0 && $visit_day['expected_date'] > date('Y-m-d')) {
                                //             $Total_visit_available = $Total_visit_available + 0;

                                //             $Total_CRF_available1 = intval(intval($demographic) + intval($history) + intval($category) + intval($social_economic) + intval($diagnosis));

                                //             $Total_CRF_available2 = intval(intval($vital) + intval($symptoms) + intval($results) + intval($hospitalization)
                                //                 + intval($treatment_plan) + intval($dgns_complctns_comorbdts) + intval($risks) + intval($hospitalization_details) + intval($lab_details)
                                //                 + intval($summary));

                                //             $Total_CRF_available1 = $Total_CRF_available + $Total_CRF_available1;
                                //             $Total_CRF_available2 = $Total_CRF_available + $Total_CRF_available2;

                                //             $Total_CRF_required = $Total_CRF_required + 0;
                                //         }
                                //     }

                                //     $Total_CRF_required = $Total_CRF_required + 5;


                                //     $Total_CRF_available = intval($Total_CRF_available1) + intval($Total_CRF_available2);
                                // }

                                // $client_progress = intval(intval($Total_CRF_available) / intval($Total_CRF_required) * 100);
                                ?>
                                <!-- <hr> -->
                                <div class="card-header p-3 text-center">
                                    <span class="badge badge-secondary right">
                                        Visits Expected&nbsp; : &nbsp;&nbsp;&nbsp;<?= $Total_visit_available1 ?> <br>
                                    </span>
                                    <span class="badge badge-info right">
                                        Visits Done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?= $Total_visit_available2 ?> <br>
                                    </span>
                                    <span class="badge badge-danger right">
                                        Visits Missed&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?= $Total_visit_available3 ?> <br>
                                    </span>
                                    <span class="badge badge-warning right">
                                        Visits Pending&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?= $Total_visit_available4 ?> <br>
                                    </span>
                                    <span class="badge badge-default right">
                                        Next Follow Up&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;<?= $Total_visit_available5 ?> <br>
                                    </span>
                                </div>

                                <?php
                                // }
                                ?>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="historys">
                                            <?php
                                            $x = 1;
                                            $historys = $override->getNewsAsc('history', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no');

                                            foreach ($historys as $history) { ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $history['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $history['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient</a> History and Complications</h3>

                                                            <div class="timeline-body">
                                                                <?php if ($history['hypertension'] == 1) { ?>
                                                                    <p class="text-muted">Hypertension</p>
                                                                <?php } ?>
                                                                <?php if ($history['diabetes'] == 1) { ?>
                                                                    <p class="text-muted">Diabetes</p>
                                                                <?php } ?>
                                                                <?php if ($history['ckd'] == 1) { ?>
                                                                    <p class="text-muted">CKD</p>
                                                                <?php } ?>

                                                                <?php if ($history['depression'] == 1) { ?>
                                                                    <p class="text-muted">Depression</p>
                                                                <?php } ?>

                                                                <?php if ($history['hiv'] == 1) { ?>
                                                                    <p class="text-muted">HIV</p>
                                                                <?php } elseif ($history['hiv'] == 3) { ?>
                                                                    <p class="text-muted">HIV - UNKNOWN</p>

                                                                <?php } ?> <?php if ($history['art'] == 1) { ?>
                                                                    <p class="text-muted">ON ART</p>
                                                                <?php } ?>

                                                                <?php if ($history['tb'] == 1) { ?>
                                                                    <p class="text-muted">TB - Smear Positive</p>
                                                                <?php } elseif ($history['tb'] == 2) { ?>
                                                                    <p class="text-muted">TB - Smear Negative</p>
                                                                <?php } elseif ($history['tb'] == 3) { ?>
                                                                    <p class="text-muted">TB - EPTB</p>
                                                                <?php } elseif ($history['tb'] == 4) { ?>
                                                                    <p class="text-muted">NEVER HAD TB</p>
                                                                <?php } elseif ($history['tb'] == 5) { ?>
                                                                    <p class="text-muted">TB - UNKNOWN</p>
                                                                <?php } ?>


                                                                <?php if ($history['smoking'] == 1) { ?>
                                                                    <p class="text-muted">Smoking</p>
                                                                <?php } elseif ($history['smoking'] == 3) { ?>
                                                                    <p class="text-muted">Smoking - UNKNOWN</p>

                                                                <?php } ?>



                                                                <?php if ($history['hepatitis_test'] == 1) { ?>
                                                                    <p class="text-muted">Hepatitis test - ( Yes ) , <?php if ($history['hepatitis_results']) {
                                                                                                                            echo 'Test Date ' . $history['hepatitis_date'] . ' RESULTS' . $history['hepatitis_results'];
                                                                                                                        } else {
                                                                                                                            echo 'NONE';
                                                                                                                        } ?> </p>

                                                                <?php } ?>


                                                                <?php if ($history['blood_group'] == 1) { ?>
                                                                    <p class="text-muted">ABO Blood Group: A+</p>
                                                                <?php } elseif ($history['blood_group'] == 2) { ?>
                                                                    <p class="text-muted">ABO Blood Group: A-</p>
                                                                <?php } elseif ($history['blood_group'] == 3) { ?>
                                                                    <p class="text-muted">ABO Blood Group: B+</p>
                                                                <?php } elseif ($history['blood_group'] == 4) { ?>
                                                                    <p class="text-muted">ABO Blood Group: B-</p>
                                                                <?php } elseif ($history['blood_group'] == 5) { ?>
                                                                    <p class="text-muted">ABO Blood Group: O+</p>
                                                                <?php } elseif ($history['blood_group'] == 6) { ?>
                                                                    <p class="text-muted">ABO Blood Group: O-</p>
                                                                <?php } elseif ($history['blood_group'] == 7) { ?>
                                                                    <p class="text-muted">ABO Blood Group: AB+</p>
                                                                <?php } elseif ($history['blood_group'] == 8) { ?>
                                                                    <p class="text-muted">ABO Blood Group: AB-</p>
                                                                <?php } ?>


                                                                <?php if ($history['alcohol'] == 1) { ?>
                                                                    <p class="text-muted">Alcohol - Yes , Currently</p>
                                                                <?php } elseif ($history['alcohol'] == 2) { ?>
                                                                    <p class="text-muted">Alcohol - Yes , in the Past</p>
                                                                <?php } elseif ($history['alcohol'] == 3) { ?>
                                                                    <p class="text-muted">Alcohol - Never</p>
                                                                <?php } ?>




                                                                <?php if ($history['cardiovascular'] == 1) { ?>
                                                                    <p class="text-muted">Cardiovascular Diseases</p>
                                                                <?php } ?>
                                                                <?php if ($history['retinopathy'] == 1) { ?>
                                                                    <p class="text-muted">Retinopathy</p>
                                                                <?php } ?>
                                                                <?php if ($history['renal'] == 1) { ?>
                                                                    <p class="text-muted">Renal Disease</p>
                                                                <?php } ?> <?php if ($history['stroke_tia'] == 1) { ?>
                                                                    <p class="text-muted">Stroke / TIA</p>
                                                                <?php } ?>
                                                                <?php if ($history['pvd'] == 1) { ?>
                                                                    <p class="text-muted">PVD</p>
                                                                <?php } ?>
                                                                <?php if ($history['neuropathy'] == 1) { ?>
                                                                    <p class="text-muted">Neuropathy</p>
                                                                <?php } ?>
                                                                <?php if ($history['sexual_dysfunction'] == 1) { ?>
                                                                    <p class="text-muted">Sexual dysfunction</p>
                                                                <?php } ?>
                                                                <?php if ($history['pain_event'] == 1) { ?>
                                                                    <p class="text-muted">Pain Event</p>
                                                                <?php } ?>
                                                                <?php if ($history['stroke'] == 1) { ?>
                                                                    <p class="text-muted">Stroke</p>
                                                                <?php } ?>
                                                                <?php if ($history['pneumonia'] == 1) { ?>
                                                                    <p class="text-muted">Pneumonia</p>
                                                                <?php } ?>
                                                                <?php if ($history['blood_transfusion'] == 1) { ?>
                                                                    <p class="text-muted">Blood Transfusion ( YES )- Since Born :- <?php if ($history['transfusion_born']) {
                                                                                                                                        echo $history['transfusion_born'];
                                                                                                                                    } else {
                                                                                                                                        echo ' 0';
                                                                                                                                    }; ?> - For Past 12 Months :- <?php if ($history['transfusion_12months']) {
                                                                                                                                                                        echo $history['transfusion_12months'];
                                                                                                                                                                    } else {
                                                                                                                                                                        echo ' 0';
                                                                                                                                                                    } ?></p>
                                                                <?php } ?>

                                                                <?php if ($history['acute_chest'] == 1) { ?>
                                                                    <p class="text-muted">Acute chest syndrome</p>
                                                                <?php } ?>

                                                                <?php if ($history['other_complication'] == 1) { ?>
                                                                    <p class="text-muted"> Other Complication :- <?= $history['specify_complication']; ?></p>
                                                                <?php } ?>


                                                                <?php if ($history['cardiac_disease'] == 1) { ?>
                                                                    <p class="text-muted">Family History of cardiac disease ? ( YES )</p>
                                                                <?php } elseif ($history['cardiac_disease'] == 3) { ?>
                                                                    <p class="text-muted">Family History of cardiac disease ? ( UNKNOWN )</p>
                                                                <?php } ?>
                                                                <?php if ($history['cardiac_surgery'] == 1) { ?>
                                                                    <p class="text-muted">History of cardiac surgery ? ( YES )</p>

                                                                    <?php if ($history['cardiac_surgery_type'] == 1) { ?>
                                                                        <p class="text-muted">Valve Surgery</p>
                                                                    <?php } elseif ($history['cardiac_surgery_type'] == 2) { ?>
                                                                        <p class="text-muted">Defect Repair</p>
                                                                    <?php } elseif ($history['cardiac_surgery_type'] == 3) { ?>
                                                                        <p class="text-muted"> Other ( YES ) :-
                                                                            <?php if ($history['surgery_other']) {
                                                                                echo $history['surgery_other'];
                                                                            } else {
                                                                                echo ' NONE ';
                                                                            }; ?>
                                                                        </p>
                                                                    <?php } ?>

                                                                <?php } ?>


                                                                <?php if ($history['diabetic_disease'] == 1) { ?>
                                                                    <p class="text-muted">Family History of Diabetic disease ( YES )</p>
                                                                <?php } elseif ($history['diabetic_disease'] == 3) { ?>
                                                                    <p class="text-muted">Family History of Diabetic disease ( UNKNOWN )</p>
                                                                <?php } ?>

                                                                <?php if ($history['hypertension_disease'] == 1) { ?>
                                                                    <p class="text-muted">Hypertension ( YES )</p>
                                                                <?php } ?>




                                                                <?php if ($history['scd_disease'] == 1) { ?>
                                                                    <p class="text-muted">Family History of SCD ( YES ) - How many siblings do you have? :- <?php if ($history['siblings']) {
                                                                                                                                                                echo $history['siblings'];
                                                                                                                                                            } else {
                                                                                                                                                                echo ' 0';
                                                                                                                                                            }; ?> - How many of them are alive? :- <?php if ($history['sibling_salive']) {
                                                                                                                                                                                                        echo $history['sibling_salive'];
                                                                                                                                                                                                    } else {
                                                                                                                                                                                                        echo ' 0';
                                                                                                                                                                                                    } ?>
                                                                    </p>
                                                                <?php } elseif ($history['scd_disease'] == 3) { ?>
                                                                    <p class="text-muted">Family History of SCD ( UNKNOWN )</p>
                                                                <?php } ?>

                                                                <?php if ($history['history_scd'] == 1) { ?>
                                                                    <p class="text-muted">Family History of SCD ( YES ) - How many siblings do you have? :- <?php if ($history['siblings']) {
                                                                                                                                                                echo $history['siblings'];
                                                                                                                                                            } else {
                                                                                                                                                                echo ' 0';
                                                                                                                                                            }; ?> - How many of them are alive? :- <?php if ($history['sibling_salive']) {
                                                                                                                                                                                                        echo $history['sibling_salive'];
                                                                                                                                                                                                    } else {
                                                                                                                                                                                                        echo ' 0';
                                                                                                                                                                                                    } ?>
                                                                    </p>


                                                                <?php } elseif ($history['history_scd'] == 3) { ?>
                                                                    <p class="text-muted">Family History of SCD ( UNKNOWN )</p>
                                                                <?php } ?>


                                                                <?php if ($history['vaccine_history'] == 1) { ?>
                                                                    <p class="text-muted"> Vaccine History ( PNEUMOCOCCAL ) :- </p>
                                                                <?php } elseif ($history['vaccine_history'] == 2) { ?>
                                                                    <p class="text-muted"> Vaccine History ( MENINGOCOCCAL ) :- </p>
                                                                <?php } elseif ($history['vaccine_history'] == 3) { ?>
                                                                    <p class="text-muted">Vaccine History:- Haemophilus Influenza Type B History ( Hib ) :- </p>
                                                                <?php } elseif ($history['vaccine_history'] == 4) { ?>
                                                                    <p class="text-muted">Vaccine History:- PPCV 23 :- </p>
                                                                <?php } elseif ($history['vaccine_history'] == 5) { ?>
                                                                    <p class="text-muted">Vaccine History:- UNKNOWN :- </p>
                                                                <?php } ?>





                                                                <?php if ($history['history_other'] == 1) { ?>
                                                                    <p class="text-muted">Other Family History ( YES ) :- <?php if ($history['history_specify']) {
                                                                                                                                echo $history['history_specify'];
                                                                                                                            } else {
                                                                                                                                echo ' NONE ';
                                                                                                                            }; ?>
                                                                    </p>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <!-- /.tab-pane historys -->
                                        <div class="tab-pane" id="symptoms">
                                            <?php
                                            $x = 1;
                                            $symptoms = $override->getNewsAsc('symptoms', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no');

                                            ?>
                                            <div id="chart-hba1c">
                                                <canvas id="hba1c">
                                                </canvas>
                                            </div>

                                            <hr>
                                            <br>
                                            <?php

                                            foreach ($symptoms as $symptom) {
                                                // $visit_date[] = $symptom['visit_date'];
                                                // $study_id[] = $symptom['study_id'];
                                                $visit_day_hba1c[] = $symptom['visit_day'];
                                                $hba1c[] = $symptom['hba1c'];
                                                // $weight[] = $symptom['weight'];
                                                // $bmi[] = $symptom['bmi'];
                                                // $muac[] = $symptom['muac'];
                                                // $dystolic[] = $symptom['dystolic'];
                                                // $systolic[] = $symptom['systolic'];
                                                // $pr[] = $symptom['pr'];                                               

                                            ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $symptom['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>

                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $symptom['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient's</a> Symptoms, & Exam</h3>

                                                            <div class="timeline-body">
                                                                <p class="text-muted">Hba1c : <?= $symptom['hba1c']; ?></p>

                                                                <?php if ($symptom['orthopnea'] == 1) { ?>
                                                                    <p class="text-muted">Orthopnea</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['paroxysmal'] == 1) { ?>
                                                                    <p class="text-muted">Paroxysmal nocturnal dyspnea</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['dyspnea']) { ?>
                                                                    <?php if ($symptom['dyspnea'] == 1) { ?>
                                                                        <p class="text-muted">Dyspnea on exertion: NYHA Classification :- ( I ) </p>
                                                                    <?php } elseif ($symptom['dyspnea'] == 2) { ?>
                                                                        <p class="text-muted">Dyspnea on exertion: NYHA Classification :- ( II ) </p>
                                                                    <?php } elseif ($symptom['dyspnea'] == 3) { ?>
                                                                        <p class="text-muted">Dyspnea on exertion: NYHA Classification :- ( III ) </p>
                                                                    <?php } elseif ($symptom['dyspnea'] == 4) { ?>
                                                                        <p class="text-muted">Dyspnea on exertion: NYHA Classification :- ( IV ) </p>
                                                                    <?php } elseif ($symptom['dyspnea'] == 5) { ?>
                                                                        <p class="text-muted">Dyspnea on exertion: NYHA Classification :- ( Cannot determine )</p>
                                                                    <?php } ?>
                                                                <?php } ?>


                                                                <?php if ($symptom['cough'] == 1) { ?>
                                                                    <p class="text-muted">Cough</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['thirst'] == 1) { ?>
                                                                    <p class="text-muted">Increased thirst</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['urination'] == 1) { ?>
                                                                    <p class="text-muted">Increased Urination</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['vision'] == 1) { ?>
                                                                    <p class="text-muted">Vision Changes</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['vomiting'] == 1) { ?>
                                                                    <p class="text-muted">Vomiting</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['weight_loss'] == 1) { ?>
                                                                    <p class="text-muted">Weight Loss</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['breathing'] == 1) { ?>
                                                                    <p class="text-muted">Difficulty Breathing</p>
                                                                <?php } ?>


                                                                <?php if ($symptom['chest_pain'] == 1) { ?>
                                                                    <p class="text-muted">Chest Pain</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['abnorminal_pain'] == 1) { ?>
                                                                    <p class="text-muted">Abnorminal Pain</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['headache'] == 1) { ?>
                                                                    <p class="text-muted">Headache Pain</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['upper_arms'] == 1) { ?>
                                                                    <p class="text-muted">Upper arms Pains</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['lower_arms'] == 1) { ?>
                                                                    <p class="text-muted">Lower arms Pains</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['waist'] == 1) { ?>
                                                                    <p class="text-muted">Waist Pains</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['joints'] == 1) { ?>
                                                                    <p class="text-muted">Joints Pains</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['other_pain'] == 1) { ?>
                                                                    <p class="text-muted">Other Pains</p>
                                                                <?php } ?>



                                                                <?php if ($symptom['edema']) { ?>
                                                                    <?php if ($symptom['edema'] == 1) { ?>
                                                                        <p class="text-muted">Edema :- ( None ) </p>
                                                                    <?php } elseif ($symptom['edema'] == 2) { ?>
                                                                        <p class="text-muted">Edema :- ( Trcae ) </p>
                                                                    <?php } elseif ($symptom['edema'] == 3) { ?>
                                                                        <p class="text-muted">Edema :- ( 1+ ) </p>
                                                                    <?php } elseif ($symptom['edema'] == 4) { ?>
                                                                        <p class="text-muted">Edema :- ( 2+ ) </p>
                                                                    <?php } elseif ($symptom['edema'] == 5) { ?>
                                                                        <p class="text-muted">Edema :- ( 3+ ) </p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($symptom['lungs']) { ?>
                                                                    <?php if ($symptom['lungs'] == 1) { ?>
                                                                        <p class="text-muted">Lungs :- ( Clear ) </p>
                                                                    <?php } elseif ($symptom['lungs'] == 2) { ?>
                                                                        <p class="text-muted">Lungs :- ( Bibasilar ) </p>
                                                                    <?php } elseif ($symptom['lungs'] == 3) { ?>
                                                                        <p class="text-muted">Lungs :- ( Crackles ) </p>
                                                                    <?php } elseif ($symptom['lungs'] == 4) { ?>
                                                                        <p class="text-muted">Lungs :- ( Wheeze ) </p>
                                                                    <?php } elseif ($symptom['lungs'] == 5) { ?>
                                                                        <p class="text-muted">Lungs : ( Other ) :- <?= $symptom['lungs_other']; ?> </p>
                                                                    <?php } ?>
                                                                <?php } ?>


                                                                <?php if ($symptom['jvp']) { ?>
                                                                    <?php if ($symptom['jvp'] == 1) { ?>
                                                                        <p class="text-muted">JVP :- ( Eleveated ) </p>
                                                                    <?php } elseif ($symptom['jvp'] == 2) { ?>
                                                                        <p class="text-muted">JVP :- ( Normal ) </p>
                                                                    <?php } elseif ($symptom['jvp'] == 3) { ?>
                                                                        <p class="text-muted">JVP :- ( Unable to determine ) </p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($symptom['volume']) { ?>
                                                                    <?php if ($symptom['volume'] == 1) { ?>
                                                                        <p class="text-muted">Volume status :-( Euvolemic ) </p>
                                                                    <?php } elseif ($symptom['volume'] == 2) { ?>
                                                                        <p class="text-muted">Volume status :-( Hyper ) </p>
                                                                    <?php } elseif ($symptom['volume'] == 3) { ?>
                                                                        <p class="text-muted">Volume status :-( Hypo ) </p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($symptom['murmur']) { ?>
                                                                    <?php if ($symptom['murmur'] == 1) { ?>
                                                                        <p class="text-muted">Loud Murmur :-( Present ) </p>
                                                                    <?php } elseif ($symptom['murmur'] == 2) { ?>
                                                                        <p class="text-muted">Loud Murmur :-( Absent ) </p>
                                                                    <?php } elseif ($symptom['murmur'] == 3) { ?>
                                                                        <p class="text-muted">Loud Murmur :-( Unknown ) </p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($symptom['foot_exam']) { ?>
                                                                    <?php if ($symptom['foot_exam'] == 1) { ?>
                                                                        <?php if ($symptom['foot_exam_finding'] == 1) { ?>
                                                                            <p class="text-muted">Foot Exam :- ( YES ) : ( Normal ) </p>
                                                                        <?php } elseif ($symptom['foot_exam_finding'] == 2) { ?>
                                                                            <p class="text-muted">Foot Exam :- ( YES ) : ( Abnormal ) :- <?= $symptom['foot_exam_other']; ?> </p>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($symptom['fasting'] == 1) { ?>
                                                                    <p class="text-muted">Fasting FS :- <?= $symptom['fasting']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['random_fs'] == 1) { ?>
                                                                    <p class="text-muted">Random FS :- <?= $symptom['random_fs']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['hba1c'] == 1) { ?>
                                                                    <p class="text-muted">HbA1C:( During enrollment ) :- <?= $symptom['hba1c']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['hypoglycemia_symptoms'] == 1) { ?>
                                                                    <p class="text-muted">Symptoms of hypoglycemia</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['hypoglycemia_severe'] == 1) { ?>
                                                                    <p class="text-muted">Severe hypoglycemia in last month ( YES ): how many episodes :- <?= $symptom['hypoglycemia__number']; ?></p>
                                                                <?php } ?>


                                                                <?php if ($symptom['malnutrition'] == 1) { ?>
                                                                    <p class="text-muted">Malnutrition</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['pallor'] == 1) { ?>
                                                                    <p class="text-muted">Pallor</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['jaundice'] == 1) { ?>
                                                                    <p class="text-muted">Jaundice</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['splenomegaly'] == 1) { ?>
                                                                    <p class="text-muted">Splenomegaly</p>
                                                                <?php } ?>

                                                                <?php if ($symptom['anemia'] == 1) { ?>
                                                                    <p class="text-muted">Anaemia</p>
                                                                <?php } ?>



                                                                <?php if ($symptom['hb'] == 1) { ?>
                                                                    <p class="text-muted">Hb: :- <?= $symptom['hb']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['wbc'] == 1) { ?>
                                                                    <p class="text-muted">WBC: :- <?= $symptom['wbc']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['plt'] == 1) { ?>
                                                                    <p class="text-muted">Plt: :- <?= $symptom['plt']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($symptom['labs_other'] == 1) { ?>
                                                                    <p class="text-muted">Other: :- <?= $symptom['labs_other']; ?></p>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <!-- /.tab-pane symptoms -->
                                        <div class="tab-pane" id="vitals">
                                            <?php
                                            $x = 1;
                                            $vitals = $override->getNewsAsc('vital', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no');
                                            ?>

                                            <div id="chart-vital">
                                                <canvas id="vital">
                                                </canvas>
                                            </div>

                                            <hr>
                                            <br>

                                            <?php

                                            foreach ($vitals as $vital) {
                                                $visit_date[] = $vital['visit_date'];
                                                $study_id[] = $vital['study_id'];
                                                $visit_day[] = $vital['visit_day'];
                                                $height[] = $vital['height'];
                                                $weight[] = $vital['weight'];
                                                $bmi[] = $vital['bmi'];
                                                $muac[] = $vital['muac'];
                                                $dystolic[] = $vital['dystolic'];
                                                $systolic[] = $vital['systolic'];
                                                $pr[] = $vital['pr'];



                                            ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $vital['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $vital['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient's</a> Vital Signs</h3>

                                                            <div class="timeline-body">

                                                                <?php if ($vital['height']) { ?>
                                                                    <p class="text-muted">Ht (cm) :- <?= $vital['height']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['weight']) { ?>
                                                                    <p class="text-muted">Wt (kg) :- <?= $vital['weight']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['bmi']) { ?>
                                                                    <p class="text-muted">BMI ( kg/m2 ) :- <?= $vital['bmi']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['muac']) { ?>
                                                                    <p class="text-muted">MUAC (cm) :- <?= $vital['muac']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['systolic']) { ?>
                                                                    <p class="text-muted">Systolic :- <?= $vital['systolic']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['dystolic']) { ?>
                                                                    <p class="text-muted">Dystolic :- <?= $vital['dystolic']; ?></p>
                                                                <?php } ?>

                                                                <?php if ($vital['pr']) { ?>
                                                                    <p class="text-muted">PR :- <?= $vital['pr']; ?></p>
                                                                <?php } ?>

                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <!-- /.tab-pane vitals -->

                                        <div class="tab-pane" id="results">
                                            <?php
                                            $x = 1;
                                            $results = $override->getNewsAsc('results', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no');

                                            foreach ($results as $result) { ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $result['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $result['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient's</a> Results </h3>

                                                            <div class="timeline-body">

                                                                <?php if ($result['ecg_performed'] == 1) { ?>
                                                                    <?php if ($result['ecg'] == 1) { ?>
                                                                        <p class="text-muted">ECG ( DONE ) :- ( Single lead ) </p>
                                                                    <?php } elseif ($result['ecg'] == 2) { ?>
                                                                        <p class="text-muted">ECG ( DONE ) :- ( 12 lead ) </p>
                                                                    <?php } elseif ($result['ecg'] == 3) { ?>
                                                                        <p class="text-muted">ECG ( DONE ) :- ( Normal sinus rythm ) </p>
                                                                    <?php } elseif ($result['ecg'] == 4) { ?>
                                                                        <p class="text-muted">ECG ( DONE ) :- ( Atrial fibrilation ) </p>
                                                                    <?php } elseif ($result['ecg'] == 5) { ?>
                                                                        <p class="text-muted">ECG ( DONE ) : ( Other ) :- <?= $result['ecg_other']; ?> </p>
                                                                    <?php } ?>
                                                                <?php } ?>


                                                                <?php if ($result['echo_performed'] == 1) { ?>
                                                                    <?php if ($result['echo'] == 1) { ?>
                                                                        <p class="text-muted">ECHO ( DONE ) :- ( Echo:(Normal) ) </p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($result['lv'] == 1) { ?>
                                                                    <p class="text-muted">LV severely depressed ( YES )</p>
                                                                <?php } elseif ($result['lv'] == 3) { ?>
                                                                    <p class="text-muted">LV severely depressed ( DONE ) : ( Unseen ) </p>
                                                                <?php } ?>

                                                                <?php if ($result['mitral'] == 1) { ?>
                                                                    <p class="text-muted">Mitral stenosis ( YES )</p>
                                                                <?php } elseif ($result['mitral'] == 3) { ?>
                                                                    <p class="text-muted">Mitral stenosis ( DONE ) : ( Unseen ) </p>
                                                                <?php } ?>

                                                                <?php if ($result['rv'] == 1) { ?>
                                                                    <p class="text-muted">RV severely dilated ( YES )</p>
                                                                <?php } elseif ($result['rv'] == 3) { ?>
                                                                    <p class="text-muted">RV severely dilated : ( Unseen ) </p>
                                                                <?php } ?>

                                                                <?php if ($result['pericardial'] == 1) { ?>
                                                                    <p class="text-muted">Pericardial effusion ( YES )</p>
                                                                <?php } elseif ($result['pericardial'] == 3) { ?>
                                                                    <p class="text-muted">Pericardial effusion : ( Unseen ) </p>
                                                                <?php } ?>

                                                                <?php if ($result['congenital_defect'] == 1) { ?>
                                                                    <p class="text-muted">Congenital defect ( YES )</p>
                                                                <?php } elseif ($result['congenital_defect'] == 3) { ?>
                                                                    <p class="text-muted">Congenital defect : ( Unseen ) </p>
                                                                <?php } ?>

                                                                <?php if ($result['ivc'] == 1) { ?>
                                                                    <p class="text-muted"> IVC dilated,collapse less than 50% ( YES )</p>
                                                                <?php } elseif ($result['ivc'] == 3) { ?>
                                                                    <p class="text-muted"> IVC dilated,collapse less than 50% ( Unseen )</p>
                                                                <?php } ?>

                                                                <?php if ($result['thrombus'] == 1) { ?>
                                                                    <p class="text-muted"> Thrombus ( YES )</p>
                                                                <?php } elseif ($result['thrombus'] == 3) { ?>
                                                                    <p class="text-muted"> Thrombus ( Unseen )</p>
                                                                <?php } ?>


                                                                <?php if ($result['echo_other'] == 1) { ?>

                                                                    <?php if ($result['echo_other2'] == 1) { ?>
                                                                        <p class="text-muted">OTHER ECHO ( DONE ): <?= $result['echo_specify']; ?> ( Yes ) </p>
                                                                    <?php } elseif ($result['echo_other2'] == 3) { ?>
                                                                        <p class="text-muted">OTHER ECHO ( DONE ) : <?= $result['echo_specify']; ?> ( Unseen )</p>
                                                                    <?php } ?>

                                                                <?php } ?>


                                                                <?php if ($result['scd_done'] == 1) { ?>
                                                                    <?php if ($result['scd_test'] == 1) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- Presumptive diagnosis </p>
                                                                    <?php } elseif ($result['scd_test'] == 2) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- Sickling Test</p>
                                                                    <?php } elseif ($result['scd_test'] == 3) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- SS</p>
                                                                    <?php } elseif ($result['scd_test'] == 4) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- SA</p>
                                                                    <?php } elseif ($result['scd_test'] == 5) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- SBThal</p>
                                                                    <?php } elseif ($result['scd_test'] == 6) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- SC</p>
                                                                    <?php } elseif ($result['scd_test'] == 7) { ?>
                                                                        <p class="text-muted">SCD Test ( DONE ) :- Other :- <?= $result['scd_test_other']; ?></p>
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                <?php if ($result['confirmatory_test'] == 1) { ?>
                                                                    <?php if ($result['confirmatory_test_type'] == 1) { ?>
                                                                        <p class="text-muted">Confirmatory Test: ( DONE ) :- Type :- HPLC </p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 2) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- HBE</p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 3) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- IEF</p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 4) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- Basique</p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 5) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- Acide</p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 6) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- HEMOTYPE SC</p>
                                                                    <?php } elseif ($result['confirmatory_test_type'] == 7) { ?>
                                                                        <p class="text-muted">Confirmatory Test ( DONE ) :- Type :- SICKLE SCAN</p>
                                                                    <?php } ?>
                                                                <?php } ?>



                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <!-- /.tab-pane results-->

                                        <div class="tab-pane" id="medications">
                                            <?php
                                            $x = 1;
                                            // $medications = $override->getNewsAsc1('medication_treatments', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $x, 'seq_no');
                                            // $medications = );

                                            foreach ($override->get3('medication_treatments', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $x) as $medication) {
                                                $medications_name = $override->getNews('medications', 'status', 1, 'id', $medication['medication_type'])[0];


                                            ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $medication['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $medication['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient's</a> Medications </h3>

                                                            <div class="timeline-body">

                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <!-- <th>Entry Date</th>
                                                                            <th>Start Date</th> -->
                                                                            <th>Medication name</th>
                                                                            <th>Action</th>
                                                                            <!-- <th>End Date</th> -->
                                                                            <th>Medication Dose</th>
                                                                            <th>Medication Descriptions</th>
                                                                            <!-- <th style="width: 40px">Units</th> -->
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><?= $x; ?> .</td>
                                                                            <!-- <td><?= $medications_name['entry_date']; ?></td> -->
                                                                            <!-- <td><?= $medications_name['start_date']; ?></td> -->
                                                                            <td><?= $medications_name['name']; ?></td>
                                                                            <?php if ($medication['medication_action'] == 1) { ?>
                                                                                <td><span class="badge bg-primary">Continue </span></td>

                                                                            <?php } elseif ($medication['medication_action'] == 2) { ?>
                                                                                <td><span class="badge bg-success">Start </span></td>

                                                                            <?php } elseif ($medication['medication_action'] == 3) { ?>
                                                                                <td><span class="badge bg-danger">Stop </span></td>

                                                                            <?php } elseif ($medication['medication_action'] == 4) { ?>
                                                                                <td><span class="badge bg-warning">Not elgible </span></td>

                                                                            <?php } ?>
                                                                            <!-- <td><?= $medications_name['end_date']; ?></td> -->
                                                                            <td><span class="badge bg-primary"><?= $medication['medication_dose']; ?> </span></td>
                                                                            <td><span class="badge bg-primary"><?= $medication['units']; ?> </span></td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php
                                                $x++;
                                            } ?>

                                        </div>
                                        <!-- /.tab-pane medications-->

                                        <div class="tab-pane" id="hospitalizations">
                                            <?php
                                            $x = 1;
                                            // $medications = $override->getNewsAsc1('medication_treatments', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no', $x, 'seq_no');
                                            $hospitalizations = $override->getNewsAsc('hospitalization_table', 'status', 1, 'patient_id', $_GET['cid'], 'seq_no');

                                            foreach ($hospitalizations as $hospitalization) { ?>
                                                <!-- The timeline -->
                                                <div class="timeline timeline-inverse">
                                                    <!-- timeline time label -->
                                                    <div class="time-label">
                                                        <span class="bg-success">
                                                            <?= $hospitalization['visit_date']; ?>
                                                        </span>
                                                    </div>
                                                    <!-- /.timeline-label -->
                                                    <!-- timeline item -->
                                                    <div>
                                                        <i class="fas fa-envelope bg-primary"></i>

                                                        <div class="timeline-item">
                                                            <span class="time timeline-header"><i class="far fa-clock"></i> <?= $hospitalization['visit_day']; ?></span>

                                                            <h3 class="timeline-header"><a href="#">List of Patient's</a> Hospitalizations </h3>

                                                            <div class="timeline-body">

                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th>Admission Date</th>
                                                                            <th>Admission Reason</th>
                                                                            <th>Discharge Diagnosis</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td><?= $x; ?> .</td>
                                                                            <td><?= $hospitalization['admission_date']; ?></td>
                                                                            <td><?= $hospitalization['admission_reason']; ?> </td>
                                                                            <td><?= $hospitalization['discharge_diagnosis']; ?> </td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <!-- <a href="#" class="btn btn-primary btn-sm">Read more</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- END timeline item -->

                                                    <div>
                                                        <i class="far fa-clock bg-gray"></i>
                                                    </div>
                                                </div>
                                            <?php
                                                $x++;
                                            } ?>

                                        </div>
                                        <!-- /.tab-pane hospitalization-->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php include 'footer.php'; ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->


    <script>
        // VITAL 

        const labels_vital = <?php echo json_encode($visit_day) ?>;

        const data_vital = {
            labels: labels_vital,
            datasets: [{
                label: 'Height',
                data: <?php echo json_encode($height) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                ],
                borderWidth: 1
            }, {
                label: 'Weight',
                data: <?php echo json_encode($weight) ?>,
                backgroundColor: [
                    'rgba(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 159, 64)',
                ],
                borderWidth: 1
            }, {
                label: 'BMI',
                data: <?php echo json_encode($weight) ?>,
                backgroundColor: [
                    'rgba(255, 205, 86, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 205, 86)',
                ],
                borderWidth: 1
            }, {
                label: 'MUC',
                data: <?php echo json_encode($muac) ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                ],
                borderColor: [
                    'rgb(75, 192, 192)',
                ],
                borderWidth: 1
            }, {
                label: 'Systolic',
                data: <?php echo json_encode($systolic) ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                ],
                borderWidth: 1
            }, {
                label: 'Dystolic',
                data: <?php echo json_encode($dystolic) ?>,
                backgroundColor: [
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }, {
                label: 'PR',
                data: <?php echo json_encode($pr) ?>,
                backgroundColor: [
                    'rgba(153, 102, 255, 0.2)',
                    // 'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(153, 102, 255)',
                    // 'rgb(201, 203, 207)'
                ],
                borderWidth: 1
            }]
        };

        const config_vital = {
            type: 'line',
            data: data_vital,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        // === include 'setup' then 'config' above ===

        var myChart_vital = new Chart(
            document.getElementById('vital'),
            config_vital
        );




        // HB1AC 

        const labels_hba1c = <?php echo json_encode($visit_day_hba1c) ?>;
        const data_hba1c = {
            labels: labels_hba1c,
            datasets: [{
                label: 'hba1c Values',
                data: <?php echo json_encode($hba1c) ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                ],
                borderWidth: 1
            }]
        };

        const config_hba1c = {
            type: 'line',
            data: data_hba1c,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            },
        };

        // === include 'setup' then 'config' above ===

        var myChart_hba1c = new Chart(
            document.getElementById('hba1c'),
            config_hba1c
        );
    </script>
</body>

</html>