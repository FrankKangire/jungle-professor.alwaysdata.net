<?php
session_start();
if(!isset($_SESSION["user_id"])){ header("Location: login.php"); exit(); }
require "connection.php";
$user_id = $_SESSION["user_id"];
if(!is_dir("uploads")) mkdir("uploads", 0777, true);
$result = mysqli_query($conn, "SELECT * FROM documents WHERE user_id = '$user_id'");
$documents = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Jungle Professor</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<!-- data-jp-bg="general" = general background images on dashboard -->
<body data-jp-bg="general">
    <?php $currentPage = 'welcome';
    include "navbar.php"; ?>

    <div class="jp-dashboard-header">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! 🌿</h1>
        <p style="color:#a9dfbf;">Manage your files or start a new adventure.</p>
        <a href="server.php" class="btn btn-jp-primary me-2 mt-2">
            🎮 GO TO GAME ROOM
        </a>
        <a href="view_history.php" class="btn btn-jp-secondary mt-2">
            📜 VIEW GAME HISTORY
        </a>
    </div>

    <!--<div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="jp-card p-4">
                    <h5>📁 Upload Document</h5>
                    <form action="upload_logic.php" method="post" 
                          enctype="multipart/form-data">
                        <div class="mb-3 mt-3">
                            <input type="file" name="document" 
                                   class="form-control jp-input" required>
                        </div>
                        <button type="submit" name="submit" 
                                class="btn btn-jp-primary w-100">
                            Upload Now
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="jp-card p-4">
                    <h5>📄 Your Documents</h5>
                    <table class="table table-dark table-hover mt-3">
                        <thead>
                            <tr style="color:#2ecc71;">
                                <th>File Name</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($documents)): ?>
                                <tr>
                                    <td colspan="2" class="text-center" 
                                        style="color:#a9dfbf;">
                                        No documents uploaded yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($doc['document_name']); ?></td>
                                    <td>
                                        <a href="<?php echo $doc['document_path']; ?>" 
                                           class="btn btn-sm btn-jp-secondary" 
                                           target="_blank">Open</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>-->
    <script src="bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/slideshow.js"></script>
</body>
</html>
