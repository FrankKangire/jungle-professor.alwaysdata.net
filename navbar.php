<?php if(session_status() === PHP_SESSION_NONE) session_start(); 
// $currentPage can be set by the including page to hide relevant nav items
// e.g. $currentPage = 'welcome'; before include "navbar.php";
$currentPage = $currentPage ?? '';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<nav class="navbar navbar-expand-lg shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold fs-4" href="index.php">
        🌿 <span>Jungle</span>Professor
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navRes"
            style="border-color:#27ae60;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navRes">
      <ul class="navbar-nav me-auto">

        <?php if($currentPage !== 'home'): ?>
<li class="nav-item">
    <!-- If logged in, Home goes to Dashboard. If not, goes to index -->
    <a class="nav-link" href="<?php echo $isLoggedIn ? 'welcome.php' : 'index.php'; ?>">
        🏠 Home
    </a>
</li>
<?php endif; ?>

        <?php if($isLoggedIn && $currentPage !== 'welcome'): ?>
        <li class="nav-item">
            <a class="nav-link" href="welcome.php">📊 Dashboard</a>
        </li>
        <?php endif; ?>

        <?php if($isLoggedIn && $currentPage !== 'history'): ?>
        <li class="nav-item">
            <a class="nav-link" href="view_history.php">📜 Game History</a>
        </li>
        <?php endif; ?>

        <?php if($isLoggedIn && $currentPage !== 'server'): ?>
        <li class="nav-item">
            <a class="nav-link" href="server.php">🎮 Game Room</a>
        </li>
        <?php endif; ?>

      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if($isLoggedIn): ?>
            <span class="text-light me-2">
                👤 <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
            </span>
            <a class="btn btn-jp-danger btn-sm" href="logout.php">Logout</a>
        <?php else: ?>
            <?php if($currentPage !== 'login'): ?>
                <a class="btn btn-jp-secondary btn-sm" href="login.php">Login</a>
            <?php endif; ?>
            <?php if($currentPage !== 'signup'): ?>
                <a class="btn btn-jp-primary btn-sm" href="signup.php">Sign Up</a>
            <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>