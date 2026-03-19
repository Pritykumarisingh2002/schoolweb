<?php
include '../../shcjsr/dbconnect.php'; // adjust as per your structure

$albumid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$album_name = isset($_GET['a']) ? htmlspecialchars($_GET['a']) : 'Album';

$pictures = [];

if ($albumid > 0) {
    $stmt = $pdo->prepare("SELECT filename FROM album_images WHERE album_id = ? ORDER BY id ASC");
    $stmt->execute([$albumid]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($images as $img) {
        $pictures[] = '../../shcjsr/uploads/' . $img['filename'];
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="description" content="About Sacred Heart Convent School" />
<meta name="keywords" content="Sacred Heart Convent School" />
<meta name="author" content="Florebit Digital" />

<!-- Page Title -->
<title><?php echo $album_name; ?>  - Academics | Sacred Heart Convent School</title>

<!-- Favicon and Touch Icons -->
<link href="../images/favicon.png" rel="shortcut icon" type="image/png">

<!-- Stylesheet -->
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="../css/animate.css" rel="stylesheet" type="text/css">
<link href="../css/css-plugin-collections.css" rel="stylesheet"/>
<!-- CSS | menuzord megamenu skins -->
<link id="menuzord-menu-skins" href="../css/menuzord-skins/menuzord-rounded-boxed.css" rel="stylesheet"/>
<!-- CSS | Main style file -->
<link href="../css/style-main.css" rel="stylesheet" type="text/css">
<!-- CSS | Preloader Styles -->
<!-- <link href="../css/preloader.css" rel="stylesheet" type="text/css"> -->
<!-- CSS | Custom Margin Padding Collection -->
<link href="../css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
<!-- CSS | Responsive media queries -->
<link href="../css/responsive.css" rel="stylesheet" type="text/css">
<!-- CSS | Style css. This is the file where you can place your own custom css code. Just uncomment it and use it. -->
<!-- <link href="../css/style.css" rel="stylesheet" type="text/css"> -->


<!-- CSS | Theme Color -->
<link href="../css/colors/theme-skin-color-set-1.css" rel="stylesheet" type="text/css">

<!-- external javascripts -->
<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<!-- JS | jquery plugin collection for this theme -->
<script src="../js/jquery-plugin-collection.js"></script>

<?php include ('../include/extra-css.css')?>
<style>
  span{
    text-align: justify!important;
  }
    	/*ul.list{list-style: disc !important}*/
    	a.grid_item .info{
			    background: linear-gradient(to bottom, transparent, #969696)!important;
		}
		.albumview .text-center1 {
		    background: #ffc107;
		    color: #000;
		    padding: 10px;
		    text-align: center;
		    min-height: 40px;
		    font-size: 18px;
		}
		.albumview img{
		    border: 1px solid #ffc107;
	        width: 100%;
            /*height: 242px;*/
		}
		.imgbox{margin-bottom:20px;}
		.albumview img{
		    width: auto !important;
            max-height: 362px;
            max-width: 100%;
		}
		
    </style>

</head>
<body class="">
  <div id="wrapper" class="clearfix">
    
    <!-- Header -->
   <?php include('../include/header.php')?>
    
    <!-- Start main-content -->
    <div class="main-content">

      <div class="kingster-page-title-wrap  kingster-style-custom kingster-left-align" id="div_983a_0">
                <div class="kingster-header-transparent-substitute"></div>
                <div class="kingster-page-title-overlay"></div>
                <div class="kingster-page-title-bottom-gradient"></div>
                <div class="kingster-page-title-container container">
                    <div class="kingster-page-title-content" id="div_983a_1">
                        <div class="kingster-page-caption" id="div_983a_2">Photo Gallery</div>
                        <h1 class="kingster-page-title" id="h1_983a_0"><?php echo $album_name; ?></h1></div>
                </div>
            </div>
      <div class="kingster-breadcrumbs">
                <div class="kingster-breadcrumbs-container container">
                    <div class="kingster-breadcrumbs-item">
                        <span property="itemListElement" typeof="ListItem">
                            <a property="item" typeof="WebPage" title="Go to Homepage" href="../index.php" class="home">
                                <span property="name">Home</span>
                            </a>
                            <meta property="position" content="1">
                        </span>
                        &gt;
                        <span property="itemListElement" typeof="ListItem">
                            <a>
                                <span property="name">Photo Gallery</span>
                            </a>
                            <meta property="position" content="2">
                        </span>
                        &gt;
                        <span property="itemListElement" typeof="ListItem">
                            <a>
                                <span property="name"><?php echo $album_name; ?></span>
                            </a>
                            <meta property="position" content="3">
                        </span>
                    </div>
                </div>
            </div>


      <!-- Section: About -->
      <section id="about">
        <div class="container mt-0 pb-30 pt-0">
          <div class="section-content">
            <div class="row mt-20">
              <div class="col-md-12 col-md-12 mb-sm-20">
                <div style="background-color: #eee; padding: 20px;">
                <div class="gdlr-core-pbf-sidebar-content  gdlr-core-column-45 gdlr-core-pbf-sidebar-padding gdlr-core-line-height gdlr-core-column-extend-left" style="">
                    <div class="gdlr-core-pbf-background-wrap" style="background-color: #f7f7f7 ;"></div>
                    <div class="gdlr-core-pbf-sidebar-content-inner">
                        <div class="gdlr-core-pbf-element">
                            
                            <div class="gdlr-core-course-item gdlr-core-item-pdlr gdlr-core-item-pdb gdlr-core-course-style-list-info" style="padding-left: 0px; padding-right: 0px;">
                                 <div class="gdlr-core-course-item-list" style="background-color: #fff; padding: 10px;">
                                    <h3 class="gdlr-core-course-item-title"><span class="gdlr-core-course-item-id gdlr-core-skin-caption"><?php echo $album_name; ?></span><a href="index.php" class="btn btn-xs btn btn-primary pull-right" style="background-color: #cf5f24; border-color: #cf5f24;float: right;padding: 0 3px;border-radius: 6px;margin-top:10px;color:#fff;margin-bottom:10px"><b>Back to Photo Gallery <i class="fa fa-arrow-right"></i></b></a></h3>
                                    <div class="gdlr-core-course-item-info-wrap">
                                        <!--<center id="loading">-->
                                        <!--    <img src="../img/loading-blue.gif" alt="Loading albums"/>-->
                                        <!--</center>-->
                                        
                                        <!--<div class="gdlr-core-course-item-info albumview" style="display:none">-->
                                        <div class="gdlr-core-course-item-info albumview">
                                            <div class="row">
    											<?php
    											if($pictures){
    												foreach ($pictures as $key => $value) {
    												 	echo '
    												 		<div class="col-lg-4 col-md-6 imgbox wow" data-wow-offset="150">
    															<a  href="'.$value.'" data-lightbox="gallery" data-title="" class="grid_item">
    																<figure class="block-reveal">
    																	<div class="block-horizzontal"></div>
    																		<center><img src="'.$value.'" alt="School Gallery" style="width:100%;"></center>';
    																	// <div class="info">
    																	// 	<small><i class="ti-layers"></i>15 Photos</small>
    																	// 	<h3>Title Here</h3>
    																	// </div>
    																echo '</figure>
    															</a>
    														</div>
    												 	';
    												 	if(!(($key+1)%3)){
    												 	    echo '<div class="clearfix"></div>';
    												 	}
    												 }
    												}
    											?>
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
          </div>
        </div>
      </section>

    </div>
    <!-- end main-content -->
    
    
    <?php include('../include/footer.php')?>
    <a class="scrollToTop" href="../#"><i class="fa fa-angle-up"></i></a>
  </div>
  <!-- end wrapper --> 

    <!-- Footer Scripts --> 
    <!-- JS | Custom script for all pages --> 
    <script src="../js/custom.js"></script>
    <script src="../js/lightbox.js"></script>

    <script>
         $('#gallery').addClass('active');
         
        //   setTimeout(function() {
        //     $("#loading").fadeOut();
        //     $(".albumview").show();
        //   }, 1000);
    </script>
</body>
</html>