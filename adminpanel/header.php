<link rel="stylesheet" href="css/header.css">
<nav class="header">
  <div class="header-left d-flex align-items-center">
    <img src="../images/jps_logo.jpeg" alt="Logo" class="logo">
    <span class="school-name ms-2">Jamshedpur Public School, Jamshedpur</span>
  </div>
  <div class="user-info">
    <i class="fas fa-user-circle"></i>
    <?php echo htmlspecialchars($_SESSION['username']); ?>
    <a href="logout.php" class="btn btn-sm btn-warning ms-3">Logout</a>
  </div>
</nav>
