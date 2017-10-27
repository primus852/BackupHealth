String.prototype.escapeHTML = function () {
    return (
        this.replace(/>/g, '&gt;').replace(/</g, '&lt;').replace(/"/g, '&quot;')
    );
};

var cols = {},
    messageIsOpen = false;

$(function () {

    startTime(1);

    $('input').attr('autocomplete', 'off');

    // Bind the event.
    $(window).hashchange(function () {

        var hash = location.hash.replace(/^#/, '');
        if (hash === '') {
            $('.trigger-message-close').trigger('click');
        }

    });

    /* ------- Init Perfect Scrollbar ------- */
    initScrollbar('#perfectScrollbar');

    /* ------- Trigger the hash check on page load ------- */
    $(window).hashchange();


    /* ------- Apply active class menu & toggle submenu ------- */
    var $curMenu = $('#' + GetNav);
    if (!$curMenu.is(':visible')) {
        $curMenu.parent().prev('a').trigger('click');
    }
    $curMenu.addClass('active');


    /* ------- Sidebar & MainWindow ------- */
    initOverlays();

    /* ------- Init Hashwatcher ------- */
    detectHash();


});

function initOverlays() {
    cols.showOverlay = function () {
        $('body').addClass('show-main-overlay');
    };
    cols.hideOverlay = function () {
        $('body').removeClass('show-main-overlay');
    };


    cols.showMessage = function () {
        $('body').addClass('show-message');
        messageIsOpen = true;
    };
    cols.hideMessage = function () {
        $('body').removeClass('show-message');
        messageIsOpen = false;
    };


    cols.showSidebar = function () {
        $('body').addClass('show-sidebar');
    };
    cols.hideSidebar = function () {
        $('body').removeClass('show-sidebar');
    };

    $('.trigger-toggle-sidebar').on('click', function () {
        cols.showSidebar();
        cols.showOverlay();
    });


    $('.trigger-message-close').on('click', function () {

        var $mb = $(this).closest('.messageFly');

        if ($('.messageFly').length <= 1) {
            cols.hideMessage();
            $mb.remove();
            history.pushState("", document.title, window.location.pathname);
        } else {
            $mb.animate({left: '9999px'}, function () {
                $mb.remove();
            });
        }

    });

    // When you click the overlay, close everything
    $('#main > .overlay').on('click', function () {
        cols.hideOverlay();
        cols.hideMessage();
        cols.hideSidebar();
    });
}

function detectHash() {

    var hash = window.location.hash;
    if (!hash.length) {
        return false;
    }

    $("[data-hash=" + hash.replace('#', '') + "]").trigger('click');

}

/* Remove Item from List */
$(document).on('click', '.removeFromList', function (e) {

    e.preventDefault();
    var $btn = $(this);

    /* Check that we have all the fields we need */
    /* Table */
    var $table = $btn.attr('data-table');
    if (typeof $table === 'undefined' || !$table.length) {
        openNoty('error', 'No \'data-table\' attribute found, please specify it when creating the table row.');
        return false;
    }

    /* ID */
    var $id = $btn.attr('data-id');
    if (typeof $id === 'undefined' || !$id.length) {
        openNoty('error', 'No \'data-id\' attribute found, please specify it when creating the table row.');
        return false;
    }

    /* Endpoint */
    var $endpoint = $btn.attr('data-endpoint');
    if (typeof $endpoint === 'undefined' || !$endpoint.length) {
        openNoty('error', 'No \'data-endpoint\' attribute found, please specify it when creating the table row.');
        return false;
    }

    var n = new Noty({
        text: 'Do you want to remove entry #' + $btn.attr('data-id') + '?',
        type: 'warning',
        buttons: [
            Noty.button('YES', 'btn btn-success pointer', function () {
                $.ajax({
                    type: "GET",
                    url: 'ajax/requests.php',
                    data: {
                        endpoint: $endpoint,
                        values: {
                            table: $table,
                            id: $id
                        }
                    },
                    success: function (data) {
                        if (data.result === 'success') {
                            if (typeof data.extra.action !== 'undefined' && data.extra.action === 'remove') {
                                $('#row_' + data.extra.id).remove();
                            }
                        }else{
                            openNoty(data.result, data.message);
                        }
                        n.close();
                    },
                    fail: function (err) {
                        openNoty('error', 'Failed to delete row!');
                        n.close();
                    }
                });
            }),
            Noty.button('NO', 'btn btn-danger pointer', function () {
                n.close();
            })
        ]
    }).show();


});

/* Toggle Treview */
$(document).on('click', '.toggleTree', function (e) {
    e.preventDefault();

    var $items = $(this).next('.treeMenu');
    var $icon = $(this).children('i');
    if ($items.is(':visible')) {
        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
    } else {
        $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
    }
    $items.toggle(200);

});

/* Click on any Listitem */
$(document).on('click', '.clickable', function (e) {

    e.preventDefault();
    window.location.hash = $(this).attr('data-hash');

    var $uId = Math.floor((Math.random() * 10000) + 1);

    $('body').append('<div class="messageFly" id="' + $uId + '"></div>');

    var $message = $('#' + $uId);
    $message.show();

    loadDetails($(this), $uId);

    if (messageIsOpen) {
        if (!$(this).hasClass('innerMessage')) {
            cols.hideMessage();
        }
        setTimeout(function () {
            cols.showMessage();
        }, 300);
    } else {
        cols.showMessage();
    }
    cols.showOverlay();


});


function loadDetails(trigger, id) {

    /* DIV */
    var $message = $('#' + id);
    $message.html('<i class="fa fa-spin fa-spinner fa-4x"></i>');

    /* BackupHealth Call */
    $.get(trigger.attr('href')).done(function (data) {
        $message.html(data);

        initOverlays();
        initScrollbar('.subScroll');
    });
}

$(document).on('click', '#updateEntry', function (e) {

    e.preventDefault();

    var $btn = $(this);
    var $html = $btn.html();
    if ($btn.hasClass('disabled')) {
        return false;
    }

    $btn.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i>');

    var sendData = [];
    $('.sendValueUpdate').each(function (i, v) {
        var dataName = $(v).attr('data-name');
        var dataVal = $(v).val();
        var dataType = $(v).attr('type');
        var dataRequired = $(v).attr('data-required');
        var dataCol = $(v).attr('data-col');
        var elem = {
            name: dataName,
            val: dataVal,
            type: dataType,
            required: dataRequired,
            col: dataCol
        };
        sendData.push(elem);
    });

    $.ajax({
        type: "GET",
        url: '/ajax/requests.php',
        data: {
            values: sendData,
            endpoint: $btn.attr('data-endpoint'),
            table: $btn.attr('data-table'),
            id: $btn.attr('data-id')
        },
        success: function (data) {
            openNoty(data.result, data.message);
            if (data.result === 'success') {

                if (typeof data.extra.fields === 'undefined') {
                    openNoty('error', 'Missing \'fields\' in response, please check the correct format first.');
                    return false;
                }

                /* Append values to Row */
                $.each(data.extra.fields, function (i, v) {
                    $('#field_'+v.field+'_'+data.extra.id).html(v.value);
                });



            }
        },
        fail: function (err) {
            openNoty('error', 'failed to save entry, please try again!');
        },
        complete: function () {
            $btn.removeClass('disabled').html($html);
        }
    });


});

$(document).on('click', '#addEntry', function (e) {

    e.preventDefault();
    addEntry($(this));

});

function addEntry(btn) {

    var text = '' +
        '<div class="row">\n    ' +
        '   <div class="col-12">\n        ' +
        '       <div class="card cardTransparent">\n            ' +
        '           <div class="card-body">\n                ' +
        '               <div class="row">\n                    ' +
        '                   <div class="col-12">\n                        ' +
        '                       <h4 class="card-title">' + btn.attr('data-add-heading') + '</h4>\n                        ' +
        '                       <h6 class="card-subtitle mb-2 text-muted">&nbsp;</h6>\n                    ' +
        '                   </div>\n                ' +
        '               </div>\n                ' +
        '               <div class="row">\n                    ' +
        '                   <div class="col-12" id="loadForm">\n                        ' +
        '                       <i class="fa fa-spin fa-spinner"></i>\n                    ' +
        '                   </div>\n                ' +
        '               </div>\n            ' +
        '           </div>\n        ' +
        '       </div>\n    ' +
        '   </div>\n' +
        '</div>\n';

    var n = new Noty({
        layout: 'center',
        text: text,
        type: 'alert',
        timeout: false,
        theme: 'mint',
        modal: true,
        closeWith: ['button'],
        buttons: [
            Noty.button('Save', 'btn btn-success btn-sm rounded-0 btn-block pointer saveModal', function () {

                var $btnId = $(this)[0]['id'];
                var $btn = $('#' + $btnId);
                var $html = $btn.html();
                if ($btn.hasClass('disabled')) {
                    return false;
                }

                $btn.addClass('disabled').html('<i class="fa fa-spin fa-spinner"></i>');

                var sendData = [];
                $('.sendValue').each(function (i, v) {
                    var dataName = $(v).attr('data-name');
                    var dataVal = $(v).val();
                    var dataType = $(v).attr('type');
                    var dataRequired = $(v).attr('data-required');
                    var dataCol = $(v).attr('data-col');
                    var elem = {
                        name: dataName,
                        val: dataVal,
                        type: dataType,
                        required: dataRequired,
                        col: dataCol
                    };
                    sendData.push(elem);
                });

                $.ajax({
                    type: "GET",
                    url: '/ajax/requests.php',
                    data: {
                        values: sendData,
                        endpoint: btn.attr('data-endpoint'),
                        table: btn.attr('data-table')
                    },
                    success: function (data) {
                        openNoty(data.result, data.message);
                        if (data.result === 'success') {
                            var $tBody = $('#' + btn.attr('data-tbody-id'));
                            if (typeof data.extra.fields === 'undefined') {
                                openNoty('error', 'Missing \'fields\' in response, please check the correct format first.');
                                return false;
                            }

                            var $row = $('<tr id="row_' + data.extra.id + '"></tr>');

                            /* Append ID to Row */
                            $row.append('<td>' + data.extra.id + '</td>');

                            /* Append values to Row */
                            $.each(data.extra.fields, function (i, v) {

                                if(v.field !== 'project_id') {

                                    if (v.type === 'password') {
                                        $row.append('<td>***</td>');
                                    } else if (v.type === 'checkbox') {
                                        if (v.value === 'true') {
                                            $row.append('<td><i class="fa fa-check"></i></td>');
                                        } else {
                                            $row.append('<td><i class="fa fa-remove"></i></td>');
                                        }
                                    } else {
                                        $row.append('<td>' + v.value + '</td>');
                                    }
                                }
                            });

                            /* Append Action Butons to Row */
                            $row.append('<td>&nbsp;</td>');

                            $tBody.append($row);
                        }

                        n.close();
                    },
                    fail: function (err) {
                        openNoty('error', 'failed to save entry, please try again!');
                    },
                    complete: function () {
                        $btn.removeClass('disabled').html($html);
                    }
                });


            }),
            Noty.button('Close', 'btn btn-danger btn-sm rounded-0 btn-block pointer', function () {
                n.close();
            })
        ]
    }).on('afterShow', function () {

        var id = 'none';
        if (typeof btn.attr('data-id') !== typeof undefined && btn.attr('data-id') !== false) {
            id = btn.attr('data-id');
        }

        $.ajax({
            type: "GET",
            url: btn.attr('data-url'),
            data: {
                template: btn.attr('data-template'),
                id: id
            },
            success: function (data) {
                $('#loadForm').html(data);
            },
            fail: function (err) {
                openNoty('error', 'Error loading template, please try again.');
                n.close();
            }
        });
    }).show();

}


/* Noty Function
 * type = {alert, success, error, warning, information}
 */
function openNoty(type, text) {
    new Noty({
        layout: 'topRight',
        text: text,
        type: type,
        timeout: 3000,
        buttons: false
    }).show();
}

function initScrollbar(selector) {
    if ($(selector).length) {
        var main = new PerfectScrollbar(selector);
    }

    /* Always init the left menu */
    if ($("#pScrollerMenu").length) {
        var side = new PerfectScrollbar("#pScrollerMenu");
    }

}

/* Display Clock upon start */
function startTime(autostart) {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    $('#startClock').html("@" + h + ":" + m + ":" + s + " Uhr");
    var t = setTimeout(startTime, 1000);

}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i
    }
    return i;
}
