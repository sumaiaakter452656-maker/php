<?php
// filepath: d:\xampp-82\htdocs\php-prectice\1_oct\auth\dashboard.php
session_start();

$userName = htmlspecialchars($_SESSION['username'] ?? 'Your account', ENT_QUOTES, 'UTF-8');
$initial = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <a class="brand" href="dashboard.php">
                <span class="brand-mark">D</span>
                <span>Deskflow</span>
            </a>

            <p class="nav-label">WORKSPACE</p>
            <nav aria-label="Main navigation">
                <a class="nav-link active" href="dashboard.php"><span>▦</span> Overview</a>
                <a class="nav-link" href="#"><span>▤</span> Projects</a>
                <a class="nav-link" href="#"><span>◷</span> Activity</a>
                <a class="nav-link" href="#"><span>⚙</span> Settings</a>
            </nav>

            <div class="sidebar-bottom">
                <div class="help-card">
                    <strong>Need a hand?</strong>
                    <p>Visit our help center for tips and answers.</p>
                    <a href="#">Visit help center →</a>
                </div>
                <a class="nav-link logout" href="logout.php"><span>↪</span> Sign out</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="breadcrumb">Workspace <span>/</span> Overview</div>
                <div class="profile">
                    <div class="avatar" aria-hidden="true"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></div>
                    <span><?= $userName ?></span>
                </div>
            </header>

            <section class="page-heading">
                <div>
                    <p class="eyebrow">YOUR WORKSPACE</p>
                    <h1>Good to see you, <?= $userName ?>.</h1>
                    <p class="subtitle">Here’s what’s happening with your workspace today.</p>
                </div>
                <a class="primary-button" href="#">＋ <span>New project</span></a>
            </section>

            <section class="stats-grid" aria-label="Workspace summary">
                <article class="stat-card">
                    <div class="stat-top"><span>Active projects</span><span class="stat-icon purple">▦</span></div>
                    <strong>12</strong>
                    <p><span class="positive">↑ 8.2%</span> from last month</p>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span>Tasks completed</span><span class="stat-icon green">✓</span></div>
                    <strong>48</strong>
                    <p><span class="positive">↑ 12.5%</span> from last month</p>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span>Team members</span><span class="stat-icon orange">♙</span></div>
                    <strong>8</strong>
                    <p>Across <span class="highlight">4 teams</span></p>
                </article>
                <article class="stat-card">
                    <div class="stat-top"><span>Upcoming deadlines</span><span class="stat-icon blue">◷</span></div>
                    <strong>3</strong>
                    <p>Next one in <span class="highlight">2 days</span></p>
                </article>
            </section>

            <section class="content-grid">
                <article class="panel projects-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Recent projects</h2>
                            <p>Keep track of your team’s latest work.</p>
                        </div>
                        <a class="text-link" href="#">View all →</a>
                    </div>

                    <div class="project-list">
                        <div class="project-row">
                            <span class="project-symbol violet">W</span>
                            <div class="project-info"><strong>Website redesign</strong><span>Updated 2 hours ago</span></div>
                            <div class="progress-wrap"><span>78%</span><div class="progress"><i style="width: 78%"></i></div></div>
                            <span class="status in-progress">In progress</span>
                        </div>
                        <div class="project-row">
                            <span class="project-symbol mint">M</span>
                            <div class="project-info"><strong>Mobile app launch</strong><span>Updated yesterday</span></div>
                            <div class="progress-wrap"><span>52%</span><div class="progress"><i style="width: 52%"></i></div></div>
                            <span class="status in-progress">In progress</span>
                        </div>
                        <div class="project-row">
                            <span class="project-symbol peach">B</span>
                            <div class="project-info"><strong>Brand guidelines</strong><span>Updated 3 days ago</span></div>
                            <div class="progress-wrap"><span>100%</span><div class="progress"><i style="width: 100%"></i></div></div>
                            <span class="status completed">Completed</span>
                        </div>
                    </div>
                </article>

                <article class="panel activity-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Recent activity</h2>
                            <p>Latest updates from your workspace.</p>
                        </div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot purple-dot"></span>
                        <div><strong>Project updated</strong><p>Website redesign progress changed</p><time>2 hours ago</time></div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot green-dot"></span>
                        <div><strong>Task completed</strong><p>Homepage wireframes approved</p><time>Yesterday</time></div>
                    </div>
                    <div class="activity-item">
                        <span class="activity-dot orange-dot"></span>
                        <div><strong>New team member</strong><p>Jordan joined the design team</p><time>Yesterday</time></div>
                    </div>
                </article>
            </section>
        </main>
    </div>
</body>
</html>