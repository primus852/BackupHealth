<div class="row">
    <div class="col-3">
        <select
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Type"
                data-col="<?php echo $sc->encrypt('type') ;?>"
        >
            <option value="ftp">FTP</option>
            <option value="sftp">SFTP</option>
        </select>
    </div>
    <div class="col-3">
        <input
                placeholder="URL / IP"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="url"
                data-col="<?php echo $sc->encrypt('url') ;?>"
        />
    </div>
    <div class="col-3">
        <input
                placeholder="Port"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Port"
                data-col="<?php echo $sc->encrypt('port') ;?>"
        />
    </div>
    <div class="col-3">
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
<!-- trigger to check mysql connection before save -->
<span class="trigger-special" data-action="sftp_connection"></span>
<div class="row">
    <div class="col-4">
        <span style="font-style: italic;">*optional</span>
    </div>
</div>