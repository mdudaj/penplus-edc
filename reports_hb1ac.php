<?php
require 'pdf.php';
$user = new User();
$override = new OverideData();
$email = new Email();
$random = new Random();

if ($user->isLoggedIn()) {
    try {
        $site_data = $override->getData('site');
        $Total = $override->getCount('clients', 'status', 1);
        $data_enrolled = $override->getCount1('clients', 'status', 1, 'enrolled', 1);

        $successMessage = 'Report Successful Created';
    } catch (Exception $e) {
        die($e->getMessage());
    }
} else {
    Redirect::to('index.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include 'headBar.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include 'sidemenu.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1> HB1AC</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index1.php">Home</a></li>
                                <li class="breadcrumb-item active">HB1AC</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

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

                .img-thumb-path {
                    width: 100px;
                    height: 80px;
                    object-fit: scale-down;
                    object-position: center center;
                }

                /* Define a class for the table */
                .table {
                    width: 100%;
                    /* Initially, set the width to 100% */
                    margin-bottom: 1rem;
                    background-color: transparent;
                    border-collapse: collapse;
                    border-spacing: 0;
                }

                /* Define styles for table header cells */
                .table th {
                    font-weight: bold;
                    background-color: #f2f2f2;
                    color: #333;
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                /* Define styles for table data cells */
                .table td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }

                /* Small screens (sm) */
                @media only screen and (max-width: 575px) {
                    .table-sm {
                        font-size: 12px;
                        /* Decrease font size for small screens */
                    }
                }

                /* Medium screens (md) */
                @media only screen and (min-width: 576px) and (max-width: 991px) {
                    .table-md {
                        font-size: 14px;
                        /* Set font size for medium screens */
                    }
                }

                /* Large screens (lg) */
                @media only screen and (min-width: 992px) {
                    .table-lg {
                        font-size: 16px;
                        /* Set font size for large screens */
                    }
                }
            </style>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Main row -->
                    <div class="row">                        
                        <!-- Right col -->
                        <section class="col-lg-6 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        HBIAC
                                    </h3>
                                    <!-- <div class="card-tools">
                                        <ul class="nav nav-pills ml-auto">
                                            <li class="nav-item">
                                                <a class="nav-link active" href="#total_vital0" data-toggle="tab">Area</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content p-0">
                                        <!-- Morris chart - Sales -->
                                        <div id="chart-hbiac">
                                            <canvas id="total_hb1ac"></canvas>
                                        </div>
                                        <!-- <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                                            <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                                        </div> -->
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </section>
                        <!-- /.right col -->
                    </div>
                    <!-- /.row (main row) -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include 'footerBar.php'; ?>

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->



    <?php
    $result = $override->getGraphsTotalHba1c('status', 1);
    foreach ($result as $value) {
        $visit_day = $value['visit_day'];
        $visit_date[] = $value['visit_date'];
        $study_id[] = $value['study_id'];
        $hba1c[] = $value['hba1c'];
    }

    ?>


    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>
    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- BS-Stepper -->
    <script src="plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- dropzonejs -->
    <script src="plugins/dropzone/min/dropzone.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->



    <!-- <script src="mycharts/registered.js"></script> -->

    <!-- Page specific script -->
    <script>
        // REGISTARION CHART

        const labels = <?php echo json_encode($visit_day) ?>;

        const data = {
            labels: labels,
            datasets: [{
                label: 'HB1AC',
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

        const config = {
            type: 'line',
            data: data,
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
            document.getElementById('total_hb1ac'),
            config
        );


        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })

        // DropzoneJS Demo Code Start
        Dropzone.autoDiscover = false

        // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
        var previewNode = document.querySelector("#template")
        previewNode.id = ""
        var previewTemplate = previewNode.parentNode.innerHTML
        previewNode.parentNode.removeChild(previewNode)

        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        })

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() {
                myDropzone.enqueueFile(file)
            }
        })

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
        })

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
        })

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0"
        })

        // Setup the buttons for all transfers
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
        document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
        }
        document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true)
        }
        // DropzoneJS Demo Code End
    </script>