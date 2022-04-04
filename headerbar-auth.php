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
?>
<nav class="navbar navbar-dark bg-dark library-headerbar">
  <div class="container-fluid">
    <a class="navbar-brand">Team 16 Library System</a>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-5">
        <li class="nav-item">
          <?php if ($pageName == "index.php") {
              echo '<a class="nav-link active" aria-current="page" href="/">Home</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/">Home</a>';
            }
          ?>
        </li>
        <li class="nav-item">
          <?php if ($pageName == "books.php" || $pageName == "book-detail.php") {
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
          <?php if ($pageName == "devices.php") {
              echo '<a class="nav-link active" aria-current="page" href="/devices.php">Devices</a>';
            }
            else
            {
              echo '<a class="nav-link" aria-current="page" href="/devices.php">Devices</a>';
            }
          ?>
      </ul>
      <form class="d-flex" style="white-space: nowrap;" action="search.php" method="post">
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
        <a href="signout.php" class="btn btn-outline-light" style="border: none;">Sign out</a>
      </form>
  </div>
</nav>