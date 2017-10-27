<?php

require_once 'vendor/autoload.php';

use primus852\BackupHealth;
use primus852\SimpleCrypt;

/* Projects Connection */
$bh = new BackupHealth();
$sc = new SimpleCrypt();

/* All Projects that are active */
$projects = $bh->list_projects();

require_once 'includes/header.inc';

?>

<!-- Slide in Window -->
<main id="main">
    <div class="overlay"></div>
    <header class="header">
        <h1 class="page-title">
            <a class="sidebar-toggle-btn trigger-toggle-sidebar">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
                <span class="line line-angle1"></span>
                <span class="line line-angle2"></span>
            </a>BackupHealth Overview
        </h1>
    </header>
    <div id="main-nano-wrapper" class="nano">
        <div class="nano-content" id="perfectScroll">
            <div class="container-fluid">
                <div class="row" style="padding-left:15px;">
                    <?php if ($projects !== null) { ?>
                        <?php foreach ($projects as $project) { ?>
                            <div class="col-4" style="padding-bottom:15px;">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <?php echo $project['name']; ?>
                                            </div>
                                            <div class="col-6 text-right">
                                                RUN ALL
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- PING -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h5>Ping</h5>
                                            </div>
                                        </div>
                                        <?php $pings = $bh->get_project_foreign_table($project['id'], 'ping') ?>
                                        <?php if ($pings !== null) { ?>
                                            <?php foreach ($pings as $ping) { ?>
                                                <div class="row">
                                                    <div class="col-7">
                                                        <?php echo $ping['url']; ?>
                                                    </div>
                                                    <div class="col-3 result-ping-<?php echo $ping['id']; ?>">

                                                    </div>
                                                    <div class="col-2 text-center">
                                                        <a href="/ajax/requests.php" data-endpoint="pingSite" class="btn btn-success btn-sm loadStart" data-action="ping" data-id="<?php echo $ping['id']; ?>">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <br />
                                            <?php } ?>
                                        <?php } else { ?>
                                            No Ping Test, please add them here <a href="/settingsProjects.php#details-project-<?php echo $project['id']; ?>">here</a>
                                            <br />
                                        <?php } ?>
                                        <!-- MySQL -->
                                        <div class="row">
                                            <div class="col-12">
                                                <h5>MySQL</h5>
                                            </div>
                                        </div>
                                        <?php $mysqls = $bh->get_project_foreign_table($project['id'], 'mysql') ?>
                                        <?php if ($mysqls !== null) { ?>
                                            <?php foreach ($mysqls as $mysql) { ?>
                                                <div class="row">
                                                    <div class="col-7">
                                                        <?php echo $mysql['db']; ?>
                                                    </div>
                                                    <div class="col-3 result-mysql-connect-<?php echo $mysql['id']; ?>">

                                                    </div>
                                                    <div class="col-2 text-center">
                                                        <a href="/ajax/requests.php" data-endpoint="pingMySql" class="btn btn-success btn-sm loadStart" data-action="mysql-connect" data-id="<?php echo $mysql['id']; ?>">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <br />
                                            <?php } ?>
                                        <?php } else { ?>
                                            No MySql DBs found, please add them here <a href="/settingsProjects.php#details-project-<?php echo $project['id']; ?>">here</a>
                                            <br />
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="col-12">
                            NO PROJECTS ADDED!
                            <br/>
                            <br/>
                            Please add one <a href="/settingsProjects.php">here</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <br/>
    </div>
</main>

<!-- CURRENT MENU ID ACTIVE -->
<script>
    var GetNav = "contentOverview";
</script>

<?php require_once 'includes/footer.inc'; ?>
