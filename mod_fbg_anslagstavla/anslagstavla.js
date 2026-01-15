var anslagUrl = '/api/anslagstavla/anslagstavla.php';
var anslagID = 0;

var noticetype = [];

noticetype["1"] = { "title": "Protokoll", "icon": "file-text" };
noticetype["2"] = { "title": "Kungörelse", "icon": "comment" };
noticetype["3"] = { "title": "Underrättelse", "icon": "info" };

function defer(method) { // Invänta jQuery
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function () { defer(method) }, 50);
    }
}

function ladda() {

    $('#tabell').DataTable({
        dom: 'Bfrtip',
        buttons: {
            dom: {
                button: {
                    tag: 'button',
                    className: 'uk-button uk-button-default uk-button-small'
                }
            },
            buttons: [{
                extend: 'searchPanes',
                text: 'Filtrera'
            },
            {
                extend: 'excel',
                text: '<span uk-icon="download"></span> Excel'
            },
            {
                extend: 'colvis',
                text: '<span uk-icon="table"></span> Kolumner'
            },
            {
                text: '<span uk-icon="refresh"></span>',
                action: function (e, dt, node, config) {
                    // dt.clear().draw();
                    dt.ajax.reload();
                }
            }
            ]
        },
        "order": [[4, "desc"]],
        "pageLength": 25,
        "ajax": anslagUrl,
        "language": {
            "url": "/fbg_apps/js/framework/datatables/sv-SE.json"
        },
        "columns": [
            {
                "data": "title",
                searchPanes: {
                    show: false,
                }
            },
            {
                "data": "noticetype",
                "className": "uk-text-center",
                render: {
                    _: function (data, type, row) {


                        var outputDisplay = '';

                        if (data) {
                            outputDisplay = '<span uk-icon="icon: ' + noticetype[data]['icon'] + '" uk-tooltip="title: ' + noticetype[data]['title'] + '"></span>';
                        }

                        if (data && type != 'display') {
                            return noticetype[data]['title'];
                        }
                        return outputDisplay;
                    }, sp: function (data, type, row) {

                        var output = '';
                        if (data) {
                            output = noticetype[data]['title'];
                        }

                        return output;
                    }
                }, searchPanes: {
                    show: true,
                    orthogonal: 'sp'
                }
            },
            {
                "data": "state",
                "className": "uk-text-center",
                render: {
                    _: function (data, type, row) {
                        var outputDisplay = '';

                        if (data == 0) {
                            outputDisplay = '<span uk-icon="icon: close" uk-tooltip="title: Avpublicerad"></span>';
                        }

                        if (data == 1) {
                            outputDisplay = '<span uk-icon="icon: check" uk-tooltip="title: Publicerad"></span>';
                        }

                        if (data == 2) {
                            outputDisplay = '<span uk-icon="icon: folder" uk-tooltip="title: Arkiverad"></span>';
                        }

                        return outputDisplay;
                    }, sp: function (data, type, row) {

                        var output = '';
                        if (data == 0) {
                            output = 'Avpublicerad';
                        }

                        if (data == 1) {
                            output = 'Publicerad';
                        }

                        if (data == 2) {
                            output = 'Arkiverad';
                        }

                        return output;
                    }
                }, searchPanes: {
                    show: true,
                    orthogonal: 'sp'
                }
            },
            {
                "data": "created",
                "className": "dt-nowrap",
                "defaultContent": "<i>Ej valt</i>",
                "visible": false,
                render: function (data, type, row) {
                    if (row['created'] && type === 'display') {
                        return row['created'].substr(0, 10);
                    } else {
                        return data;
                    }

                }
            },
            {
                "data": "modified",
                "className": "dt-nowrap",
                "defaultContent": "<i>Ej valt</i>",
                render: function (data, type, row) {
                    if (row['modified'] && type === 'display') {
                        return row['modified'].substr(0, 10);
                    } else {
                        return data;
                    }

                }
            },
            {
                "data": "noticedatemeeting",
                "className": "dt-nowrap",
                "visible": false,
                "defaultContent": "<i>Ej valt</i>",
                render: function (data, type, row) {
                    if (row['noticedatemeeting']) {
                        return row['noticedatemeeting'].substr(0, 10);
                    }

                }
            },
            {
                "data": "noticedateadjusted",
                "visible": false,
                "className": "dt-nowrap",
                "defaultContent": "<i>Ej valt</i>",
                render: function (data, type, row) {
                    if (row['noticedateadjusted']) {
                        return row['noticedateadjusted'].substr(0, 10);
                    }

                }
            },
            {
                "data": "noticedateposted",
                "visible": false,
                "className": "dt-nowrap",
                "defaultContent": "<i>Ej valt</i>",
                render: function (data, type, row) {
                    if (row['noticedateposted']) {
                        return row['noticedateposted'].substr(0, 10);
                    }

                }
            },
            {
                "data": "noticedateremoval",
                "className": "dt-nowrap",
                "visible": false,
                "defaultContent": "<i>Ej valt</i>",
                render: function (data, type, row) {
                    if (row['noticedateremoval']) {
                        return row['noticedateremoval'].substr(0, 10);
                    }

                }
            },
            {
                "data": "noticedocumentlocation",
                "visible": false,
                searchPanes: {
                    show: true,
                }
            },
            { "data": "noticecontactperson" },
            {
                "data": "noticecontactemail",
                "visible": false,
                searchPanes: {
                    show: false,
                }
            },
            {
                "data": "noticeattachment",
                "visible": false,
                searchPanes: {
                    show: false,
                }
            },
            {
                "data": "noticelink",
                "visible": false,
                searchPanes: {
                    show: false,
                }
            }
        ]
    });

    console.log('Klar');

    jQuery('#btn-save').on('click', function () {
        var formData = jQuery("#form-notice").serializeObject();
        var method = 'POST';
        var table = $('#tabell').DataTable();

        if (anslagID != 0) {
            method = 'PATCH';
        }

        formData.noticeID = anslagID;

        console.log(formData);

        fetch(anslagUrl, {
            method: method, // or 'PUT'
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (data.result.id > 0) {
                    UIkit.notification({ message: 'Anslaget sparat', status: 'success' });
                    UIkit.modal('#fbg-modal').hide();
                } else {
                    UIkit.notification({ message: data.result.errors[0].title, status: 'warning' });
                }

                table.ajax.reload();
            })
            .catch((error) => {
                console.error('Error:', error);
                UIkit.notification({ message: 'Något gick fel', status: 'danger' });
                table.ajax.reload();
            });

    });

    jQuery('#btn-addnotice').on('click', function () {

        anslagID = 0;

        jQuery('#fbg-modal > div > div.uk-modal-header > h2').html('Nytt anslag');
        jQuery('#form-title').val('');
        jQuery('#form-noticedatemeeting').val('');
        jQuery('#form-noticedateadjusted').val('');
        jQuery('#form-noticedateposted').val('');
        jQuery('#form-noticedateremoval').val('');
        jQuery('#form-noticedocumentlocation').val('');
        jQuery('#form-noticecontactemail').val(userEmail);
        jQuery('#form-noticecontactperson').val(userName);
        jQuery('#form-noticeattachment').val('');
        jQuery('#form-noticelink').val('');

        jQuery('input:radio[name=form-type]').val([1]);
        jQuery('input:radio[name=form-published]').val([1]);

        UIkit.modal('#fbg-modal').show();

    });

    function setDateFormat(date) {
        DateObj = new Date(date);
        var day = DateObj.getDate();
        var month = DateObj.getMonth() + 1;
        var fullYear = DateObj.getFullYear().toString();
        var setformattedDate = '';
        setformattedDate = fullYear + '-' + getDigitToFormat(month) + '-' + getDigitToFormat(day);
        return setformattedDate;
    }

    $("#form-noticedateposted").change(function () {

        var startDateVal = $("#form-noticedateposted").val();
        var ModifyInDate = new Date(setDateFormat(startDateVal));
        var NewDate = ModifyInDate.setDate(ModifyInDate.getDate() + parseInt(21));
        NewDate = new Date(NewDate);
        if (NewDate != "Invalid Date" && jQuery('#form-noticedateremoval').val() == '')
            $("#form-noticedateremoval").val(setDateFormat(NewDate));
    });
    function getDigitToFormat(val) {

        if (val < 10) {
            val = '0' + val;
        }

        return val.toString();
    }


    jQuery('#tabell tbody').on('click', 'tr', function () {


        var table = $('#tabell').DataTable();
        var data = table.row(jQuery(this)).data();

        anslagID = data.id;

        console.log(data);

        jQuery('#fbg-modal > div > div.uk-modal-header > h2').html('ID: ' + data.id);
        jQuery('#form-title').val(data.title);
        jQuery('#form-noticedatemeeting').val(data.noticedatemeeting);
        jQuery('#form-noticedateadjusted').val(data.noticedateadjusted);
        jQuery('#form-noticedateposted').val(data.noticedateposted);
        jQuery('#form-noticedateremoval').val(data.noticedateremoval);
        jQuery('#form-noticedocumentlocation').val(data.noticedocumentlocation);
        jQuery('#form-noticecontactemail').val(data.noticecontactemail);
        jQuery('#form-noticecontactperson').val(data.noticecontactperson);
        jQuery('#form-noticeattachment').val(data.noticeattachment);
        jQuery('#form-noticelink').val(data.noticelink);

        if (data.noticetype) {
            jQuery('input:radio[name=form-type]').val([data.noticetype]);
        }

        if (data.state || data.state == 0) {
            jQuery('input:radio[name=form-published]').val([data.state]);
        }


        // jQuery('#fbg-modal > div > div.uk-modal-body').html('');
        // jQuery('#fbg-modal > div > div.uk-modal-body').append('<p>'+data.noticedateposted+' - '+data.noticedateremoval+'</p>');


        UIkit.modal('#fbg-modal').show();



    });
}

defer(function () { // Invänta jQuery
    jQuery(document).ready(function () {

        ladda();


    });
});