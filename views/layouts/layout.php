<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>X-Port Click & Collect</title>
    <!-- base:css -->
    <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
    <!-- endinject --> 
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <!-- endinject -->
    <!-- jquery notification: custom modification -->
    <link rel="stylesheet" href="/css/notify.css">
    <!-- end jquery notification-->
    <!-- barcode scanner styles -->
    <link rel="stylesheet" href="/css/barcode_overlay.css">
    <!-- end barcode overlay styles -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <!-- datatable styles -->
    <link rel="stylesheet" href="/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <!-- end datatable styles -->
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="/"><img src="../../images/logo.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="/"><img src="../../images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle  pl-0 pr-0" href="#" data-toggle="dropdown" id="profileDropdown">
                <i class="typcn typcn-user-outline mr-0"></i>
                <span class="nav-profile-name">Store</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="/store">
                <i class="typcn typcn-cog text-primary"></i>
                Registrazione Store
                </a>
                <a href="/auth/logout" class="dropdown-item">
                <i class="typcn typcn-power text-primary"></i>
                Esci
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_settings-panel.html -->
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="/">
              <i class="typcn typcn-device-desktop menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="/store">
                  <i class="typcn typcn-user-outline menu-icon"></i>
                  <span class="menu-title">Store</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="/products">
                  <i class="typcn typcn-dropbox menu-icon"></i>
                  <span class="menu-title">Prodotti</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="/orders">
                  <i class="typcn typcn-archive menu-icon"></i>
                  <span class="menu-title">Ordini</span>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="/sales">
                  <i class="typcn typcn-shopping-cart menu-icon"></i>
                  <span class="menu-title">Vendite</span>
              </a>
          </li>
        </ul>
      </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
              <?=$this->section('content')?>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-center text-sm-left d-block d-sm-inline-block">Copyright © <a href="https://x-port.it/" target="_blank">X-Port</a> <?= date('Y') ?></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>

    <!-- Modal usata per chiedere conferma -->
    <div class="modal fade" id="confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vuoi veramente cancellare questo elemento?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Se procedi con la cancellazione non sarà più possibile ripristinarlo.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary confirmation-button">Conferma</button>
                    <button type="button" class="btn btn-secondary cancel-button" data-dismiss="modal">Annulla</button>
                </div>
            </div>
        </div>
    </div>

    <div id="main-barcode-overlay" class="barcode-overlay" tabindex="-1"></div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="../../vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="../../js/off-canvas.js"></script>
    <script src="../../js/hoverable-collapse.js"></script>
    <script src="../../js/template.js"></script>
    <script src="../../js/settings.js"></script>
    <script src="../../js/todolist.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="../../vendors/progressbar.js/progressbar.min.js"></script>
    <script src="../../vendors/chart.js/Chart.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- jquery notification: custom modification -->
    <script src="/js/notify.js"></script>
    <!-- end jquery notification-->
    <script src="/js/barcode_scanner.js"></script>
    <!-- datatable -->
    <script src="/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- end datatable  -->
  </body>
</html>