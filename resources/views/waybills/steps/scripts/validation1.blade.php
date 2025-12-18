<script>
$(document).ready(function(){
    var form1 = $('#form-step-1').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: false,
		ignore: "",
		rules: {
            lname: {
                required: true,
            },
            fname: {
                required: true,
            },
            gender: {
				required: true,
			},
		    email: {
				required: true,
				email:true
			},		
			contact_no: {
				required: true,
				phone: 'required'
			},		
			address_label: {
				required: true
			},
			province: {
				required: true
			},
			city: {
				required: true
			},
			barangay: {
				required: true
			},
			street: {
				required: true
            },
            postal_code: {
				required: true
			}
		},
			
		messages: {
            gender: "Please choose gender",
            email: {
				required: "Please provide a valid email.",
				email: "Please provide a valid email."
			},
            province: "Please choose province",
            city: "Please choose city",
            barangay: "Please provide barangay"
		},	
		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},
			
		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
			$(e).remove();
		},
			
		errorPlacement: function (error, element) {
			if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
			else error.insertAfter(element.parent());
		},	
		submitHandler: function (form) {
		},
		invalidHandler: function (form) {
		}

    });
})
</script>