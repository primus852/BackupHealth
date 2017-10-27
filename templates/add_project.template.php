<div class="row">
    <div class="col-6">
        <input
                placeholder="Name"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Name"
                data-col="<?php echo $sc->encrypt('name') ;?>"
        />
    </div>
    <div class="col-6">
        <input
                placeholder="Description"
                class="sendValue"
                type="text"
                data-required="true"
                data-name="Description"
                data-col="<?php echo $sc->encrypt('description') ;?>"
        />
    </div>
</div>
<br/>
<div class="row">
    <div class="col-4">
        <span style="font-style: italic;">*optional</span>
    </div>
</div>