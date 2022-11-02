<?php 
  include "php/config.php";
  $new_url = "";
  if(isset($_GET)){
    foreach($_GET as $key=>$val){
      $u = mysqli_real_escape_string($conn, $key);
      $new_url = str_replace('/', '', $u);
    }
      $sql = mysqli_query($conn, "SELECT full_url FROM url WHERE shorten_url = '{$new_url}'");
      if(mysqli_num_rows($sql) > 0){
        $sql2 = mysqli_query($conn, "UPDATE url SET clicks = clicks + 1 WHERE shorten_url = '{$new_url}'");
        if($sql2){
            $full_url = mysqli_fetch_assoc($sql);
            header("Location:".$full_url['full_url']);
          }
      }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL Shortener in PHP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="./assets/css/main.css">
</head>
<body>
  <!-- navigation --> 
  <nav class="navbar fixed-top navbar-expand-md bg-white shadow-sm navbar-light  d-none d-lg-block d-xl-block">
      <div class="container">
        <div class="d-flex justify-content-between w-100">
          <a href="#">
            <img src="./assets/images/logo.png" width="100px" alt="HbtlURL logo" class="logo pt-1 img-fluid">
          </a>
          <ul class="navbar-nav">
              <li class="nav-item"><a class="nav-link px-lg-4" href="#">Login</a></li>
              <li class="nav-item"><a class="nav-link px-lg-4" href="#">Sign up</a></li>
          </ul>
        </div>
      </div>
  </nav>
  <nav class="navbar fixed-top d-block d-lg-none d-xl-none">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center w-100">
          <a href="#" aria-label="">
            <img src="./assets/images/logo.png" width="100px" alt="HbtlURL logo" class="logo img-fluid pt-1"/>
          </a>
          <a href="#" data-bs-toggle="offcanvas"data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="fa-solid fa-bars fa-2x"></i></a>
        </div>
      </div>
  </nav>

  <!--mobile nav -->
  <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
    
      <div class="offcanvas-header">
        <a href="#" class="mx-2">
          <img src="./assets/images/logo.png" width="100px" class="logo img-fluid pt-1" alt="hubURL logo"/>
        </a>
        <button type="button" class="text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-xmark fa-2x"></i>
        </button>
      </div>

      <div class="offcanvas-body">
        <ul class="navbar-nav text-center">
          <li class="nav-item"><a class="nav-link px-lg-4" href="#">Login</a></li>
          <li class="nav-item"><a class="nav-link px-lg-4" href="#">Sign up</a></li>
        </ul>
      </div>
      
  </div>  <!--End of navigation -->
  <main>
    <section class="hero-section">
      <div class="container">
        <div class="row align-items justify-content-center">
          <div class="col-12 col-md-7 col-lg-7 col-xl-7">
            <div class="hero-text">
              <h1></h1>
              <div class="wrapper">
                <form action="#" autocomplete="off">
                  <input type="text" spellcheck="false" name="full_url" placeholder="Enter or paste a long url" required>
                  <i class="url-icon fa-solid fa-link"></i>
                  <button>Shorten</button>
                </form>
                <?php
                  $sql2 = mysqli_query($conn, "SELECT * FROM url ORDER BY id DESC");
                  if(mysqli_num_rows($sql2) > 0){;
                    ?>
                      <div class="statistics">
                        <?php
                          $sql3 = mysqli_query($conn, "SELECT COUNT(*) FROM url");
                          $res = mysqli_fetch_assoc($sql3);

                          $sql4 = mysqli_query($conn, "SELECT clicks FROM url");
                          $total = 0;
                          while($count = mysqli_fetch_assoc($sql4)){
                            $total = $count['clicks'] + $total;
                          }
                        ?>
                        <span>Total Links: <span><?php echo end($res) ?></span> & Total Clicks: <span><?php echo $total ?></span></span>
                        <a href="./php/delete.php?delete=all">Clear All</a>
                    </div>
                    <div class="urls-area">
                      <div class="title">
                        <li>Shorten URL</li>
                        <li>Original URL</li>
                        <li>Clicks</li>
                        <li>Action</li>
                      </div>
                      <?php
                        while($row = mysqli_fetch_assoc($sql2)){
                          ?>
                            <div class="data">
                            <li>
                              <a href="<?php echo $domain.$row['shorten_url'] ?>" target="_blank">
                              <?php
                                if($domain.strlen($row['shorten_url']) > 50){
                                  echo $domain.substr($row['shorten_url'], 0, 50) . '...';
                                }else{
                                  echo $domain.$row['shorten_url'];
                                }
                              ?>
                              </a>
                            </li> 
                            <li>
                              <?php
                                if(strlen($row['full_url']) > 60){
                                  echo substr($row['full_url'], 0, 60) . '...';
                                }else{
                                  echo $row['full_url'];
                                }
                              ?>
                            </li> 
                          </li>
                            <li><?php echo $row['clicks'] ?></li>
                            <li><a href="./php/delete.php?id=<?php echo $row['shorten_url'] ?>">Delete</a></li>
                          </div>
                          <?php
                        }
                      ?>
                  </div>
                    <?php
                  }
                ?>
              </div>
              <div class="buttons pt-5">
                <a href="#" class="btn btn-primary">Login to try</a>
              </div>

              <div class="blur-effect"></div>
              <div class="popup-box">
                <div class="info-box">Your short link is ready. You can also edit your short link now but can't edit once you saved it.</div>
                <form action="#" autocomplete="off">
                  <label>Edit your shorten url</label>
                  <input type="text" class="shorten-url" spellcheck="false" required>
                  <i class="copy-icon fa-solid fa-copy"></i>
                  <button>Save</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-5 col-lg-4 col-xl-4 offset-xl-1">
            <div class="hero-image pt-4 pt-md-0">
              <img src="./assets/images/undraw_link_shortener_mvf6.svg" alt="">
            </div>
          </div>

        </div>
      </div>
    </section>
  </main>
  <footer>

  </footer>

  <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  <script src="./script.js"></script>

</body>
</html>

