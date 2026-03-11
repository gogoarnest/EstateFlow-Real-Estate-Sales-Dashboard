<?php
include "php/auth/check-auth.php";
include "php/config/db.php";

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EstateFlow | Users</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="css/global.css" />
    <link rel="stylesheet" href="css/users.css" />
</head>

<body>
    <div class="page-layout">
        <aside class="sidebar">
            <div class="sidebar-top">
                <h2 class="logo">EstateFlow</h2>
                <p class="sidebar-subtitle">Real Estate Dashboard</p>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="areas.php">Areas</a></li>
                    <li><a href="compounds.php">Compounds</a></li>
                    <li><a href="units.php">Units</a></li>
                    <li><a href="add-unit.php">Add Unit</a></li>
                    <li><a href="teams.php">Teams</a></li>
                    <li><a href="users.php" class="active">Users</a></li>
                    <li><a href="php/auth/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-text">
                    <h1>Users</h1>
                    <p>Manage all available system users</p>
                </div>

                <div class="user-box">
                    <span class="user-role">Admin</span>
                    <div class="user-avatar">G</div>
                </div>
            </header>

            <section class="form-section">
                <div class="section-header">
                    <h2>Add New User</h2>
                    <p>Create and manage users across the system</p>
                </div>

                <form class="user-form" action="php/users/add-user.php" method="POST">
                    <div class="form-group">
                        <label for="user-name">User Name</label>
                        <input type="text" id="user-name" name="user-name" placeholder="Enter user name" required />
                    </div>

                    <div class="form-group">
                        <label for="user-email">User Email</label>
                        <input type="email" id="user-email" name="user-email" placeholder="Enter user email" required />
                    </div>

                    <div class="form-group">
                        <label for="user-password">User Password</label>
                        <input type="text" id="user-password" name="user-password" placeholder="Enter user password" required />
                    </div>

                    <div class="form-group">
                        <label for="user-role">User Role</label>
                        <select id="user-role" name="user-role" required>
                            <option value="" selected disabled>Select role</option>
                            <option value="admin">Admin</option>
                            <option value="leader">Team Leader</option>
                            <option value="senior">Senior</option>
                            <option value="sales">Sales</option>
                        </select>
                    </div>

                    <button type="submit" class="primary-btn">Add User</button>
                </form>
            </section>

            <section class="table-section">
                <div class="section-header">
                    <h2>Users List</h2>
                    <p>All active users will appear here</p>
                </div>

                <div class="table-wrapper">
                    <table>
                        <caption class="sr-only">List of all system users</caption>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Password</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>••••••••</td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td><button class="table-btn" type="button">Edit</button></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>