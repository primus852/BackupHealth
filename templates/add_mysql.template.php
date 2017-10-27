<div class="row">
    <div class="col-4">
        <input
                placeholder="Hostname"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Hostname"
                data-col="<?php echo $sc->encrypt('hostname') ;?>"
        />
    </div>
    <div class="col-4">
        <input
                placeholder="Port, set to 3306 if unknown"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Port"
                data-col="<?php echo $sc->encrypt('port') ;?>"
        />
    </div>
    <div class="col-4">
        <input
                placeholder="Database"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Database"
                data-col="<?php echo $sc->encrypt('db') ;?>"
        />
    </div>
</div>
<br />
<div class="row">
    <div class="col-4">
        <input
                placeholder="Username"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Username"
                data-col="<?php echo $sc->encrypt('username') ;?>"
        />
    </div>
    <div class="col-4">
        <input
                placeholder="Password*"
                class="sendValue"
                type="password"
                data-required="false"
                data-name="Password"
                data-col="<?php echo $sc->encrypt('pass') ;?>"
        />
    </div>
    <div class="col-4">
        <input
                placeholder="Description*"
                class="sendValue"
                type="text"
                data-required="false"
                data-name="Description"
                data-col="<?php echo $sc->encrypt('description') ;?>"
        />
    </div>
</div>
<br/>
<input
        class="sendValue"
        type="hidden"
        data-required="true"
        data-name="Project ID"
        value="<?php echo isset($id) ? $id : '';?>"
        data-col="<?php echo $sc->encrypt('project_id') ;?>"
/>
<div class="row">
    <div class="col-4">
        <span style="font-style: italic;">*optional</span>
    </div>
</div>