<?php

require_once 'vendor/autoload.php';

use primus852\BackupHealth;
use primus852\SimpleCrypt;

/* Projects Connection */
$bh = new BackupHealth();
$sc = new SimpleCrypt();

/* All Projects that are active */
$projects = $bh->list_projects();

/* Close DB Connection */
$bh->close_connection();

?>

<?php require_once 'includes/header.inc'; ?>

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
                </a>All Projects
                <a class="btn btn-info btn-sm rounded-0" id="addEntry" href="#"
                   data-url="/ajax/load_template.php"
                   data-template="add_project"
                   data-tbody-id="app_projects"
                   data-endpoint="addEntry"
                   data-add-heading="Add Project"
                   data-table="<?php echo $sc->encrypt('app_projects'); ?>"
                ><i class="fa fa-plus"></i> Add Project</a>
            </h1>
        </header>
        <div id="main-nano-wrapper" class="nano">
            <div class="nano-content" id="perfectScroll">
                <div class="container-fluid">
                    <div class="row" style="padding-left:15px;">
                        <div class="col-12">
                            <?php

                            if ($projects !== null) {

                                echo $bh->display_table(
                                    $projects,
                                    array(
                                        'id' => '#',
                                        'name' => 'Name',
                                        'description' => 'Description',
                                        'Action' => array(
                                            array(
                                                'classes' => 'btn-danger removeFromList',
                                                'icon' => 'remove',
                                                'text' => 'Delete',
                                                'endpoint' => 'removeById',
                                                'template' => null,
                                                'hash' => null,
                                                'action' => 'message'
                                            ),
                                            array(
                                                'classes' => 'btn-warning clickable',
                                                'icon' => 'edit',
                                                'text' => 'Edit',
                                                'endpoint' => 'editById',
                                                'template' => 'edit_project',
                                                'hash' => 'details-project',
                                                'action' => 'edit'
                                            )
                                        ),
                                    ), 'app_projects');

                            }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
        </div>
    </main>

    <!-- CURRENT MENU ID ACTIVE -->
    <script>
        var GetNav = "contentSettingsProjects";
    </script>

<?php require_once 'includes/footer.inc'; ?>