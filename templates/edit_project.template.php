<div class="header">
    <div class="row">
        <div class="col-11">
            <h1 class="page-title"><a class="icon circle-icon fa fa-remove trigger-message-close"></a>
                <?php echo isset($data) ? $data['name'] : ''; ?>
            </h1>
        </div>
        <div class="col-1 text-right">
            &nbsp;
        </div>
    </div>
</div>
<div class="messageFly-nano-wrapper nano">
    <div class="nano-content">
        <div class="message-container">
            <div class="message-box">
                <div class="message">
                    <?php if (isset($data)) { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-11">
                                                Info
                                            </div>
                                            <div class="col-1">
                                                <?php if (isset($data)) { ?>
                                                    <a href="#" class="btn btn-success btn-block btn-sm"
                                                       id="updateEntry"
                                                       data-endpoint="updateEntry"
                                                       data-id="<?php echo $data['id']; ?>"
                                                       data-table="<?php echo $sc->encrypt('app_projects'); ?>"
                                                    ><i class="fa fa-save"></i> Save</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Name</label>
                                                <input
                                                        placeholder="Name"
                                                        class="sendValueUpdate"
                                                        type="text"
                                                        value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>"
                                                        data-required="true"
                                                        data-name="Name"
                                                        data-col="<?php echo $sc->encrypt('name'); ?>"
                                                />
                                            </div>
                                            <div class="col-6">
                                                <label>Description</label>
                                                <input
                                                        placeholder="Description"
                                                        class="sendValueUpdate"
                                                        type="text"
                                                        value="<?php echo isset($data['description']) ? $data['description'] : ''; ?>"
                                                        data-required="true"
                                                        data-name="Description"
                                                        data-col="<?php echo $sc->encrypt('description'); ?>"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                MySQL
                                            </div>
                                            <div class="col-6 text-right">
                                                <a class="btn btn-success btn-sm" id="addEntry" href="#"
                                                   data-url="/ajax/load_template.php"
                                                   data-template="add_mysql"
                                                   data-tbody-id="projects_mysql"
                                                   data-endpoint="addEntry"
                                                   data-id="<?php echo isset($data['id']) ? $data['id'] : ''; ?>"
                                                   data-add-heading="Add MySQL to <?php echo isset($data['name']) ? $data['name'] : ''; ?>"
                                                   data-table="<?php echo $sc->encrypt('projects_mysql'); ?>"
                                                >
                                                    <i class="fa fa-plus"></i> Add
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <?php

                                                if (!empty($data)) {
                                                    echo $bh->display_table(
                                                        $bh->get_project_mysql($data['id']),
                                                        array(
                                                            'id' => '#',
                                                            'hostname' => 'Host',
                                                            'port' => 'Port',
                                                            'db' => 'Database',
                                                            'username' => 'Username',
                                                            'pass' => 'Password',
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
                                                                )
                                                            ),
                                                        ), 'projects_mysql');
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-12">
                                ERRROR LOADING DETAILS
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>