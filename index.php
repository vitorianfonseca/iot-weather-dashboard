<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: auth/login.php");
    exit();
}
// Se não houver utilizador autenticado, redirecionar para login

  $page = $_GET['page'] ?? 'dashboard';  // Página padrão
  $allowed = ['dashboard', 'sensors', 'history', 'contact'];
  if (!in_array($page, $allowed)) {
    $page = 'dashboard'; // fallback de segurança
  }
?>

<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SkyCast</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    >
    <link rel="stylesheet" href="styles/global.css" >
    <link rel="stylesheet" href="styles/style.css" >
    <link rel="stylesheet" href="styles/sensors.css" >
    <link rel="stylesheet" href="styles/history.css">
    <link rel="icon" type="image/x-icon" href="assets/other/favicon.svg" >
  </head>
  <body>
    <div class="app d-flex">
      <!-- Sidebar (desktop) -->
      <aside class="sidebar flex-column p-3" style="width: 250px">
        <div class="logo text-center mb-4">
          <img src="assets/other/logo.svg" alt="Politécnico de Leiria" class="img-fluid" >
        </div>

        <nav class="flex-grow-1">
          <ul class="nav flex-column align-items-center align-items-lg-start">
            <li class="nav-item">
              <a
                id="link-dashboard"
                data-page="dashboard.html"
                class="nav-link d-flex flex-column flex-lg-row align-items-center <?= $page === 'dashboard' ? 'active' : '' ?>"
                href="index.php?page=dashboard"
              >
                <i class="bi bi-house-door fs-5 mb-1 mb-lg-0"></i>
                <span class="d-none d-lg-inline ms-lg-2">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a
                id="link-sensors"
                data-page="sensors.php"
                class="nav-link d-flex flex-column flex-lg-row align-items-center <?= $page === 'sensors' ? 'active' : '' ?>"
                href="index.php?page=sensors"
              >
                <i class="bi bi-hdd-network fs-5 mb-1 mb-lg-0"></i>
                <span class="d-none d-lg-inline ms-lg-2">Sensors</span>
              </a>
            </li>
            <li class="nav-item">
              <a
                id="link-history"
                data-page="history.html"
                class="nav-link d-flex flex-column flex-lg-row align-items-center <?= $page === 'history' ? 'active' : '' ?>"
                href="index.php?page=history"
              >
                <i class="bi bi-map fs-5 mb-1 mb-lg-0"></i>
                <span class="d-none d-lg-inline ms-lg-2">History</span>
              </a>
            </li>
          </ul>
        </nav>

        <div class="logout mt-auto text-center text-lg-start">
          <small class="text-muted d-none d-lg-block mb-2">System</small>
          <a
            class="nav-link text-danger d-flex flex-column flex-lg-row align-items-center"
            href="auth/logout.php"
          >
            <i class="bi bi-box-arrow-left fs-5 mb-1 mb-lg-0"></i>
            <span class="d-none d-lg-inline ms-lg-2">Logout</span>
          </a>
        </div>
      </aside>

      <!-- Sidebar (mobile) -->
      <div
        class="offcanvas offcanvas-start"
        tabindex="-1"
        id="mobileSidebar"
        aria-labelledby="mobileSidebarLabel"
      >
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Close"
          ></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
          <div class="logo mb-4">
            <img src="assets/other/logo.svg" alt="Politécnico de Leiria" class="img-fluid" >
          </div>
          <nav class="flex-grow-1">
            <ul class="nav flex-column">
              <li class="nav-item mb-2">
                <a
                  id="mobile-link-dashboard"
                  data-page="dashboard.html"
                  class="nav-link active d-flex align-items-center <?= $page === 'dashboard' ? 'active' : '' ?>"
                  href="index.php?page=dashboard"
                >
                  <i class="bi bi-house-door"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item mb-2">
                <a
                  id="mobile-link-sensors"
                  data-page="sensors.html"
                  class="nav-link d-flex align-items-center <?= $page === 'sensors' ? 'active' : '' ?>"
                  href="index.php?page=sensors"
                >
                  <i class="bi bi-hdd-network"></i>
                  Sensors
                </a>
              </li>
              <li class="nav-item mb-2">
                <a
                  id="mobile-link-history"
                  data-page="history.html"
                  class="nav-link d-flex align-items-center <?= $page === 'history' ? 'active' : '' ?>"
                  href="index.php?page=history"
                >
                  <i class="bi bi-map"></i>
                  History
                </a>
              </li>
              <li class="nav-item mb-2">
                <a
                  id="mobile-link-contact"
                  data-page="contact.html"
                  class="nav-link d-flex align-items-center <?= $page === 'contact' ? 'active' : '' ?>"
                  href="index.php?page=contact"
                >
                  <i class="bi bi-calendar-event fs-5 mb-1 mb-lg-0"></i>
                  Contact
                </a>
              </li>
            </ul>
          </nav>

          <div class="logout mt-auto">
            <small class="text-muted d-block mb-2">System</small>
            <a class="nav-link text-danger d-flex align-items-center" href="#">
              <i class="bi bi-box-arrow-left me-2"></i>
              Logout account
            </a>
          </div>
        </div>
      </div>

      <div class="main-content flex-grow-1 d-flex flex-column">
        <header class="main-header">
          <div
            class="d-flex flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mobile-header"
          >
            <div class="d-flex align-items-center gap-3 flex-shrink-0">
              <button
                class="btn bg-light rounded-3 p-2 d-lg-none"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar"
              >
                <i class="bi bi-list text-muted fs-6 fs-md-5"></i>
              </button>
              <div>
                <h5 class="fw-bold mb-0"><?php echo date('F Y'); ?> <!-- Ex: April 2025 --></h5>
                <small class="text-muted"><?php echo date('l, M j, Y'); ?> <!-- Ex: Wednesday, Apr 2, 2025 --></small>
              </div>
            </div>

            <div
              class="d-flex flex-grow-1 gap-2 align-items-center justify-content-end w-100 header-actions"
            >
              <div class="position-relative w-50 w-sm-auto mobile-header-search">
                <input
                  id="searchCity"
                  type="text"
                  class="form-control ps-5 rounded-3 bg-light border-0 w-100"
                  placeholder="Search location here"
                >
                <ul
                  id="suggestions"
                  class="list-group position-absolute w-100 shadow-sm"
                  style="z-index: 999"
                ></ul>
                <i
                  class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                ></i>
              </div>
              <button class="btn bg-light rounded-3 p-2">
                <i class="bi bi-bell text-muted fs-6 fs-md-5"></i>
              </button>
            </div>
          </div>
        </header>

        <main id="main-content" class="flex-grow-1 overflow-auto">
          <section class="container-fluid">
            <h2 class="warning d-none">iot</h2>
            <?php
              // Verifica se a página é 'sensors' ou 'history' e atribui a extensão correta
              $extension = ($page === 'sensors' || $page === 'history' || $page === 'dashboard') ? 'php' : 'html';
              include "pages/$page.$extension";
            ?>
          </section>
        </main>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
