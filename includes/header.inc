<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Backup::Health</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- GOOGLE FONTS -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,100,300,500"/>

    <!-- LOAD PLUGIN CSS FILES -->
    <link rel="stylesheet" type="text/css" href="/assets/css/reset.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/animate.css/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/noty/lib/noty.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/tooltipster/dist/css/tooltipster.bundle.min.css">

    <!-- OWN CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/overrides.min.css">

</head>
<body>

<aside id="sidebar" class="nano">
    <div class="nano-content" id="pScrollerMenu">
        <div class="logo-container"><span class="logo fa fa-envelope"></span>BHealth</div>
        <div class="menu-segment">
            <ul>
                <li>
                    <div class="title" style="float:left;"><p id="startClock"></p> (KW 43)</div>
                </li>
            </ul>
        </div>
        <br/>
        <div class="separator"></div>
        <menu class="menu-segment">
            <ul>
                <li id="contentOverview">
                    <a href="/index.php" class="mLink">
                        <i class="fa fa-list"></i> Overview
                    </a>
                </li>
            </ul>
        </menu>
        <div class="separator"></div>
        <menu class="menu-segment">
            <ul>
                <li>
                    <a href="#" class="mLink toggleTree">
                        <i class="fa fa-chevron-right"></i> Settings
                    </a>
                    <ul class="treeMenu">
                        <li id="contentSettingsProjects" class="treeItem">
                            <a href="/settingsProjects.php" class="mLink">
                                <i class="fa fa-cogs"></i> All Projects
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </menu>
        <div class="separator"></div>
        <div class="bottom-padding"></div>
    </div>
</aside>