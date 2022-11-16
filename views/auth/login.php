<?php  /** @var string $message (importato dal controller) */ ?>
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
  <!-- barcode scanner syles -->
  <link rel="stylesheet" href="/css/barcode_overlay.css">
  <!-- end barcode overlay styles -->
  <link rel="shortcut icon" href="/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="../../images/logo.svg" alt="logo">
              </div>
              <h4>Inserisci il tuo codice di accesso per entrare nel sistema</h4>
              <p class="text-primary"><?= $message ?></p>
              <form id="login-form" class="pt-3" method="post">
                <div class="form-group">
                  <input type="password" name="access_code" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Codice di accesso">
                </div>
                <div class="row align-items-stretch">
                    <div class="col-7 mt-3">
                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">ENTRA</button>
                    </div>
                    <div class="col-1 mt-3" style="font-size: 50px">
                        /
                    </div>
                    <div class="col-4 mt-3">
                        <a class="read-scanner" href=""><img src="/images/barcode_example.png" alt="entra con il barcode" /></a>
                    </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <div id="main-barcode-overlay" class="barcode-overlay" tabindex="-1"></div>

  <!-- container-scroller -->
  <!-- base:js -->
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- jquery notification: custom modification -->
  <script src="/js/notify.js"></script>
  <!-- end jquery notification-->
  <script src="/js/barcode_scanner.js"></script>

  <script>
      jQuery(".read-scanner").click(function(e) {
          e.preventDefault();
          get_from_barcode_scanner("#main-barcode-overlay")
              .then((ean) => {
                  jQuery('[type=password]').val(ean);
                  jQuery('#login-form').submit();
              })
              .catch( () => {
                  jQuery.notify("Per favore inserisci un barcode da scanner",{ type: "danger"});
              });
      });
  </script>

</body>

</html>
