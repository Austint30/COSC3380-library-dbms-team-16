<style>
    .library-headerbar {
        background-color: #1e8b1e !important;
        box-shadow: 0 1px 7px 1px rgba(0,0,0,0.45);
        -webkit-box-shadow: 0 1px 7px 1px rgba(0,0,0,0.45);
        -moz-box-shadow: 0 1px 7px 1px rgba(0,0,0,0.45);
    }
    .library-headerbar .navbar-nav {
      flex-direction: row;
      gap: 1rem;
    }
</style>
<?php
  $pageName = basename($_SERVER['PHP_SELF']);
  include 'require-signin.php';
  
  $stmt = sqlsrv_query($conn, "SELECT Account.Type FROM library.library.Account WHERE Account.[User ID]=$cookie_userID");
  if( $stmt === false) {
    die("Failed to fetch accounts.");
}
  $userType = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC)[0];

?>
<nav class="navbar navbar-dark bg-dark library-headerbar">
  <div class="container-fluid">
    <a class="navbar-brand">Team 16 Library System</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-5">
        <!-- <li class="nav-item"> -->
          <?php
            // if ($pageName == "index.php") {
            //   echo '<a class="nav-link active" aria-current="page" href="/">Home</a>';
            // }
            // else
            // {
            //   echo '<a class="nav-link" aria-current="page" href="/">Home</a>';
            // }
          // ?>
        </li>
        <li class="nav-item">
          <?php if ($pageName == "books.php" || $pageName == "book-detail.php" || $pageName == "admin-addbooks.php") {
              echo '<a class="nav-link active" aria-current="page" href="/books.php">Books</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/books.php">Books</a>';
            }
          ?>
        </li>
        <li class="nav-item">
          <?php if ($pageName == "media.php") {
              echo '<a class="nav-link active" aria-current="page" href="/media.php">Media</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/media.php">Media</a>';
            }
          ?>
        </li>
        <li>
          <?php if ($pageName == "devices.php") {
              echo '<a class="nav-link active" aria-current="page" href="/devices.php">Devices</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/devices.php">Devices</a>';
            }
          ?>
        </li>
        <li>
          <?php if ($pageName == "held-items.php") {
              echo '<a class="nav-link active" aria-current="page" href="/held-items.php">Held Items</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/held-items.php">Held Items</a>';
            }
          ?>
        </li>
        <?php
          if ($userType == "STAFF" || $userType == "ADMIN"){
            $link = null;
            if ($pageName == 'checkout.php'){
              $link = "<a class='nav-link active' aria-current='page' href='/checkout.php'>Check Out</a>";
            }
            else
            {
              $link = "<a class='nav-link' aria-current='page' href='/checkout.php'>Check Out</a>";
            }
            echo "<li>$link</li>";
          }
        ?>
        <?php
          if ($userType == "ADMIN"){
            $link = null;
            if ($pageName == 'admin_control_panel.php'){
              $link = "<a class='nav-link active' aria-current='page' href='/admin_control_panel.php'>Admin Control</a>";
            }
            else
            {
              $link = "<a class='nav-link' aria-current='page' href='/admin_control_panel.php'>Admin Control</a>";
            }
            echo "<li>$link</li>";

            $link = null;
            if ($pageName == 'users.php'){
              $link = "<a class='nav-link active' aria-current='page' href='/users.php'>Users</a>";
            }
            else
            {
              $link = "<a class='nav-link' aria-current='page' href='/users.php'>Users</a>";
            }
            echo "<li>$link</li>";
          }
        ?>
        
      </ul>
      <form class="d-flex align-items-center" style="white-space: nowrap;" action="search.php" method="post">
        <div class="input-group me-2">
          <select class="form-select" style="width: 7rem;" name="type">
            <?php
              echo "<option value='books'";
              if ($pageName == "books.php"){
                echo "selected";
              }
              echo ">Books</option>";

              echo "<option value='media'";
              if ($pageName == "media.php"){
                echo "selected";
              }
              echo ">Media</option>";

              echo "<option value='devices'";
              if ($pageName == "devices.php"){
                echo "selected";
              }
              echo ">Devices</option>";
            ?>
          </select>
          <input class="form-control" style="width: 20rem;" type="search" placeholder="Search" aria-label="Search" name="query" required>
          <button class="btn btn-outline-light me-2" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </button>
        </div>
        <?php
          $sql = "SELECT a.[First Name], a.[Last Name] FROM library.library.Account as a WHERE a.[User ID] = ?";
          $result = sqlsrv_query($conn, $sql, array($cookie_userID));
          if ($result){
            $user = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC);
            echo "<span class='text-white'>Hello, $user[0] $user[1]!</span>";
          }
        ?>
        <a href="signout.php" class="btn btn-outline-light ms-3" >Sign out</a>
      </form>
  </div>
</nav>