<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./assets/images/header_ic.png" type="image/png">

    <title>Thư viện điện tử</title>
    <!-- Custom fonts for this template -->
    <link href="./vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="./vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href=".\assets\css\mystyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.9.0/main.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        canvas {
            display: block;
            margin: 0 auto;
        }
        #dataTable tbody tr {
            cursor: pointer;
        }
        #dataTable tbody tr:hover {
            background-color: rgba(0,0,0,0.05);
        }
      .dataTables_paginate {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 15px;
    }

    .dataTables_paginate .paginate_button {
        width: 35px;
        height: 35px;
        margin: 0px 5px;
        margin-top: 20px;
        border: 1px solid #dee2e6;
        border-radius: 50%; /* Làm tròn nút */
        background-color: #f8f9fa;
        color: #000; /* Chữ màu đen */
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: #007bff;
        color: white;
    }

    .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .dataTables_paginate .paginate_button.disabled {
        color: #6c757d;
        pointer-events: none;
        opacity: 0.65;
    }
    .nav-link[data-toggle="collapse"]::after {
    color: #423b8e !important;
}
</style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'views/components/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'views/components/navbar.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid ">
                    <?php
                    if (isset($content)) {
                        include ($content);
                    }
                ?>
                </div>
               
            </div>
            <?php include 'views/components/footer.php'; ?>
        </div>
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="./vendor/jquery/jquery.min.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./assets/js/myscript.js"></script>

    <!-- Page level plugins -->
    <script src="./vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="./vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    
    <script src="https://kit.fontawesome.com/1b233c9fdd.js" crossorigin="anonymous"></script>

</body>

</html>