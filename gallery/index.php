<?php
session_start();
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
  <meta name="description" content="Photo Gallery of Sacred Heart Convent School" />
  <meta name="keywords" content="Sacred Heart Convent School" />
  <meta name="author" content="Florebit Digital" />
  <title>Photo Gallery | Jamshedpur Public School</title>
  <link href="../images/favicon.png" rel="shortcut icon" type="image/png">

  <!-- Stylesheets -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/jquery-ui.min.css" rel="stylesheet">
  <link href="../css/animate.css" rel="stylesheet">
  <link href="../css/css-plugin-collections.css" rel="stylesheet"/>
  <link href="../css/menuzord-skins/menuzord-rounded-boxed.css" rel="stylesheet"/>
  <link href="../css/style-main.css" rel="stylesheet">
  <link href="../css/custom-bootstrap-margin-padding.css" rel="stylesheet">
  <link href="../css/responsive.css" rel="stylesheet">
  <link href="../css/colors/theme-skin-color-set-1.css" rel="stylesheet">
  <script src="../js/jquery-2.2.4.min.js"></script>
  <script src="../js/jquery-ui.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery-plugin-collection.js"></script>
  <?php include ('../include/extra-css.css')?>

  <style>
    .albumview .text-center1 {
        background: #246D19;
        color: #fff;
        padding: 10px;
        text-align: center;
        min-height: 40px;
        font-size: 16px;
    }
    .albumview img {
        border: 1px solid #ffc107;
        width: 100%;
        height: 260px;
        object-fit: cover;
    }
  </style>
</head>
<body>
<div id="wrapper" class="clearfix">
  <?php include('../include/header.php')?>

  <div class="main-content">
    <div class="kingster-page-title-wrap kingster-style-custom kingster-left-align">
      <div class="kingster-header-transparent-substitute"></div>
      <div class="kingster-page-title-overlay"></div>
      <div class="kingster-page-title-bottom-gradient"></div>
      <div class="kingster-page-title-container container">
        <div class="kingster-page-title-content">
          <div class="kingster-page-caption">Gallery</div>
          <h1 class="kingster-page-title">Photo Gallery</h1>
        </div>
      </div>
    </div>

    <div class="kingster-breadcrumbs">
      <div class="kingster-breadcrumbs-container container">
        <div class="kingster-breadcrumbs-item">
          <span><a href="../index.php" class="home"><span>Home</span></a></span>
          &gt; <span><a><span>Photo Gallery</span></a></span>
        </div>
      </div>
    </div>

    <section id="about">
      <div class="container mt-0 pb-30 pt-0">
        <div class="section-content">
          <div class="row mt-20">
            <div class="col-md-12 mb-sm-20">
              <div style="background-color: #eee; padding: 20px;">
                <div class="gdlr-core-pbf-sidebar-content gdlr-core-column-45 gdlr-core-pbf-sidebar-padding gdlr-core-line-height gdlr-core-column-extend-left">
                  <div class="gdlr-core-pbf-background-wrap" style="background-color: #f7f7f7;"></div>
                  <div class="gdlr-core-pbf-sidebar-content-inner">
                    <div class="gdlr-core-course-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-course-style-list-info" style="padding-left: 0; padding-right: 0;">
                      <div class="gdlr-core-course-item-list" style="background-color: #fff; padding: 10px;">
                        <h3 class="gdlr-core-course-item-title">
                          <span class="gdlr-core-course-item-id gdlr-core-skin-caption">PHOTO GALLERY</span>
                        </h3>
                        <div class="gdlr-core-course-item-info-wrap">
                          <div class="gdlr-core-course-item-info albumview" id="album-container">
                            <div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i> Loading albums...</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>                              
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include('../include/footer.php')?>
  <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>
</div>

<script src="../js/custom.js"></script>
<script>
  let previousHtml = '';

  function loadAlbums() {
    $.ajax({
      url: 'ajax_index.php',
      type: 'GET',
      success: function (data) {
        if (data.trim() !== previousHtml.trim()) {
          previousHtml = data.trim();
          const albumContainer = document.getElementById('album-container');
          albumContainer.innerHTML = previousHtml;
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
        document.getElementById('album-container').innerHTML =
          '<p class="text-danger text-center">Failed to load albums.</p>';
      }
    });
  }

  $(document).ready(function () {
    loadAlbums(); 
    setInterval(loadAlbums, 3000); 
  });
</script>

</body>
</html>
