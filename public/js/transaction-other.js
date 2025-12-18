$(".select2").css("width", "100%").select2({ allowClear: true });
count_additional_deposit = 0;
$(".additional_deposit_btn").click(function () {
    cdate = new Date().toLocaleDateString("en-CA");

    $(".div_additional_deposit").append(
        '<div style="border-color:#71717B;" class="well form-group div_additional_deposit_' +
            count_additional_deposit +
            '">' +
            '<button onclick="remove_additional_deposit(' +
            count_additional_deposit +
            ')" class="btn btn-xs pull-right btn-danger"><i class="fa fa-trash"></i></button>' +
            '<label class="col-md-12 col-sm-12 col-xs-12" for="fname">Deposit Date:</label>' +
            '<div class="col-md-12 col-sm-12 col-xs-12">' +
            '<input required max="' +
            cdate +
            '" value="' +
            cdate +
            '" type="date" name="addtl_pca_deposit_date[]" class="form-control"  />' +
            "</div>" +
            '<label class="col-md-12 col-sm-12 col-xs-12" for="fname">Reference No.</label>' +
            '<div class="col-md-12 col-sm-12 col-xs-12">' +
            '<input  required  type="text"  name="addtl_pca_deposit_ref_no[]" class="form-control"  />' +
            "</div>" +
            '<label class="col-md-12 col-sm-12 col-xs-12" for="fname">Amount</label>' +
            '<div class="col-md-12 col-sm-12 col-xs-12">' +
            '<input required  step="any" type="number"  name="addtl_pca_deposit_amt[]" class="form-control" />' +
            "</div>" +
            '<label class="col-md-12 col-sm-12 col-xs-12" for="fname">Upload Proof of Deposit Slip: <br><i><small>(image only)</small></i> </label>' +
            '<div class="col-md-12 col-sm-12 col-xs-12">' +
            '<input accept="image/*" required type="file" name="addtl_pca_deposit_file[]" class="form-control" />' +
            "</div>" +
            "</div>"
    );
    count_additional_deposit++;
});
function remove_additional_deposit(cnt) {
    $(".div_additional_deposit_" + cnt).remove();
}
$(".pca_deposit_modal_btn").click(function () {
    var date = new Date();

    $('input[name="pca_deposit_date"]').val(
        date.toISOString().substring(0, 10)
    );
    $('input[name="pca_deposit_account_name"]').val("");
    $('input[name="pca_deposit_account_no"]').val("");
    $('input[name="pca_deposit_ref_no"]').val("");
    $('input[name="pca_deposit_amt"]').val("");
    $('input[name="pca_deposit_file"]').val("");
    $("#pca_deposit_form_loading").hide();
    $(".div_additional_deposit").html("");

    $.ajax({
        url: "/pca-no-details/" + btoa($('input[name="pca_deposit_no"]').val()),
        type: "GET",
        dataType: "json",
        success: function (result) {
            $.each(result, function () {
                $('input[name="pca_deposit_account_name"]').val(
                    this.account_name
                );
                $('input[name="pca_deposit_account_no"]').val(this.account_no);
                $('input[name="pca_deposit_amt_min"]').val(
                    Number(this.min_deposit)
                );
            });
        },
        error: function () {
            swal({
                icon: "error",
            });
        },
    });
});
$(".pca_deposit_form").submit(function () {
    event.preventDefault();
    document.getElementById("pca_deposit_form_btn").disabled = true;
    $("#pca_deposit_form_loading").show();

    tdeposit = Number($('input[name="pca_deposit_amt"]').val());

    addtl_pca_deposit_ref_no = document.getElementsByName(
        "addtl_pca_deposit_ref_no[]"
    );
    addtl_pca_deposit_amt = document.getElementsByName(
        "addtl_pca_deposit_amt[]"
    );

    ol_duplicate_ref = 0;
    for (a_ol = 0; a_ol < addtl_pca_deposit_ref_no.length; a_ol++) {
        tdeposit += Number(addtl_pca_deposit_amt[a_ol].value);
        if (
            addtl_pca_deposit_ref_no[a_ol].value ==
            $('input[name="pca_deposit_ref_no"]').val()
        ) {
            ol_duplicate_ref++;
        } else {
            for (ae_ol = 0; ae_ol < addtl_pca_deposit_ref_no.length; ae_ol++) {
                if (
                    ae_ol != a_ol &&
                    addtl_pca_deposit_ref_no[a_ol].value ==
                        addtl_pca_deposit_ref_no[ae_ol].value
                ) {
                    ol_duplicate_ref++;
                }
            }
        }
    }
    if (ol_duplicate_ref > 0) {
        Swal.fire({
            icon: "error",
            title: "Ooops!",
            text: "Duplicate Reference no.",
        }).then((res) => {
            document.getElementById("pca_deposit_form_btn").disabled = false;
            $("#pca_deposit_form_loading").hide();
        });
    } else if (
        Number($('input[name="pca_deposit_amt_min"]').val()) > tdeposit
    ) {
        Swal.fire({
            icon: "error",
            title: "Ooops!",
            text:
                "Total Deposit should not be less than " +
                Number($('input[name="pca_deposit_amt_min"]').val())
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","),
        }).then((res) => {
            document.getElementById("pca_deposit_form_btn").disabled = false;
            $("#pca_deposit_form_loading").hide();
        });
    } else {
        $.ajax({
            url: "/pca-save-deposit",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (result) {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message,
                }).then((res) => {
                    if (result.type == "error") {
                    } else {
                        $(".a_pca_deposit_tab1").trigger("click");
                    }
                });
                document.getElementById(
                    "pca_deposit_form_btn"
                ).disabled = false;
                $("#pca_deposit_form_loading").hide();
                if (result.type == "error") {
                } else {
                    $(".pca_deposit_modal_close").click();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    text: "An error occured. Please contact Customer Service.",
                    icon: "error",
                    title: jqXHR.responseJSON.message,
                });
                document.getElementById(
                    "pca_deposit_form_btn"
                ).disabled = false;
                $("#pca_deposit_form_loading").hide();
            },
        });
    }
});

$(".pca_deposit").click(function () {
    $(".div_doff_transaction").hide();
    $("#pca-deposit").show();
    $(".a_pca_deposit_tab1").trigger("click");
});
$(".a_pca_deposit_tab").click(function () {
    pca_no = $(this).data("pca_no");
    tab = $(this).data("tab");
    $(".a_pca_deposit_tab")
        .removeClass("btn-primary")
        .addClass("btn-default")
        .show();
    $(".a_pca_deposit_tab" + tab)
        .removeClass("btn-default")
        .addClass("btn-primary")
        .show();

    $("#pca-deposit-table-loading").show();
    $("#pca-deposit-tbl").html("");

    $.ajax({
        url: "/pca-deposit/" + btoa(pca_no) + "/" + btoa(tab),
        type: "GET",
        dataType: "json",
        success: function (result) {
            $("#pca-deposit-table").DataTable().destroy();
            $("#pca-deposit-table-loading").show();
            $("#pca-deposit-tbl").html("");
            $.each(result, function () {
                // upload_file='-';
                // if(this.upload_file != null && this.upload_file !=''){
                //     upload_file='<a data-file="'+this.upload_file+'" class="view_pca_deposit_upload_btn" data-toggle="modal" data-target=".view_pca_deposit_upload"  ><image src="'+this.upload_file+'" height="50px;" width="50px;" /></a>';
                // }

                btn = "";
                if (
                    Number(this.advance_payment_status) == 0 &&
                    this.prepared_by == null
                ) {
                    btn +=
                        '<button data-pca_no="' +
                        this.pca_account_no +
                        '" data-id="' +
                        this.advance_payment_id +
                        '" class="btn btn-xs btn-warning cancel-pca-deposit" ><i class="fa fa-times"></i> CANCEL</button>';
                }

                deposit_reference = this.deposit_reference.split("~");
                withdraw = this.withdraw.split("~");
                adv_id = this.adv_id.split("~");
                deposit_date = this.deposit_date.split("~");
                details = '<table class="table">';
                tdeposit = 0;
                for (i = 0; i < adv_id.length; i++) {
                    file =
                        '<a data-adv_id="' +
                        adv_id[i] +
                        '" class="view_pca_deposit_upload_btn" data-toggle="modal" data-target=".view_pca_deposit_upload"  ><i class="fa fa-eye"></i> View Proof </a>';

                    tdeposit += Number(withdraw[i]);
                    details +=
                        "<tr>" +
                        "<td>" +
                        deposit_date[i] +
                        "<br>" +
                        deposit_reference[i] +
                        "<br>" +
                        Number(withdraw[i])
                            .toFixed(2)
                            .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                        "</td>" +
                        "<td>" +
                        file +
                        "</td>" +
                        "</tr>";
                }
                details +=
                    "<tr>" +
                    "<td><b>TOTAL</b></td>" +
                    "<td><b>" +
                    Number(tdeposit)
                        .toFixed(2)
                        .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                    "</b></td>" +
                    "</tr>";
                details += "</table>";

                $("#pca-deposit-tbl").append(
                    "<tr>" +
                        "<td>" +
                        this.actual_datetime +
                        "</td>" +
                        "<td>" +
                        this.deposit_account_name +
                        "<br>" +
                        this.deposit_account_no +
                        "</td>" +
                        // '<td>'+this.deposit_reference+'</td>'+
                        // '<td>'+Number(this.withdraw).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+'</td>'+
                        // '<td>'+upload_file+'</td>'+
                        "<td>" +
                        details +
                        "</td>" +
                        "<td>" +
                        btn +
                        "</td>" +
                        "</tr>"
                );
            });
            $("#pca-deposit-table").DataTable().draw();
            $("#pca-deposit-table-loading").hide();
        },
        error: function () {
            swal({
                icon: "error",
            });
            $("#pca-deposit-table-loading").hide();
        },
    });
});
$("#pca-deposit-table").on(
    "click",
    ".view_pca_deposit_upload_btn",
    function () {
        file = $(this).data("file");
        adv_id = $(this).data("adv_id");
        $.ajax({
            url: "/pca-deposit-view-proof/" + btoa(adv_id),
            type: "GET",
            dataType: "json",
            success: function (result) {
                $.each(result, function () {
                    $("#view_pca_deposit_upload_file").prop(
                        "src",
                        this.upload_file
                    );
                });
            },
            error: function () {
                swal({
                    icon: "error",
                });
            },
        });
        //$("#view_pca_deposit_upload_file").prop('src',file);
    }
);

$(".fv_edit_wtax_breakdown").on(
    "click",
    ".view_waybill_wtax_application",
    function () {
        waybill_details($(this).data("tcode"));
    }
);

$(".fv_edit_wtax_breakdown").on(
    "click",
    ".view_pca_deposit_upload_btn",
    function () {
        file = $(this).data("file");
        $("#view_pca_deposit_upload_file").prop("src", file);
    }
);
$("#pca-deposit-table").on("click", ".cancel-pca-deposit", function () {
    id = $(this).data("id");
    pca_no = $(this).data("pca_no");
    if (confirm("Are you sure you want to CANCEL this?")) {
        $.ajax({
            url: "/pca-cancel-deposit/" + btoa(id) + "/" + btoa(pca_no),
            type: "GET",
            dataType: "json",
            success: function (result) {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message,
                }).then((res) => {
                    if (result.type == "success") {
                        $(".a_pca_deposit_tab1").trigger("click");
                    }
                });
            },
            error: function (xhr, status) {
                if (xhr.status == 500) {
                    var responseJSON = xhr.responseJSON;

                    Swal.fire({
                        icon: "error",
                        title: "Ooops!",
                        text: responseJSON.message,
                    });
                } else if (xhr.status == 408) {
                    Swal.fire({
                        icon: "error",
                        title: "Connection time-out",
                        text: "Please check your internet",
                    });
                } else if ((xhr.status = 422)) {
                    var errors = xhr.responseJSON.errors;
                    var errorHTML = "";
                    for (var key of Object.keys(errors)) {
                        errorHTML +=
                            "<p> <font color='red'>*</font> " +
                            errors[key] +
                            "</p>";
                    }

                    $("#modal-error .modal-body").html(errorHTML);
                    $("#modal-error").modal("show");
                }
            },
        });
    }
});

$(".search_filter_transacted").click(function () {
    if ($("#pca_unpaid_transaction").hasClass("active")) {
        $(".pca_unpaid_transaction").trigger("click");
    } else {
        $(".pca_transacted_list").trigger("click");
    }
});

function get_pca_transaction(pca_no, pca_action) {
    $(".div_doff_transaction").hide();
    $("#pca-unpaid-transaction").show();
    $("#pca-unpaid-transaction-table-loading").show();
    $("#pca-unpaid-transaction-table tbody").html("");
    $(".wtax_breakdown_modal_btn").hide();

    $.ajax({
        url:
            "/pca-unpaid-transaction/" +
            btoa(pca_no) +
            "/" +
            btoa(pca_action) +
            "/" +
            btoa($("#filter_transacted_month").val()),
        type: "GET",
        dataType: "json",
        success: function (result) {
            $(".wtax_breakdown_modal_btn").hide();
            $.each(result["pca"], function () {
                if (Number(this.bir_2307) == 1) {
                    $(".wtax_breakdown_modal_btn").show();
                }
            });

            $("#pca-unpaid-transaction-table").DataTable().destroy();
            $("#pca-unpaid-transaction-table-loading").show();
            $("#pca-unpaid-transaction-table tbody").html("");
            $.each(result["wb"], function () {
                if (this.transactioncode.substring(0, 2) == "CI") {
                    ttype = "<br>PREPAID";
                } else if (this.transactioncode.substring(0, 2) == "CD") {
                    ttype = "<br>COLLECT";
                } else {
                    ttype = "<br>CHARGE";
                }
                //btn='';
                //if( Number(this.bir_2307)==1 && pca_action == 'transacted' && Number(this.wtax_amt)+Number(this.wtax_amt_pending) ==0 ){
                //btn +='<button data-action="apply" data-tcode="'+this.transactioncode+'" data-toggle="modal" data-target=".wtax_breakdown_modal" class="btn btn-xs btn-primary wtax_breakdown_modal_btn" ><i class="fa fa-file"></i> Apply Witholding Tax</button>';
                //}
                //else if( Number(this.bir_2307)==1 && pca_action == 'transacted' && Number(this.wtax_amt)+Number(this.wtax_amt_pending) > 0 ){
                //btn +='<button data-action="view" data-tcode="'+this.transactioncode+'" data-toggle="modal" data-target=".wtax_breakdown_modal" class="btn btn-xs btn-info wtax_breakdown_modal_btn" ><i class="fa fa-eye"></i> View Witholding Tax</button>';
                //}
                $("#pca-unpaid-transaction-table tbody").append(
                    "<tr>" +
                        "<td>" +
                        this.tdate +
                        "</td>" +
                        "<td onclick=\"waybill_details('" +
                        this.transactioncode +
                        '\')" data-toggle="modal" data-target=".view_waybill" ><a>' +
                        this.waybill_no +
                        ttype +
                        "</a></td>" +
                        "<td>" +
                        this.shipper_name +
                        "</td>" +
                        "<td>" +
                        this.consignee_name +
                        "</td>" +
                        "<td>" +
                        Number(this.balance_amount)
                            .toFixed(2)
                            .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                        "</td>" +
                        //'<td>'+btn+'</td>'+
                        "</tr>"
                );
            });
            $("#pca-unpaid-transaction-table").DataTable().draw();
            $("#pca-unpaid-transaction-table-loading").hide();
        },
        error: function () {
            swal({
                icon: "error",
            });
            $("#pca-unpaid-transaction-table-loading").hide();
        },
    });
}
$(".wtax_breakdown_modal_btn").click(function () {
    pca_no = $(this).data("pca_no");
    $(".fv_edit_wtax_breakdown").html("");
    $("#tbl_wtax_waybill").html("");

    if ($(this).data("action") == "view") {
        $(".modal-pca-wtax").removeClass("modal-md");
        $(".modal-pca-wtax").addClass("modal-lg");
        $(".wtax_breakdown_modal_h4").html(
            '<i class="fa fa-eye"></i> VIEW WITHOLDING TAX APPLICATION'
        );
        $(".apply_wtax").hide();
        $(".view_wtax").show();
        get_wtax_breakdown_func();
    } else {
        $(".modal-pca-wtax").removeClass("modal-lg");
        $(".modal-pca-wtax").addClass("modal-md");
        $(".apply_wtax").show();
        $(".view_wtax").hide();
        $(".wtax_breakdown_modal_h4").html(
            '<i class="fa fa-file"></i> APPLY WITHOLDING TAX'
        );
        $("#wtax_breakdown_form_loading").hide();
        get_tax_code_func();
        get_wtax_waybill_func(pca_no);
    }
});
function get_wtax_waybill_func(pca_no) {
    $('select[name="wtax_waybill_selection"]').html(
        '<option selected value="">--Select Waybill--</option>'
    );
    $.getJSON("/get-pca-wtax-waybill/" + btoa(pca_no), function (result) {
        $('select[name="wtax_waybill_selection"]').html(
            '<option selected value="">--Select Waybill--</option>'
        );
        $.each(result, function () {
            $('select[name="wtax_waybill_selection"]').append(
                '<option value="' +
                    this.transactioncode +
                    '">' +
                    this.waybill_no +
                    "</option>"
            );
        });
        $('select[name="wtax_waybill_selection"]').each(function () {
            $(this).select2({
                dropdownParent: $(this).parent(),
            });
        });
        $('select[name="wtax_waybill_selection"]').trigger("change");
    });
}
function remove_wtax_application(id) {
    if (confirm("Are you sure you want to CANCEL this?")) {
        $.getJSON("/cancel-wtax-application/" + btoa(id), function (result) {
            Swal.fire({
                icon: result.type,
                title: result.title,
                text: result.message,
            }).then((res) => {});
            get_wtax_breakdown_func();
            //$(".wtax_breakdown_modal_close").trigger('click');
            $(".pca_transacted_list").trigger("click");
        });
    }
}
function get_wtax_breakdown_func(tcode = "ALL") {
    $("#wtax_breakdown_form_loading").show();
    $(".fv_edit_wtax_breakdown").html("");

    $.getJSON(
        "/get-wtax-breakdown/" +
            btoa(tcode) +
            "/" +
            btoa($('input[name="pca_wtax_cno"]').val()),
        function (result) {
            $(".table_pca_wtax_breakdown").DataTable().destroy();
            $(".fv_edit_wtax_breakdown").html("");
            $("#wtax_breakdown_form_loading").show();

            $.each(result, function () {
                btn = '<font color="blue">VALIDATED</font>';
                msg_append = "";
                if (
                    Number(this.waybills_wtax_breakdown_application_status) == 0
                ) {
                    btn =
                        '<button type="button" onclick="remove_wtax_application(' +
                        this.waybills_wtax_breakdown_application_id +
                        ')" class="btn btn-xs btn-warning" ><i class="fa fa-times"></i> Cancel</button>';
                    msg_append =
                        '<br><font color="green"><small>PENDING</small></font>';
                }
                $(".fv_edit_wtax_breakdown").append(
                    "<tr>" +
                        "<td>" +
                        this.tdate +
                        msg_append +
                        "<br><br>" +
                        '<a data-file="' +
                        this.upload_file +
                        '" class="view_pca_deposit_upload_btn" data-toggle="modal" data-target=".view_pca_deposit_upload"  ><small><i>view uploaded image</small></i></a>' +
                        "</td>" +
                        "<td>" +
                        this.wb_list +
                        "</td>" +
                        "<td>" +
                        this.tax_code +
                        "</td>" +
                        "<td>" +
                        Number(this.tamount).toFixed(2) +
                        "</td>" +
                        "<td>" +
                        btn +
                        "</td>" +
                        "</tr>"
                );
            });
            $(".table_pca_wtax_breakdown").DataTable();
            $("#wtax_breakdown_form_loading").hide();
        }
    );
}
function get_tax_code_func() {
    $(".wtax_tax_code").html(
        '<option selected value="">--Select Tax Code--</option>'
    );
    $.getJSON("/get-wtax-atc", function (result) {
        $(".wtax_tax_code").html(
            '<option selected value="">--Select Tax Code--</option>'
        );
        $.each(result, function () {
            $(".wtax_tax_code").append(
                '<option value="' +
                    this.tax_code_id +
                    '">' +
                    this.tax_code +
                    "</option>"
            );
        });
        $(".wtax_tax_code").each(function () {
            $(this).select2({
                dropdownParent: $(this).parent(),
            });
        });
        $(".wtax_tax_code").trigger("change");
    });
}

$(".wtax_waybill_selection_btn").click(function () {
    if ($('select[name="wtax_waybill_selection"]').val() != "") {
        pca_wtax_waybill = document.getElementsByName("pca_wtax_waybill[]");
        exist_wb = 0;
        for (i = 0; i < pca_wtax_waybill.length; i++) {
            if (
                pca_wtax_waybill[i].value ==
                $('select[name="wtax_waybill_selection"]').val()
            ) {
                exist_wb++;
            }
        }
        if (exist_wb > 0) {
            alert("Already in list.");
        } else {
            wb = $('select[name="wtax_waybill_selection"]').val();
            $("#tbl_wtax_waybill").append(
                '<tr id="tr_pca_wtax_wb_' +
                    wb +
                    '">' +
                    "<td>" +
                    '<input type="hidden" name="pca_wtax_waybill[]" value="' +
                    wb +
                    '" />' +
                    $(
                        'select[name="wtax_waybill_selection"] option:selected'
                    ).text() +
                    "</td>" +
                    '<td><button data-wb="' +
                    wb +
                    '" data-wno="' +
                    $(
                        'select[name="wtax_waybill_selection"] option:selected'
                    ).text() +
                    '" class="btn btn-xs btn-danger remove_pca_wtax_btn"><i class="fa fa-trash"></i></button></td>' +
                    "</tr>"
            );

            $('select[name="wtax_waybill_selection"]')
                .val("")
                .trigger("change");
            $(
                'select[name="wtax_waybill_selection"] option[value="' +
                    wb +
                    '"]'
            ).remove();
        }
    } else {
        alert("Empty selection.");
    }
});
$("#tbl_wtax_waybill").on("click", ".remove_pca_wtax_btn", function () {
    wb = $(this).data("wb");
    wno = $(this).data("wno");
    $("#tr_pca_wtax_wb_" + wb).remove();

    $('select[name="wtax_waybill_selection"]').append(
        '<option value="' + wb + '">' + wno + "</option>"
    );
});

count_wtax_breakdown = 0;
function wtax_tax_code_btn_func(dtype) {
    if (
        $('select[name="wtax_tax_code_' + dtype + '"]').val() != "" &&
        Number($('input[name="wtax_tax_code_amt_' + dtype + '"]').val()) > 0
    ) {
        $("#tbl_witholding_tax_breakdown_" + dtype).append(
            '<tr id="tr_wtax_breakdown_' +
                count_wtax_breakdown +
                '" >' +
                "<td>" +
                '<input type="hidden" name="wtax_breakdown_type[]" value="' +
                dtype +
                '" />' +
                '<input type="hidden" name="wtax_breakdown_tcode[]" value="' +
                $('select[name="wtax_tax_code_' + dtype + '"]').val() +
                '" />' +
                '<input step="any" type="hidden" name="wtax_breakdown_tcode_amt[]" value="' +
                $('input[name="wtax_tax_code_amt_' + dtype + '"]').val() +
                '" />' +
                $(
                    'select[name="wtax_tax_code_' + dtype + '"] option:selected'
                ).text() +
                "</td>" +
                "<td>" +
                Number($('input[name="wtax_tax_code_amt_' + dtype + '"]').val())
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                "</td>" +
                '<td align="center" class="td_edit_wtax_breakdown" ><button  type="button"  class="btn btn-danger btn-xs" onclick="remove_wtax_breakdown_func(' +
                count_wtax_breakdown +
                ')" ><i class="fa fa-trash"></i></button></td>' +
                "</tr>"
        );
        $('select[name="wtax_tax_code_' + dtype + '"]')
            .val("")
            .trigger("change");
        $('input[name="wtax_tax_code_amt_' + dtype + '"]').val("");

        count_wtax_breakdown++;
        compute_wtax_breakdown();
    } else {
        alert("Incorrect input/s.");
    }
}
function remove_wtax_breakdown_func(id) {
    $("#tr_wtax_breakdown_" + id).remove();
    compute_wtax_breakdown();
}
function compute_wtax_breakdown() {
    document.getElementById("witholding_tax_breakdown_submit").disabled = true;

    wtax_breakdown_tcode = document.getElementsByName("wtax_breakdown_tcode[]");
    wtax_breakdown_tcode_amt = document.getElementsByName(
        "wtax_breakdown_tcode_amt[]"
    );
    total_wtax = 0;
    for (i = 0; i < wtax_breakdown_tcode.length; i++) {
        total_wtax += Number(wtax_breakdown_tcode_amt[i].value);
    }
    $("#wtax_breakdown_total").val(total_wtax.toFixed(2));

    if (total_wtax > 0) {
        document.getElementById(
            "witholding_tax_breakdown_submit"
        ).disabled = false;
    }
}
$(".wtax_breakdown_form").submit(function () {
    event.preventDefault();
    document.getElementById("witholding_tax_breakdown_submit").disabled = true;

    pca_wtax_waybill = document.getElementsByName("pca_wtax_waybill[]");

    $("#wtax_breakdown_form_loading").show();
    if (pca_wtax_waybill.length <= 0) {
        alert("Please add at least 1 waybill.");
        document.getElementById(
            "witholding_tax_breakdown_submit"
        ).disabled = false;
        $("#wtax_breakdown_form_loading").hide();
    } else if (Number($("#wtax_breakdown_total").val()) <= 0) {
        alert("Witholding Tax should be greater than 0.");
        document.getElementById(
            "witholding_tax_breakdown_submit"
        ).disabled = false;
        $("#wtax_breakdown_form_loading").hide();
    } else {
        $.ajax({
            url: "/save-wtax-application",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function (result) {
                Swal.fire({
                    icon: result.icon,
                    title: result.title,
                    text: result.message,
                }).then((res) => {
                    if (
                        Number(result.msg_type) == 2 ||
                        Number(result.msg_type) == 0
                    ) {
                    } else {
                        $(".pca_transacted_list").trigger("click");
                    }
                });
                document.getElementById(
                    "witholding_tax_breakdown_submit"
                ).disabled = false;
                $("#wtax_breakdown_form_loading").hide();

                if (
                    Number(result.msg_type) == 2 ||
                    Number(result.msg_type) == 0
                ) {
                } else {
                    $(".wtax_breakdown_modal_close").click();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    text: "An error occured. Please contact Customer Service.",
                    icon: "error",
                    title: jqXHR.responseJSON.message,
                });
                document.getElementById(
                    "witholding_tax_breakdown_submit"
                ).disabled = false;
                $("#wtax_breakdown_form_loading").hide();
            },
        });
    }
});
$(".tab_pub_transction").click(function () {
    $("#tab_pub_transction_ctab").val($(this).data("tab"));
    $(".tab_pub_transction")
        .removeClass("btn-primary")
        .addClass("btn-default")
        .show();
    $(".tab_pub_transction_" + $("#tab_pub_transction_ctab").val())
        .removeClass("btn-default")
        .addClass("btn-primary")
        .show();
    $(".div_pub_transaction").hide();
    $(".div_pub_transaction_" + $("#tab_pub_transction_ctab").val()).show();
    $(".div_doff_transaction").hide();
    $("#pca-unpaid-transaction").show();
    $("#div_publication_transaction_loading").hide();
    get_pub_transaction();
});

function get_pub_transaction() {
    $("#tbl_pub_add_transaction").html("");
    $("#tbl_pub_dr").html("");
    if ($("#tab_pub_transction_ctab").val() == "add") {
        cdate = new Date().toLocaleDateString("en-CA");
        $('input[name="pub_transaction_add_date"]').val(cdate);
        $('input[name="pub_transaction_add_date"]').prop("max", cdate);
        get_pub_agent_list();
    } else {
        get_pub_dr_list();
    }
}
$(".filter_pub_dr_btn").click(function () {
    get_pub_dr_list();
});
function get_pub_dr_list() {
    $("#tbl_pub_dr").html("");
    $("#div_publication_transaction_loading").show();

    $.ajax({
        url: "/get-pub-dr-list",
        type: "GET",
        data: {
            month: btoa($("#filter_pub_dr_month").val()),
            pca_no: btoa($('input[name="pub_transaction_pno"]').val()),
        },
        dataType: "json",
        success: function (result) {
            $(".table_pub_dr").DataTable().destroy();
            $("#tbl_pub_dr").html("");
            $.each(result, function () {
                status_txt = "";

                btn ='<button data-id="'+this.publication_added_transaction_id+'" data-toggle="modal"  data-target=".view_dr_details_modal" class="btn btn-xs btn-info view_pub_dr_btn"><i class="fa fa-eye"></i> View Details</button>';
                btn +='<button data-id="'+this.publication_added_transaction_id+'" class="btn btn-xs btn-default download_pub_dr_btn"><i class="fa fa-download"></i> Download</button>';
                if (
                    this.confirmed_date == null &&
                    this.received_date == null &&
                    Number(this.publication_added_transaction_status) == 0

                ) {
                    status_txt='<font color="green">Waiting For Confirmation</font>';
                    if(this.added_by == null){
                        btn +='<button data-id="'+this.publication_added_transaction_id+'" class="btn btn-xs btn-danger remove_pub_dr_btn"><i class="fa fa-trash"></i> Remove</button>';
                        btn +='<button data-id="'+this.publication_added_transaction_id+'" data-toggle="modal"  data-target=".edit_dr_details_modal" class="btn btn-xs btn-success edit_pub_dr_btn"><i class="fa fa-pencil"></i> Edit Details</button>';
                    }
                } else if (
                    this.confirmed_date != null &&
                    this.received_date == null &&
                    Number(this.publication_added_transaction_status) == 0
                ) {
                    status_txt ='<font color="red">Delivery in Progress</font>';
                } else if (
                    this.confirmed_date != null &&
                    this.received_date != null &&
                    Number(this.publication_added_transaction_status) == 1
                ) {
                    status_txt = '<font color="blue">Delivered</font>';
                }
                $("#tbl_pub_dr").append(
                    "<tr>" +
                        "<td>" +
                        this.issue_date +
                        "</td>" +
                        "<td>" +
                        this.added_date +
                        "</td>" +
                        "<td>" +
                        status_txt +
                        "</td>" +
                        '<td align="center"><div class="btn-group-vertical">' +
                        btn +
                        "</div></td>" +
                        "</tr>"
                );
            });
            $(".table_pub_dr").DataTable().draw();
            $("#div_publication_transaction_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            $("#div_publication_transaction_loading").hide();
        },
    });
}
none_edit_count = 0;
$("#tbl_pub_dr").on("click", ".edit_pub_dr_btn", function () {

    id = $(this).data("id");
    $('input[name="dr_edit_id"]').val(id);
    $("#tbl_dr_edit_details").html("");
    $("#edit_dr_details_loading").show();
    $("#edit_dr_issue_date").html("");
    $.ajax({
        url: "/get-pub-dr-transaction",
        type: "GET",
        data: {
            id: btoa(id),
        },
        dataType: "json",
        success: function (result) {
            $("#edit_dr_details_loading").show();
            $("#tbl_dr_edit_details").html("");
            $("#edit_dr_issue_date").html("");
            $.each(result["main"], function () {
                $('input[name="dr_edit_issue_date"]').val(this.issue_date);
            });

            $.each(result["details"], function () {
                if (this.publication_agent_id == null) {
                    none_edit_count++;
                    id = "NONE_EDIT" + none_edit_count;
                } else {
                    id = this.publication_agent_id;
                }

                $("#tbl_dr_edit_details").append(
                    '<tr id="tr_edit_dr_' +
                        id +
                        '" >' +
                        "<td>" +
                        this.agent_name +
                        '<input type="hidden" value="' +
                        id +
                        '" name="pub_add_transaction_agent_id[]" />' +
                        '<input type="hidden" value="' +
                        this.publication_added_transaction_details_id +
                        '" name="pub_add_transaction_details_id[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_name +
                        '" name="pub_add_transaction_agent_name[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_contact_person +
                        '" name="pub_add_transaction_cperson[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_contact_no +
                        '" name="pub_add_transaction_cperson_no[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_address_street +
                        '" name="pub_add_transaction_agent_address_street[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_address_barangay +
                        '" name="pub_add_transaction_agent_address_brgy[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_address_city +
                        '" name="pub_add_transaction_agent_address_city[]" />' +
                        '<input type="hidden" value="' +
                        this.agent_address_province +
                        '" name="pub_add_transaction_agent_address_province[]" />' +
                        "</td>" +
                        "<td>" +
                        this.agent_contact_person +
                        "</td>" +
                        "<td>" +
                        this.agent_contact_no +
                        "</td>" +
                        "<td>" +
                        this.agent_address_street +
                        " " +
                        this.agent_address_barangay +
                        " " +
                        this.agent_address_city +
                        " " +
                        this.agent_address_province +
                        "</td>" +
                        '<td><input min="0" value="' +
                        this.main_qty +
                        '" required class="form-control" type="number" name="pub_add_transaction_main_qty[]" /></td>' +
                        '<td><input  min="0" value="' +
                        this.tabloid_qty +
                        '" required class="form-control" type="number" name="pub_add_transaction_tabloid_qty[]" /></td>' +
                        '<td><button type="button" data-id="' +
                        id +
                        '" class="btn btn-md btn-danger pub_add_transaction_agent_remove"><i class="fa fa-trash"></i></button></td>' +
                        "</tr>"
                );
            });
            $("#edit_dr_details_loading").hide();
            $(".table-tbl_dr_edit_details").tableDnD();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            $("#edit_dr_details_loading").hide();
        },
    });
});

function view_pub_dr_details(id){

    $("#tbl_dr_details").html("");
    $("#view_dr_details_loading").show();
    $("#view_dr_issue_date").html("");
    $.ajax({
        url: "/get-pub-dr-transaction",
        type: "GET",
        data: {
            id: btoa(id),
        },
        dataType: "json",
        success: function (result) {
            $("#view_dr_details_loading").show();
            $("#tbl_dr_details").html("");
            $("#view_dr_issue_date").html("");
            $.each(result["main"], function () {
                $("#view_dr_issue_date").html(this.issue_date);
            });
            $.each(result["details"], function () {
                confirmed_main_amt='';
                if (this.confirmed_main_qty == null) {
                    this.confirmed_main_qty = "-";
                }else{
                    confirmed_main_amt='<br><br><p>Php '+((Number(this.confirmed_main_qty)*Number(this.main_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                }
                confirmed_tabloid_amt='';
                if (this.confirmed_tabloid_qty == null) {
                    this.confirmed_tabloid_qty = "-";
                }else{
                    confirmed_tabloid_amt='<br><br><p>Php '+((Number(this.confirmed_tabloid_qty)*Number(this.tabloid_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                }
                received_main_amt='';
                view_proof='';
                if (this.received_main_qty == null) {
                    this.received_main_qty = "-";
                }else{
                    received_main_amt='<br><br><p>Php '+((Number(this.received_main_qty)*Number(this.main_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                    view_proof='<br><a data-toggle="modal" data-id="'+this.publication_added_transaction_details_id+'" data-target=".view_proof_upload" class="pull-right view_proof_upload_btn"><i class="fa fa-eye"> View Proof</i></a>';
                }
                received_tabloid_amt='';
                if (this.received_tabloid_qty == null) {
                    this.received_tabloid_qty = "-";
                }else{
                    received_tabloid_amt='<br><br><p>Php '+((Number(this.received_tabloid_qty)*Number(this.tabloid_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                }
                returned_main_amt='';
                if (this.returned_main_qty == null) {
                    this.returned_main_qty = "-";
                }else{
                    returned_main_amt='<br><br><p>Php '+((Number(this.returned_main_qty)*Number(this.main_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                }
                returned_tabloid_amt='';
                if (this.returned_tabloid_qty == null) {
                    this.returned_tabloid_qty = "-";
                }
                else{
                    returned_tabloid_amt='<br><br><p>Php '+((Number(this.returned_tabloid_qty)*Number(this.tabloid_cprice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","))+'</p>';
                }
                details =
                    '<br><i class="fa fa-user"></i> ' +
                    this.agent_contact_person;
                details +=
                    '<br><i class="fa fa-phone"></i> ' + this.agent_contact_no;
                details +=
                    '<br><i class="fa fa-map-marker"></i> ' +
                    this.agent_address_street +
                    " " +
                    this.agent_address_barangay +
                    " " +
                    this.agent_address_city +
                    " " +
                    this.agent_address_province;

                $("#tbl_dr_details").append(
                    "<tr>" +
                        "<td>" +
                        this.agent_name +
                        details +
                        view_proof+
                        "</td>" +
                        "<td>" +
                        this.main_qty +
                        "</td>" +
                        "<td>" +
                        this.tabloid_qty +
                        "</td>" +
                        "<td>" +
                        this.confirmed_main_qty +confirmed_main_amt+
                        "</td>" +
                        "<td>" +
                        this.confirmed_tabloid_qty +confirmed_tabloid_amt+
                        "</td>" +
                        "<td>" +
                        this.received_main_qty +received_main_amt+
                        "</td>" +
                        "<td>" +
                        this.received_tabloid_qty +received_tabloid_amt+
                        "</td>" +
                        "<td>" +
                        this.returned_main_qty +returned_main_amt+
                        "</td>" +
                        "<td>" +
                        this.returned_tabloid_qty +returned_tabloid_amt+
                        "</td>" +
                        "</tr>"
                );
            });
            $("#view_dr_details_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            $("#view_dr_details_loading").hide();
        },
    });
}
$('#tbl_dr_details').on('click', '.view_proof_upload_btn', function() {
    id=$(this).data('id');
    $(".div_view_proof").html('');

    $.ajax({
        url: "/pub-upload-view-proof",
        type: "GET",
        data:{
            id:btoa(id)
        },
        dataType: "json",
        success: function(result){
            $(".div_view_proof").html('');
            $.each(result,function(){
                $(".div_view_proof").append('<image style="width:100%;height:100%;" src="/system_files/PUBLICATION/DR/'+this.upload_file+'" /><br>');
            });

        }, error: function(){
            swal({
                icon: "error"
            });
        }


    });
});

$("#tbl_dr_edit_details").on(
    "click",
    ".pub_add_transaction_agent_remove",
    function () {
        $("#tr_edit_dr_" + $(this).data("id")).remove();
    }
);
$("#tbl_pub_dr").on("click", ".download_pub_dr_btn",function (){
    id = $(this).data("id");
    window.open( "/publication-csv-template-download/"+btoa('dr-'+id));
});

$("#tbl_pub_dr").on("click", ".view_pub_dr_btn", function () {
    id = $(this).data("id");
    view_pub_dr_details(id);
});

$("#tbl_pub_dr").on("click", ".remove_pub_dr_btn", function () {
    id = $(this).data("id");
    if (confirm("Are you sure you want to REMOVE this?")) {
        $.ajax({
            url: "/remove-pub-dr",
            type: "GET",
            data: {
                id: btoa(id),
            },
            dataType: "json",
            success: function (result) {
                Swal.fire({
                    icon: result.type,
                    title: result.title,
                    text: result.message,
                }).then((res) => {
                    if (Number(result.msg_type) == 1) {
                        get_pub_dr_list();
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                new swal({
                    text: "An error occured. Please contact Customer Service.",
                    icon: "error",
                    title: jqXHR.responseJSON.message,
                });
            },
        });
    }
});
$(".pub_transaction_add_agent_modal_btn").click(function () {
    $('input[name="pub_transaction_add_agent_modal_action"]').val(
        $(this).data("action")
    );
    get_pub_agent_list("pub_transaction_add_agent_name");
});
$("#pub_transaction_add_agent_modal_btn").click(function (){

    if ($('select[name="pub_transaction_add_agent_name"]').val() == "") {
        new swal({
            text: "Please select Agent.",
            icon: "error",
            title: "Empty",
        });
    } else {
        id=document.getElementsByName('pub_add_transaction_agent_id[]');
        count_exist=0;
        for(i=0;i<id.length;i++){
            if(id[i].value==$('select[name="pub_transaction_add_agent_name"]').val()){
                count_exist++;
            }
        }
        if(count_exist > 0){
            new swal({
                text: "Agent already in list.",
                icon: "error",
                title: "Exist",
            });
        }else{
            if (
                $('input[name="pub_transaction_add_agent_modal_action"]').val() ==
                "edit"
            ) {
                tbl_name = "tbl_dr_edit_details";
                loading_name = "edit_dr_details_loading";
            } else {
                tbl_name = "tbl_pub_add_transaction";
                loading_name = "div_publication_transaction_loading";
            }
            $("#" + loading_name).show();
            $("#form_pub_transaction_add_btn").prop("disabled", true);
            $.getJSON(
                "/pca-agent-list/" +
                    btoa($('input[name="pub_transaction_pno"]').val()) +
                    "/" +
                    btoa($('select[name="pub_transaction_add_agent_name"]').val()),
                function (result) {
                    $("#" + loading_name).show();
                    $("#form_pub_transaction_add_btn").prop("disabled", true);
                    $.each(result, function () {
                        tr_name = "tr_pub_add_transaction_";
                        if (
                            $(
                                'input[name="pub_transaction_add_agent_modal_action"]'
                            ).val() == "edit"
                        ) {
                            tr_name = "tr_edit_dr_";
                        }
                        $("#" + tbl_name).append(
                            '<tr id="' +
                                tr_name +
                                this.publication_agent_id +
                                '">' +
                                "<td>" +
                                '<input type="hidden" value="' +
                                this.publication_agent_id +
                                '" name="pub_add_transaction_agent_id[]" />' +
                                '<input type="hidden" value="' +
                                this.publication_agent_name +
                                '" name="pub_add_transaction_agent_name[]" />' +
                                '<input type="hidden" value="' +
                                this.contact_person +
                                '" name="pub_add_transaction_cperson[]" />' +
                                '<input type="hidden" value="' +
                                this.contact_no +
                                '" name="pub_add_transaction_cperson_no[]" />' +
                                '<input type="hidden" value="' +
                                this.street +
                                '" name="pub_add_transaction_agent_address_street[]" />' +
                                '<input type="hidden" value="' +
                                this.barangay +
                                '" name="pub_add_transaction_agent_address_brgy[]" />' +
                                '<input type="hidden" value="' +
                                this.cities_name +
                                '" name="pub_add_transaction_agent_address_city[]" />' +
                                '<input type="hidden" value="' +
                                this.province_name +
                                '" name="pub_add_transaction_agent_address_province[]" />' +
                                this.publication_agent_name +
                                "</td>" +
                                "<td>" +
                                this.contact_person +
                                "</td>" +
                                "<td>" +
                                this.contact_no +
                                "</td>" +
                                "<td>" +
                                this.street +
                                " " +
                                this.barangay +
                                " " +
                                this.cities_name +
                                " " +
                                this.province_name +
                                "</td>" +
                                '<td><input min="0"  required class="form-control" type="number" name="pub_add_transaction_main_qty[]" /></td>' +
                                '<td><input  min="0" required class="form-control" type="number" name="pub_add_transaction_tabloid_qty[]" /></td>' +
                                '<td><button type="button" data-id="' +
                                this.publication_agent_id +
                                '" class="btn btn-md btn-danger pub_add_transaction_agent_remove"><i class="fa fa-trash"></i></button></td>' +
                                "</tr>"
                        );
                    });

                    $("#form_pub_transaction_add_btn").prop("disabled", false);
                    $("#" + loading_name).hide();
                    $(".pub_transaction_add_agent_modal_close").click();
                    $(".table-"+tbl_name).tableDnD();

                }
            );
        }
    }
});
function get_pub_agent_list(val = "") {
    if (val == "pub_transaction_add_agent_name") {
        $("#pub_transaction_add_agent_modal_loading").show();
        $('select[name="pub_transaction_add_agent_name"]').html(
            '<option selected value="">--Select Agent--</option>'
        );
        $.getJSON(
            "/pca-agent-list/" +
                btoa($('input[name="pub_transaction_pno"]').val()) +
                "/" +
                btoa("ALL"),
            function (result) {
                $("#pub_transaction_add_agent_modal_loading").show();
                $('select[name="pub_transaction_add_agent_name"]').html(
                    '<option selected value="">--Select Agent--</option>'
                );
                $.each(result, function () {
                    $('select[name="pub_transaction_add_agent_name"]').append(
                        '<option value="' +
                            this.publication_agent_id +
                            '">' +
                            this.publication_agent_name +
                            "</option>"
                    );
                });
                $("#pub_transaction_add_agent_modal_loading").hide();
                $('select[name="pub_transaction_add_agent_name"]').trigger(
                    "change"
                );
            }
        );
    } else {
        $("#div_publication_transaction_loading").show();
        $("#form_pub_transaction_add_btn").prop("disabled", true);
        $("#tbl_pub_add_transaction").html("");
        $.getJSON(
            "/pca-agent-list/" +
                btoa($('input[name="pub_transaction_pno"]').val()) +
                "/" +
                btoa("ALL"),
            function (result) {
                $("#div_publication_transaction_loading").show();
                $("#form_pub_transaction_add_btn").prop("disabled", true);
                $("#tbl_pub_add_transaction").html("");
                $.each(result, function () {
                    $("#tbl_pub_add_transaction").append(
                        '<tr id="tr_pub_add_transaction_' +
                            this.publication_agent_id +
                            '">' +
                            "<td>" +
                            '<input type="hidden" value="' +
                            this.publication_agent_id +
                            '" name="pub_add_transaction_agent_id[]" />' +
                            '<input type="hidden" value="' +
                            this.publication_agent_name +
                            '" name="pub_add_transaction_agent_name[]" />' +
                            '<input type="hidden" value="' +
                            this.contact_person +
                            '" name="pub_add_transaction_cperson[]" />' +
                            '<input type="hidden" value="' +
                            this.contact_no +
                            '" name="pub_add_transaction_cperson_no[]" />' +
                            '<input type="hidden" value="' +
                            this.street +
                            '" name="pub_add_transaction_agent_address_street[]" />' +
                            '<input type="hidden" value="' +
                            this.barangay +
                            '" name="pub_add_transaction_agent_address_brgy[]" />' +
                            '<input type="hidden" value="' +
                            this.cities_name +
                            '" name="pub_add_transaction_agent_address_city[]" />' +
                            '<input type="hidden" value="' +
                            this.province_name +
                            '" name="pub_add_transaction_agent_address_province[]" />' +
                            this.publication_agent_name +
                            "</td>" +
                            "<td>" +
                            this.contact_person +
                            "</td>" +
                            "<td>" +
                            this.contact_no +
                            "</td>" +
                            "<td>" +
                            this.street +
                            " " +
                            this.barangay +
                            " " +
                            this.cities_name +
                            " " +
                            this.province_name +
                            "</td>" +
                            '<td><input min="0"  required class="form-control" type="number" name="pub_add_transaction_main_qty[]" /></td>' +
                            '<td><input  min="0" required class="form-control" type="number" name="pub_add_transaction_tabloid_qty[]" /></td>' +
                            '<td><button type="button" data-id="' +
                            this.publication_agent_id +
                            '" class="btn btn-md btn-danger pub_add_transaction_agent_remove"><i class="fa fa-trash"></i></button></td>' +
                            "</tr>"
                    );
                });

                $("#form_pub_transaction_add_btn").prop("disabled", false);
                $("#div_publication_transaction_loading").hide();
                $(".table-tbl_pub_add_transaction").tableDnD();
            }
        );
    }
}
$("#tbl_pub_add_transaction").on(
    "click",
    ".pub_add_transaction_agent_remove",
    function () {
        $("#tr_pub_add_transaction_" + $(this).data("id")).remove();
    }
);
$(".pca_transacted_list").click(function () {
    $(".div_premium_booking_transaction").hide();
    $(".div_publication_transaction").hide();
    if ($(this).data("pc_type") == "publication") {
        $(".div_publication_transaction").show();
        $(".tab_pub_transction_list").trigger("click");
    } else {
        $(".div_premium_booking_transaction").show();
        pca_no = $(this).data("pca_no");
        pca_action = $(this).data("pca_action");
        get_pca_transaction(pca_no, pca_action);
    }
});
$(".pca_unpaid_transaction").click(function () {
    pca_no = $(this).data("pca_no");
    pca_action = $(this).data("pca_action");
    get_pca_transaction(pca_no, pca_action);
});
$(".pca_view_agents").click(function () {
    $(".div_doff_transaction").hide();
    $("#pca-agents").show();
    get_pca_agent_list();
});
$(".pca_view_ledger").click(function () {
    $(".div_doff_transaction").hide();
    $("#pca-ledger").show();
    pca_no = $(this).data("pca_no");
    $("#ledger_pca_no").val(pca_no);
    $("#pca_ledger_h5_details").html("");

    $("#pca_filter_ledger_date").val("last30").trigger("change");
    get_pca_ledger_func();
});
$("#pca_filter_ledger_date").change(function () {
    $(".td_pca_filter_ledger_range").hide();
    if (this.value == "custom") {
        $(".td_pca_filter_ledger_range").show();
    }
});
$(".pca_filter_ledger_btn").click(function () {
    get_pca_ledger_func();
});
$(".pca_print_ledger_btn").click(function () {
    print_pca_ledger_func();
});
function print_pca_ledger_func() {
    window.open(
        "/get-pca-ledger/" +
            btoa($("#ledger_pca_no").val()) +
            "/" +
            btoa($("#pca_filter_ledger_date").val()) +
            "/" +
            btoa($("#pca_filter_ledger_from").val()) +
            "/" +
            btoa($("#pca_filter_ledger_to").val()) +
            "/" +
            btoa("print")
    );
}
function number_format_func(val){
    if(val==0){
        val='-';
    }else{
        val=Number(val).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
    }
    return val;
}
function get_pca_ledger_func(pub_dr_id='') {
    $("#tbody_pca_ledger_details").html("");
    $("#tbody_pca_ledger_details_loading").show();

    $.ajax({
        url:
            "/get-pca-ledger/" +
            btoa($("#ledger_pca_no").val()) +
            "/" +
            btoa($("#pca_filter_ledger_date").val()) +
            "/" +
            btoa($("#pca_filter_ledger_from").val()) +
            "/" +
            btoa($("#pca_filter_ledger_to").val()) +
            "/" +
            btoa("view"),
        type: "GET",
        dataType: "json",
        success: function (result) {
            $("#tbody_pca_ledger_details").html("");
            $("#tbody_pca_ledger_details_loading").show();
            rbalance = 0;

            $("#tbody_pca_ledger_details").html(
                '<tr hidden id="tr_pca_ledger_details_append">' +
                    '<td colspan="6"></td>' +
                    "</tr>"
            );

            debit = number_format_func(result[result.length - 1]["withdraw"]);
            $("#tr_pca_ledger_details_append").after(
                "<tr>" +
                    '<td colspan="5">Previous Balance</td>' +
                    "<td>" +
                    debit +
                    "</td>" +
                    "</tr>"
            );

            rbalance =
                rbalance +
                Number(result[result.length - 1]["withdraw"]) -
                Number(result[result.length - 1]["deposit"]);

            $.each(result, function () {
                particular = "ADVANCE PAYMENT";
                debit = number_format_func(this.withdraw);
                credit = number_format_func(this.deposit);
                rbalance =
                    rbalance + Number(this.withdraw) - Number(this.deposit);
                if (Number(this.deposit) > 0) {
                    particular = "PAYMENT";
                }
                if (
                    this.pdate != "PREVIOUS BALANCE" &&
                    this.particulars != null &&
                    this.particulars != ""
                ) {
                    particular = this.particulars;
                }

                if (this.pdate != "PREVIOUS BALANCE") {
                    reference = "";
                    if (
                        this.reversal_payment_details != null &&
                        this.reversal_payment_details != ""
                    ) {
                        reversal_payment_details =
                            this.reversal_payment_details.split("^");

                        for (i = 0; i < reversal_payment_details.length; i++) {
                            data = reversal_payment_details[i].split("~");
                            if (reference != "") {
                                reference += "<br><br>";
                            }
                            reference +=
                                "Waybill/Ref: <a onclick=\"waybill_details('" +
                                data[1] +
                                '\')" data-toggle="modal" data-target=".view_waybill" >' +
                                data[0] +
                                "</a>";
                            if (data[1].substring(0, 2) == "CI") {
                                reference += "<br>PREPAID";
                            } else if (data[1].substring(0, 2) == "CD") {
                                reference += "<br>COLLECT";
                            } else {
                                reference += "<br>CHARGE";
                            }
                        }
                    }
                    if (
                        this.payment_details != null &&
                        this.payment_details != ""
                    ) {
                        payment_details = this.payment_details.split("^");
                        for (i = 0; i < payment_details.length; i++) {
                            data = payment_details[i].split("~");
                            if (Number(this.deposit) > 0) {
                                if (reference != "") {
                                    reference += "<br><br>";
                                }
                                if(data[15] !=''  ){
                                    if(pub_dr_id != data[15]){
                                        reference +='<a class="ledger_pub_dr_btn" data-id="'+data[15]+'" data-toggle="modal"  data-target=".view_dr_details_modal" >Delivery Receipt: '+data[16]+'</a>';
                                    }
                                    pub_dr_id=data[15];
                                }else{
                                    if (Number(data[12]) == 1) {
                                        reference += "Ref: <a>" + data[2] + "</a>";
                                        reference += "<br>PASABOX CONV. FEE";
                                    } else {
                                        reference +=
                                            "Waybill/Ref: <a onclick=\"waybill_details('" +
                                            data[1] +
                                            '\')" data-toggle="modal" data-target=".view_waybill" >' +
                                            data[2] +
                                            "</a>";
                                        if (data[1].substring(0, 2) == "CI") {
                                            reference += "<br>PREPAID";
                                        } else if (
                                            data[1].substring(0, 2) == "CD"
                                        ) {
                                            reference += "<br>COLLECT";
                                        } else {
                                            reference += "<br>CHARGE";
                                        }
                                        if (data[13] != "" && data[14] != "") {
                                            reference +=
                                                "<br>" +
                                                data[13] +
                                                " TO " +
                                                data[14];
                                        }
                                    }
                                }
                            } else {
                                if (reference != "") {
                                    reference += "<br><br>";
                                }
                                if (Number(data[3]) > 0) {
                                    reference += "Mode of Payment: ONLINE";
                                    reference += "<br>Bank: " + data[6];
                                    reference += "<br>Reference: " + data[4];
                                    reference += "<br>Date: " + data[5];
                                }
                                if (Number(data[7]) > 0) {
                                    reference += "Mode of Payment: CHECK";
                                    reference += "<br>Bank: " + data[9];
                                    reference += "<br>Check No.: " + data[8];
                                    reference += "<br>Date: " + data[10];
                                }
                                if (Number(data[11]) > 0) {
                                    reference += "Mode of Payment: CASH";
                                }
                            }
                        }
                    }
                    if (this.particulars != null && this.particulars != "") {
                        reference = "Adjustment";
                    }
                    $("#tr_pca_ledger_details_append").after(
                        "<tr>" +
                            "<td>" +
                            this.pdate +
                            "</td>" +
                            "<td>" +
                            particular +
                            "</td>" +
                            "<td>" +
                            reference +
                            "</td>" +
                            "<td>" +
                            debit +
                            "</td>" +
                            "<td>" +
                            credit +
                            "</td>" +
                            "<td>" +
                            number_format_func(rbalance) +
                            "</td>" +
                            "</tr>"
                    );
                }
            });
            $("#tbody_pca_ledger_details_loading").hide();
        },
        error: function () {
            swal({
                icon: "error",
            });
            $("#tbody_pca_ledger_details_loading").hide();
        },
    });
}
$("#tbody_pca_ledger_details").on("click", ".ledger_pub_dr_btn", function () {
    id = $(this).data("id");
    view_pub_dr_details(id);
});
function waybill_details(tcode, count_ref = 1) {
    $("#view_waybill").html("");
    $("#view_customer").html("");
    $("#view_customer_saddress").html("");
    $("#tbl_bill").html("");

    $.getJSON("/pca-get-waybill-details/" + btoa(tcode), function (result) {
        $.each(result, function () {
            subtotal = "-";
            total = "-";

            if (Number(this.vat_amount) + Number(this.amount_due) > 0) {
                total = Number(
                    Number(this.vat_amount) + Number(this.amount_due)
                )
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            }
            if (
                Number(this.freight_amount) - Number(this.discount_amount) >
                0
            ) {
                subtotal = Number(
                    Number(this.freight_amount) - Number(this.discount_amount)
                )
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            }
            if (Number(this.freight_amount) > 0) {
                this.freight_amount = Number(this.freight_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.freight_amount = "-";
            }

            if (Number(this.discount_amount) > 0) {
                this.discount_amount = Number(this.discount_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
                if (Number(this.bd_rebate) > 0) {
                    this.discount_amount += '<small><table width="100%">';
                    this.discount_amount +=
                        '<tr><td style="border-top: 1px solid #000;" align="left">Rebate:</td><td style="border-top: 1px solid #000;">' +
                        Number(this.bd_rebate)
                            .toFixed(2)
                            .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                        "</td></tr>";
                    if (Number(this.bd_disc) > 0) {
                        this.discount_amount +=
                            '<tr><td align="left">Disc: </td><td>' +
                            Number(this.bd_disc)
                                .toFixed(2)
                                .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",") +
                            "</td></tr>";
                    }
                    this.discount_amount += "</table></small>";
                }
            } else {
                this.discount_amount = "-";
            }

            if (Number(this.pickup_charge) > 0) {
                this.pickup_charge = Number(this.pickup_charge)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.pickup_charge = "-";
            }

            if (Number(this.delivery_charge) > 0) {
                this.delivery_charge = Number(this.delivery_charge)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.delivery_charge = "-";
            }

            if (Number(this.othercharges_amount) > 0) {
                this.othercharges_amount = Number(this.othercharges_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.othercharges_amount = "-";
            }
            if (Number(this.declared_value) > 0) {
                this.declared_value = Number(this.declared_value)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.declared_value = "-";
            }
            if (Number(this.insurance_amount) > 0) {
                this.insurance_amount = Number(this.insurance_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.insurance_amount = "-";
            }
            btn_wtax = "";
            if (Number(this.withholdingttax_amount) > 0) {
                this.withholdingttax_amount = Number(
                    this.withholdingttax_amount
                )
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.withholdingttax_amount = "-";
            }

            if (Number(this.finaltax_amount) > 0) {
                this.finaltax_amount = Number(this.finaltax_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.finaltax_amount = "-";
            }
            if (Number(this.vat_amount) > 0) {
                this.vat_amount = Number(this.vat_amount)
                    .toFixed(2)
                    .replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",");
            } else {
                this.vat_amount = "-";
            }

            $("#tbl_bill").append(
                "<tr>" +
                    '<td align="right">' +
                    this.freight_amount +
                    "</td>" +
                    '<td align="right">' +
                    this.discount_amount +
                    "</td>" +
                    '<td align="right">' +
                    subtotal +
                    "</td>" +
                    '<td align="right">' +
                    this.pickup_charge +
                    "</td>" +
                    '<td align="right">' +
                    this.delivery_charge +
                    "</td>" +
                    '<td align="right" data-tcode="' +
                    tcode +
                    '" class="view_oc_btn" data-toggle="modal" data-target=".view_wb_othercharges_modal" >' +
                    this.othercharges_amount +
                    "</td>" +
                    '<td align="right">' +
                    this.declared_value +
                    "</td>" +
                    '<td align="right">' +
                    this.insurance_amount +
                    "</td>" +
                    '<td align="right">' +
                    this.withholdingttax_amount +
                    btn_wtax +
                    "</td>" +
                    '<td align="right">' +
                    this.finaltax_amount +
                    "</td>" +
                    '<td align="right">' +
                    this.vat_amount +
                    "</td>" +
                    '<td align="right">' +
                    total +
                    "</td>" +
                    "</tr>"
            );

            if (this.tcode == "CI") {
                tcode = "PREPAID";
            } else if (this.tcode == "CD") {
                tcode = "COLLECT";
            } else {
                tcode = "CHARGE";
            }
            if (this.tcode == "AR") {
                this.waybill_no = "SOA";
            }
            cancellation_reason = "";
            if (this.waybill_status == "cancel") {
                $("#cancel_watermark").show();
                cancellation_reason =
                    "<br>Reason for cancellation: " + this.cancellation_reason;
            } else {
                $("#cancel_watermark").hide();
            }
            if (this.waybill_status == "waybill_do_not_exist") {
                $("#not_exist_watermark").show();
            } else {
                $("#not_exist_watermark").hide();
            }
            shipper = "";
            if (
                this.sby_fileas != "" &&
                this.sby_fileas != null &&
                this.sby_fileas != "null"
            ) {
                shipper = "Shipper<br>" + this.sby_fileas;

                if (this.sby_email != null && this.sby_email != "") {
                    shipper += "<br>" + this.sby_email;
                }

                if (
                    this.shipper_contactno != null &&
                    this.shipper_contactno != ""
                ) {
                    shipper += "<br>" + this.shipper_contactno;
                }
            }
            shipper_address = "";

            if (
                this.shipper_street != "" &&
                this.shipper_street != null &&
                this.shipper_street != "null"
            ) {
                shipper_address = shipper_address + this.shipper_street + " ";
            }
            if (
                this.shipper_barangay != "" &&
                this.shipper_barangay != null &&
                this.shipper_barangay != "null"
            ) {
                shipper_address = shipper_address + this.shipper_barangay + " ";
            }
            if (
                this.shipper_city != "" &&
                this.shipper_city != null &&
                this.shipper_city != "null"
            ) {
                shipper_address = shipper_address + this.shipper_city + " ";
            }
            if (
                this.shipper_province != "" &&
                this.shipper_province != null &&
                this.shipper_province != "null"
            ) {
                shipper_address = shipper_address + this.shipper_province + " ";
            }
            if (
                this.shipper_postalcode != "" &&
                this.shipper_postalcode != null &&
                this.shipper_postalcode != "null"
            ) {
                shipper_address =
                    shipper_address + this.shipper_postalcode + " ";
            }

            consignee = "";
            if (
                this.cby_fileas != "" &&
                this.cby_fileas != null &&
                this.cby_fileas != "null"
            ) {
                consignee = "Consignee<br>" + this.cby_fileas;
                if (this.cby_email != null && this.cby_email != "") {
                    consignee += "<br>" + this.cby_email;
                }

                if (
                    this.consignee_contactno != null &&
                    this.consignee_contactno != ""
                ) {
                    consignee += "<br>" + this.consignee_contactno;
                }
            }

            charge = "";
            if (
                this.chby_fileas != "" &&
                this.chby_fileas != null &&
                this.chby_fileas != "null"
            ) {
                charge = "Charge<br>" + this.chby_fileas;

                if (this.chby_email != null && this.chby_email != "") {
                    charge += "<br>" + this.chby_email;
                }
                if (
                    this.chby_contact_no != null &&
                    this.chby_contact_no != ""
                ) {
                    charge += "<br>" + this.chby_contact_no;
                }

                $("#tr_view_customer_chname").show();
            } else {
                $("#tr_view_customer_chname").hide();
            }

            $("#tr_view_customer_delivery").hide();
            if (Number(this.delivery) == 1) {
                $("#tr_view_customer_delivery").show();
                if (
                    this.delivery_sector_street == "" ||
                    this.delivery_sector_street == null
                ) {
                    this.delivery_sector_street = "";
                }
                $("#view_customer_delivery").html(
                    "DELIVERY: <u>" +
                        this.delivery_sector_street +
                        " " +
                        this.delivery_sector_barangay +
                        " " +
                        this.delivery_sector_city +
                        " " +
                        this.delivery_sector_province +
                        " " +
                        this.delivery_sector_postalcode
                ) + "</u>";
            }

            $("#tr_view_customer_pickup").hide();
            if (Number(this.pickup) == 1) {
                $("#tr_view_customer_pickup").show();
                if (
                    this.pickup_sector_street == "" ||
                    this.pickup_sector_street == null
                ) {
                    this.pickup_sector_street = "";
                }
                $("#view_customer_pickup").html(
                    "PICKUP: <u>" +
                        this.pickup_sector_street +
                        " " +
                        this.pickup_sector_barangay +
                        " " +
                        this.pickup_sector_city +
                        " " +
                        this.pickup_sector_province +
                        " " +
                        this.pickup_sector_postalcode
                ) + "</u>";
            }

            consignee_address = "";

            if (
                this.consignee_street != "" &&
                this.consignee_street != null &&
                this.consignee_street != "null"
            ) {
                consignee_address =
                    consignee_address + this.consignee_street + " ";
            }
            if (
                this.consignee_barangay != "" &&
                this.consignee_barangay != null &&
                this.consignee_barangay != "null"
            ) {
                consignee_address =
                    consignee_address + this.consignee_barangay + " ";
            }
            if (
                this.consignee_city != "" &&
                this.consignee_city != null &&
                this.consignee_city != "null"
            ) {
                consignee_address =
                    consignee_address + this.consignee_city + " ";
            }
            if (
                this.consignee_province != "" &&
                this.consignee_province != null &&
                this.consignee_province != "null"
            ) {
                consignee_address =
                    consignee_address + this.consignee_province + " ";
            }
            if (
                this.consignee_postalcode != "" &&
                this.consignee_postalcode != null &&
                this.consignee_postalcode != "null"
            ) {
                consignee_address =
                    consignee_address + this.consignee_postalcode + " ";
            }
            if (
                this.reference_no == null ||
                this.reference_no == "" ||
                this.reference_no == "null"
            ) {
                this.reference_no = "";
            } else {
                this.reference_no = "<br>Reference No.: " + this.reference_no;
            }
            if (
                this.discount_coupon == null ||
                this.discount_coupon == "" ||
                this.discount_coupon == "null"
            ) {
                this.discount_coupon = "";
            } else {
                this.discount_coupon =
                    "<br>Discount Coupon: " + this.discount_coupon;
            }
            if (
                this.tracking_no == null ||
                this.tracking_no == "" ||
                this.tracking_no == "null"
            ) {
                this.tracking_no = "";
            }

            $("#view_waybill").html(
                "<br>" +
                    this.waybill_no +
                    "<br>" +
                    tcode +
                    "<br>Tracking: " +
                    this.tracking_no +
                    this.reference_no +
                    this.discount_coupon +
                    "<br>" +
                    this.sbranch_desc +
                    " TO " +
                    this.dbranch_desc +
                    cancellation_reason
            );
            $("#view_customer_sname").html(shipper);
            $("#view_customer_cname").html(consignee);
            $("#view_customer_chname").html(charge);
            $("#view_customer_saddress").html("<br>" + shipper_address);
            if (consignee_address == "null" || consignee_address == null) {
                consignee_address = "";
            }
            $("#view_customer_caddress").html("<br>" + consignee_address);
        });
    });

    $("#view_shipment_details").html("");
    $("#tcargo_qty").html("0");

    $.getJSON(
        "/pca-waybill-shipment-details/" + btoa(tcode),
        function (result) {
            $.each(result, function () {
                $("#view_shipment_details").append(
                    "<tr>" +
                        "<td>" +
                        this.item_description +
                        "</td>" +
                        "<td>" +
                        this.unit_description +
                        "</td>" +
                        "<td>" +
                        this.cargotype_description +
                        "</td>" +
                        "</tr>" +
                        "<tr >" +
                        '<td colspan="3">' +
                        '<table class="table table-striped table-bordered"><tbody id="tbl_' +
                        this.waybill_shipment_id +
                        '"></tbody></table>' +
                        "</td>" +
                        "</tr>"
                );

                shipment_dimensions(this.waybill_shipment_id);

                $("#tcargo").html(
                    "Total Cargo: " +
                        (Number($("#tcargo_qty").html()) +
                            Number(this.quantity))
                );
                $("#tcargo_qty").html(
                    Number($("#tcargo_qty").html()) + Number(this.quantity)
                );
            });
        }
    );
}

function shipment_dimensions(
    waybill_shipment_id,
    count = 0,
    tqty = 0,
    tweight = 0,
    tlength = 0,
    twidth = 0,
    theight = 0,
    tamount = 0,
    total_cw = 0
) {
    $("#tbl_" + waybill_shipment_id).html("");
    $.getJSON(
        "/pca-waybill-shipment-dimensions-details/" + btoa(waybill_shipment_id),
        function (result) {
            $.each(result, function () {
                if (count <= 0) {
                    $("#tbl_" + waybill_shipment_id).append(
                        "<tr>" +
                            "<td></td>" +
                            "<td>Quantity</td>" +
                            "<td>Weight</td>" +
                            "<td>Length</td>" +
                            "<td>Width</td>" +
                            "<td>Height</td>" +
                            //'<td>CW</td>'+
                            //'<td>Price</td>'+
                            //'<td>Total Amount</td>'+
                            "</tr>" +
                            '<tr style="background-color:#E5E7E9;" id="tbl_' +
                            waybill_shipment_id +
                            '_2" >' +
                            "<td></td>" +
                            '<td id="td_qty_' +
                            waybill_shipment_id +
                            '"></td>' +
                            '<td id="tdweight_' +
                            waybill_shipment_id +
                            '"></td>' +
                            '<td id="tdlength_' +
                            waybill_shipment_id +
                            '"></td>' +
                            '<td id="tdwidth_' +
                            waybill_shipment_id +
                            '"></td>' +
                            '<td id="tdheight_' +
                            waybill_shipment_id +
                            '"></td>' +
                            //'<td id="tdcw_'+waybill_shipment_id+'"></td>'+
                            //'<td align="right">-</td>'+
                            //'<td id="tdamount_'+waybill_shipment_id+'" align="right"></td>'+
                            "</tr>"
                    );
                }

                if (Number(this.quantity) > 0) {
                    tqty = tqty + Number(this.quantity);
                } else {
                    this.quantity = "-";
                }
                if (Number(this.weight) > 0) {
                    if (this.each_all == "EACH") {
                        tweight =
                            tweight +
                            Number(this.weight) * Number(this.quantity);
                    } else {
                        tweight = tweight + Number(this.weight);
                    }
                } else {
                    this.weight = "-";
                }
                if (Number(this.lenght) > 0) {
                    tlength =
                        tlength + Number(this.lenght) * Number(this.quantity);
                } else {
                    this.lenght = "-";
                }
                if (Number(this.width) > 0) {
                    twidth =
                        twidth + Number(this.width) * Number(this.quantity);
                } else {
                    this.width = "-";
                }
                if (Number(this.height) > 0) {
                    theight =
                        theight + Number(this.height) * Number(this.quantity);
                } else {
                    this.height = "-";
                }
                // if(Number(this.total_amount) > 0){
                //     tamount=tamount+Number(this.total_amount);
                // }else{
                //     this.tamount='-';
                // }
                if (tqty > 0) {
                    $("#td_qty_" + waybill_shipment_id).html(tqty);
                } else {
                    $("#td_qty_" + waybill_shipment_id).html("-");
                }
                if (tweight > 0) {
                    $("#tdweight_" + waybill_shipment_id).html(tweight);
                } else {
                    $("#tdweight_" + waybill_shipment_id).html("-");
                }
                if (tlength > 0) {
                    $("#tdlength_" + waybill_shipment_id).html(tlength);
                } else {
                    $("#tdlength_" + waybill_shipment_id).html("-");
                }
                if (twidth > 0) {
                    $("#tdwidth_" + waybill_shipment_id).html(twidth);
                } else {
                    $("#tdwidth_" + waybill_shipment_id).html("-");
                }
                if (theight > 0) {
                    $("#tdheight_" + waybill_shipment_id).html(theight);
                } else {
                    $("#tdheight_" + waybill_shipment_id).html("-");
                }
                // if(tamount > 0){
                //     $("#tdamount_"+waybill_shipment_id).html(Number(tamount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ","));
                // }else{
                //     $("#tdamount_"+waybill_shipment_id).html('-');
                // }

                if (this.each_all == "EACH") {
                    each_all_text = "INDIVIDUAL";
                } else {
                    each_all_text = "GROUP";
                }
                chargeable_weight = "-";

                // if(Number(this.chargeable_weight)==4){
                //     chargeable_weight='minimum';
                //     if(this.weight_sd != null && this.dimension_sd != null){
                //         if( Number(this.weight_sd) <= Number(this.dimension_sd) ){

                //             if( this.specialrate_template_id != null ){
                //                 chargeable_weight=(((Number(this.height)*Number(this.lenght)*Number(this.width))/3500)*Number(this.quantity)).toFixed(2)+' <small>[dimension]<br>(minimum)</small>';
                //                 total_cw +=(((Number(this.height)*Number(this.lenght)*Number(this.width))/3500)*Number(this.quantity));
                //             }else{
                //                 chargeable_weight=(Number(this.height)*Number(this.lenght)*Number(this.width)*Number(this.quantity))+' <small>[dimension]<br>(minimum)</small>';
                //                 total_cw +=(Number(this.height)*Number(this.lenght)*Number(this.width)*Number(this.quantity));
                //             }

                //         }else{
                //             if(this.each_all=='EACH'){
                //                 chargeable_weight=(Number(this.weight)*Number(this.quantity)).toFixed(2)+' <small>[weight]<br>(minimum)</small>';
                //                 total_cw +=(Number(this.weight)*Number(this.quantity));
                //             }else{
                //                 chargeable_weight=Number(this.weight).toFixed(2)+' <small>[weight]<br>(minimum)</small>';
                //                 total_cw +=Number(this.weight);
                //             }
                //         }
                //     }

                // }
                // else if(Number(this.chargeable_weight)==3){
                //     chargeable_weight='fixed';

                // }
                // else if(Number(this.chargeable_weight)==2){
                //     if( this.specialrate_template_id != null ){
                //         chargeable_weight=(((Number(this.height)*Number(this.lenght)*Number(this.width))/3500)*Number(this.quantity)).toFixed(2)+'<br><small>(dimension)</small>';
                //         total_cw +=(((Number(this.height)*Number(this.lenght)*Number(this.width))/3500)*Number(this.quantity));
                //     }else{
                //         chargeable_weight=(Number(this.height)*Number(this.lenght)*Number(this.width)*Number(this.quantity))+'<br><small>(dimension)</small>';
                //         total_cw +=(Number(this.height)*Number(this.lenght)*Number(this.width)*Number(this.quantity));
                //     }
                // }
                // else if(Number(this.chargeable_weight)==1){
                //     if(this.each_all=='EACH'){
                //         chargeable_weight=(Number(this.weight)*Number(this.quantity)).toFixed(2)+'<br><small>(weight)</small>';
                //         total_cw +=(Number(this.weight)*Number(this.quantity));
                //     }else{
                //         chargeable_weight=Number(this.weight).toFixed(2)+'<br><small>(weight)</small>';
                //         total_cw +=Number(this.weight);
                //     }
                // }
                // if(total_cw > 0){
                //     $("#tdcw_"+waybill_shipment_id).html(total_cw.toFixed(2));
                // }else{
                //     $("#tdcw_"+waybill_shipment_id).html('-');
                // }

                $("#tbl_" + waybill_shipment_id + "_2").before(
                    "<tr>" +
                        "<td >" +
                        each_all_text +
                        "</td>" +
                        "<td >" +
                        this.quantity +
                        "</td>" +
                        "<td >" +
                        this.weight +
                        "</td>" +
                        "<td >" +
                        this.lenght +
                        "</td>" +
                        "<td >" +
                        this.width +
                        "</td>" +
                        "<td >" +
                        this.height +
                        "</td>" +
                        //'<td >'+chargeable_weight+'</td>'+
                        //'<td align="right">'+Number(this.price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+'</td>'+
                        //'<td align="right">'+Number(this.total_amount).toFixed(2).replace(/\B(?=(\d{3})+(?!\d)+\.)/g, ",")+'</td>'+
                        "</tr>"
                );

                count++;
            });
        }
    );
}

$(".add_agent_modal_btn").click(function () {
    clear_add_agent_form();
    $('input[name="agent_id"]').val("");
    $("#add_agent_modal_h4").html(
        '<i class="fa fa-plus-circle"></i> ADD AGENT'
    );
});
function clear_add_agent_form() {
    $('input[name="agent_name"]').val("");
    $('input[name="agent_street"]').val("");
    $('input[name="agent_cperson"]').val("");
    $('input[name="agent_cperson_no"]').val("");
    $("#add_agent_form_loading").hide();
    get_agent_city();
}
function get_agent_city(city = "", brgy = "", province = "") {
    $('select[name="agent_city"]').html(
        '<option selected value="">--Select City/Municipality--</option>'
    );
    $.getJSON("/pca-city", function (result) {
        $('select[name="agent_city"]').html(
            '<option selected value="">--Select City/Municipality--</option>'
        );
        $.each(result, function () {
            if (province != this.province_name) {
                if (province != "") {
                    $('select[name="agent_city"]').append("</optgroup>");
                }
                $('select[name="agent_city"]').append(
                    '<optgroup label="' + this.province_name + '">'
                );
            }

            $('select[name="agent_city"]').append(
                '<option value="' +
                    this.cities_id +
                    '">' +
                    this.cities_name +
                    "</option>"
            );
            province = this.province_name;
        });
        if (province != "") {
            $('select[name="agent_city"]').append("</optgroup>");
        }

        $('select[name="agent_city"]').val(city).trigger("change");
        $('input[name="agent_default_brgy"]').val(brgy);
    });
}

$('select[name="agent_city"]').change(function () {
    get_agent_brgy();
});
function get_agent_brgy() {
    $('select[name="agent_brgy"]').html(
        '<option selected value="">--Select Barangay--</option>'
    );
    if ($('select[name="agent_city"]').val() != "") {
        $.getJSON(
            "/pca-brgy/" + btoa($('select[name="agent_city"]').val()),
            function (result) {
                $('select[name="agent_brgy"]').html(
                    '<option selected value="">--Select Barangay--</option>'
                );
                $.each(result, function () {
                    $('select[name="agent_brgy"]').append(
                        '<option value="' +
                            this.sectorate_no +
                            '">' +
                            this.barangay +
                            "</option>"
                    );
                });

                $('select[name="agent_brgy"]')
                    .val($('input[name="agent_default_brgy"]').val())
                    .trigger("change");
            }
        );
    } else {
        $('select[name="agent_brgy"]').trigger("change");
    }
}

function get_pca_agent_list() {
    $("#tbl_agent_list_loading").show();
    $("#tbl_agent_list").html("");
    $.getJSON(
        "/pca-agent-list/" +
            btoa($('input[name="agent_pca_no"]').val()) +
            "/" +
            btoa("ALL"),
        function (result) {
            $(".table_agent_list").DataTable().destroy();
            $("#tbl_agent_list").html("");
            $("#tbl_agent_list_loading").show();
            $.each(result, function () {
                $("#tbl_agent_list").append(
                    "<tr>" +
                        "<td>" +
                        this.publication_agent_name +
                        "</td>" +
                        "<td>" +
                        this.street +
                        " " +
                        this.barangay +
                        " " +
                        this.cities_name +
                        " " +
                        this.province_name +
                        "</td>" +
                        "<td>" +
                        this.contact_person +
                        "<br>" +
                        this.contact_no +
                        "</td>" +
                        '<td><button data-toggle="modal"  data-target=".add_agent_modal" data-id="' +
                        this.publication_agent_id +
                        '" class="btn btn-xs btn-success edit_agent_btn"><i class="fa fa-pencil"></i> Edit</button></td>' +
                        "</tr>"
                );
            });

            $(".table_agent_list").DataTable().draw();
            $("#tbl_agent_list_loading").hide();
        }
    );
}

$("#tbl_agent_list").on("click", ".edit_agent_btn", function () {
    $('input[name="agent_id"]').val($(this).data("id"));
    $("#add_agent_modal_h4").html('<i class="fa fa-pencil"></i> EDIT AGENT');
    $("#add_agent_form_loading").show();
    $('input[name="agent_name"]').val("");
    $('input[name="agent_street"]').val("");
    $('input[name="agent_cperson"]').val("");
    $('input[name="agent_cperson_no"]').val("");

    $.getJSON(
        "/pca-agent-list/" +
            btoa($('input[name="agent_pca_no"]').val()) +
            "/" +
            btoa($(this).data("id")),
        function (result) {
            $("#add_agent_form_loading").show();
            $.each(result, function () {
                $('input[name="agent_name"]').val(this.publication_agent_name);
                $('input[name="agent_street"]').val(this.street);
                $('input[name="agent_cperson"]').val(this.contact_person);
                $('input[name="agent_cperson_no"]').val(this.contact_no);
                get_agent_city(this.city_id, this.sectorate_no);
            });
            $("#add_agent_form_loading").hide();
        }
    );
});
$(".add_agent_form").submit(function () {
    event.preventDefault();
    document.getElementById("add_agent_form_btn").disabled = true;
    $("#add_agent_form_loading").show();

    $.ajax({
        url: "/save-pca-agent",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (result) {
            Swal.fire({
                icon: result.icon,
                title: result.title,
                text: result.message,
            }).then((res) => {
                if (
                    Number(result.msg_type) == 2 ||
                    Number(result.msg_type) == 0
                ) {
                } else {
                    if ($('input[name="agent_id"]').val() != "") {
                        $(".add_agent_modal_close").click();
                    } else {
                        clear_add_agent_form();
                    }
                    get_pca_agent_list();
                }
            });
            document.getElementById("add_agent_form_btn").disabled = false;
            $("#add_agent_form_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            document.getElementById("add_agent_form_btn").disabled = false;
            $("#add_agent_form_loading").hide();
        },
    });
});

$(".form_pub_transaction_add").submit(function () {
    event.preventDefault();
    document.getElementById("form_pub_transaction_add_btn").disabled = true;
    $("#div_publication_transaction_loading").show();

    $.ajax({
        url: "/add-publication-transaction",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (result) {
            Swal.fire({
                icon: result.icon,
                title: result.title,
                text: result.message,
            }).then((res) => {
                if (
                    Number(result.msg_type) == 2 ||
                    Number(result.msg_type) == 0
                ) {
                } else {
                    $(".tab_pub_transction_add").trigger("click");
                }
            });
            document.getElementById(
                "form_pub_transaction_add_btn"
            ).disabled = false;
            $("#div_publication_transaction_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            document.getElementById(
                "form_pub_transaction_add_btn"
            ).disabled = false;
            $("#div_publication_transaction_loading").hide();
        },
    });
});

$(".publication_download_csv_template_btn").click(function () {
    window.open(
        "/publication-csv-template-download/" +
            btoa($('input[name="pub_transaction_pno"]').val())
    );
});

$("#input-file-pub").change(function () {
    $("#upload_csv_dr_form_btn").click();
});
$(".upload_csv_dr_form").submit(function () {
    event.preventDefault();
    document.getElementById("form_pub_transaction_add_btn").disabled = true;
    $("#div_publication_transaction_loading").show();
    $.ajax({
        url: "/publication-import-delivery",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.type == "success") {
                data = result.row_list;
                $('input[name="pub_transaction_add_date"]').val(
                    data["issue_date"]
                );
                $("#tbl_pub_add_transaction").html("");
                $.each(data["delivery"], function (index) {
                    list = data["delivery"][index];
                    btn_save_agent = "";
                    if (list[9].toString().indexOf("NONE") > -1) {
                        btn_save_agent =
                            '<i id="pub_save_agent_list_btn_' +
                            list[9] +
                            '" ><br><br><button type="button" data-id="' +
                            list[9] +
                            '" class="btn btn-xs btn-info pub_save_agent_list_btn pull-right"><i class="fa fa-check"></i> Save this in Agent List</button></i>';
                    }
                    btn_save_agent_address = "";
                    if (list[10] != "NONE") {
                        btn_save_agent_address =
                            '<i id="pub_save_agent_address_btn_' +
                            list[9] +
                            '" ><br><br><button type="button" data-sector="' +
                            list[10] +
                            '" data-id="' +
                            list[9] +
                            '" class="btn btn-xs btn-danger pub_save_agent_address_btn pull-right"><i class="fa fa-check"></i> Save this as current address</button></i>';
                    }

                    $("#tbl_pub_add_transaction").append(
                        '<tr id="tr_pub_add_transaction_' +
                            list[9] +
                            '">' +
                            "<td>" +
                            '<input type="hidden" value="' +
                            list[9] +
                            '" id="pub_add_transaction_agent_id_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_id[]" name="pub_add_transaction_agent_id[]" />' +
                            '<input type="hidden" value="' +
                            list[0] +
                            '" id="pub_add_transaction_agent_name_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_name[]" />' +
                            '<input type="hidden" value="' +
                            list[1] +
                            '" id="pub_add_transaction_cperson_' +
                            list[9] +
                            '" name="pub_add_transaction_cperson[]" />' +
                            '<input type="hidden" value="' +
                            list[2] +
                            '" id="pub_add_transaction_cperson_no_' +
                            list[9] +
                            '" name="pub_add_transaction_cperson_no[]" />' +
                            '<input type="hidden" value="' +
                            list[3] +
                            '" id="pub_add_transaction_agent_address_street_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_address_street[]" />' +
                            '<input type="hidden" value="' +
                            list[4] +
                            '" id="pub_add_transaction_agent_address_brgy_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_address_brgy[]" />' +
                            '<input type="hidden" value="' +
                            list[5] +
                            '" id="pub_add_transaction_agent_address_city_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_address_city[]" />' +
                            '<input type="hidden" value="' +
                            list[6] +
                            '" id="pub_add_transaction_agent_address_province_' +
                            list[9] +
                            '" name="pub_add_transaction_agent_address_province[]" />' +
                            list[0] +
                            btn_save_agent +
                            "</td>" +
                            "<td>" +
                            list[1] +
                            "</td>" +
                            "<td>" +
                            list[2] +
                            "</td>" +
                            "<td>" +
                            list[3] +
                            " " +
                            list[4] +
                            " " +
                            list[5] +
                            " " +
                            list[6] +
                            btn_save_agent_address +
                            "</td>" +
                            '<td><input min="0" value="' +
                            list[7] +
                            '" required class="form-control" type="number" name="pub_add_transaction_main_qty[]" /></td>' +
                            '<td><input  min="0" value="' +
                            list[8] +
                            '" required class="form-control" type="number" name="pub_add_transaction_tabloid_qty[]" /></td>' +
                            '<td><button type="button" data-id="' +
                            list[9] +
                            '" class="btn btn-md btn-danger pub_add_transaction_agent_remove"><i class="fa fa-trash"></i></button></td>' +
                            "</tr>"
                    );
                });
                $("#input-file-pub").val(null);
            } else {
                new swal({
                    text: result.message,
                    icon: result.icon,
                    title: result.title,
                });
            }
            document.getElementById(
                "form_pub_transaction_add_btn"
            ).disabled = false;
            $("#div_publication_transaction_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            document.getElementById(
                "form_pub_transaction_add_btn"
            ).disabled = false;
            $("#div_publication_transaction_loading").hide();
        },
    });
});
$("#tbl_pub_add_transaction").on(
    "click",
    ".pub_save_agent_address_btn",
    function () {
        id = $(this).data("id");
        sector = $(this).data("sector");

        if (
            confirm(
                "Are you sure you want to SAVE this as current address of this agent?"
            )
        ) {
            $.ajax({
                url: "/pub-transaction-save-agent-address",
                type: "GET",
                data: {
                    id: btoa($("#pub_add_transaction_agent_id_" + id).val()),
                    street: btoa(
                        $(
                            "#pub_add_transaction_agent_address_street_" + id
                        ).val()
                    ),
                    sector: btoa(sector),
                },
                dataType: "json",
                success: function (result) {
                    Swal.fire({
                        icon: result.type,
                        title: result.title,
                        text: result.message,
                    }).then((res) => {
                        if (Number(result.msg_type) == 1) {
                            $("#pub_save_agent_address_btn_" + id).hide();
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    new swal({
                        text: "An error occured. Please contact Customer Service.",
                        icon: "error",
                        title: jqXHR.responseJSON.message,
                    });
                },
            });
        }
    }
);

$("#tbl_pub_add_transaction").on(
    "click",
    ".pub_save_agent_list_btn",
    function () {
        id = $(this).data("id");
        if (confirm("Are you sure you want to SAVE this?")) {
            $.ajax({
                url: "/pub-transaction-save-agent",
                type: "GET",
                data: {
                    name: btoa(
                        $("#pub_add_transaction_agent_name_" + id).val()
                    ),
                    cperson: btoa(
                        $("#pub_add_transaction_cperson_" + id).val()
                    ),
                    cperson_no: btoa(
                        $("#pub_add_transaction_cperson_no_" + id).val()
                    ),
                    street: btoa(
                        $(
                            "#pub_add_transaction_agent_address_street_" + id
                        ).val()
                    ),
                    brgy: btoa(
                        $("#pub_add_transaction_agent_address_brgy_" + id).val()
                    ),
                    city: btoa(
                        $("#pub_add_transaction_agent_address_city_" + id).val()
                    ),
                    province: btoa(
                        $(
                            "#pub_add_transaction_agent_address_province_" + id
                        ).val()
                    ),
                    pca_no: btoa($('input[name="pub_transaction_pno"]').val()),
                },
                dataType: "json",
                success: function (result) {
                    Swal.fire({
                        icon: result.type,
                        title: result.title,
                        text: result.message,
                    }).then((res) => {
                        if (Number(result.msg_type) == 1) {
                            $("#pub_save_agent_list_btn_" + id).hide();
                            $("#pub_add_transaction_agent_id_" + id).val(
                                result.id
                            );
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    new swal({
                        text: "An error occured. Please contact Customer Service.",
                        icon: "error",
                        title: jqXHR.responseJSON.message,
                    });
                },
            });
        }
    }
);

$(".edit_dr_details_form").submit(function () {
    event.preventDefault();
    document.getElementById("edit_dr_details_btn").disabled = true;
    $("#edit_dr_details_loading").show();

    $.ajax({
        url: "/update-publication-transaction",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (result) {
            Swal.fire({
                icon: result.icon,
                title: result.title,
                text: result.message,
            }).then((res) => {
                if (
                    Number(result.msg_type) == 2 ||
                    Number(result.msg_type) == 0
                ) {
                } else {
                    $(".edit_dr_details_modal_close").click();
                    get_pub_dr_list();
                }
            });
            document.getElementById("edit_dr_details_btn").disabled = false;
            $("#edit_dr_details_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            new swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            document.getElementById("edit_dr_details_btn").disabled = false;
            $("#edit_dr_details_loading").hide();
        },
    });
});
$(".sort_agent_modal_btn").click(function(){
    sort_list();
});
function sort_list(count_sort=1){

    $("#sort_agent_form_btn").prop("disabled", true);
    $("#sort_agent_list_loading").show();
    $("#sort_agent_list").html('');
    $.getJSON("/pca-agent-list/"+btoa($('input[name="pub_transaction_pno"]').val())+"/" +btoa('SORT'),function (result){

        $("#sort_agent_form_btn").prop("disabled", true);
        $("#sort_agent_list_loading").show();
        $("#sort_agent_list").html('');
        $.each(result, function(){
            $("#sort_agent_list").append('<tr id="'+count_sort+'"><td><input name="sort_agent[]" value="'+this.publication_agent_id+'" type="hidden">'+this.publication_agent_name+'</td></tr>');
            count_sort++;
        });

        $("#sort_agent_form_btn").prop("disabled", false);
        $("#sort_agent_list_loading").hide();
        $("#table-agent-sort").tableDnD();
    });
}
$(".sort_agent_form").submit(function () {
    event.preventDefault();
    document.getElementById("sort_agent_form_btn").disabled = true;
    $("#sort_agent_list_loading").show();
    $.ajax({
        url: "/save-agent-sorting",
        type: "POST",
        data: new FormData(this),
        contentType: false,
        processData: false,
        success: function (result) {

            Swal.fire({
                icon: result.icon,
                title: result.title,
                text: result.message,
            }).then((res) => {
                // if (
                //     Number(result.msg_type) == 2 ||
                //     Number(result.msg_type) == 0
                // ) {
                // }else {

                // }
            });
            document.getElementById("sort_agent_form_btn").disabled = false;
            $("#sort_agent_list_loading").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal({
                text: "An error occured. Please contact Customer Service.",
                icon: "error",
                title: jqXHR.responseJSON.message,
            });
            document.getElementById("sort_agent_form_btn").disabled = false;
            $("#sort_agent_list_loading").hide();
        },
    });

});
