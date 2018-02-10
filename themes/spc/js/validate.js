(function ($) {
	var VALIDATE = {};
	
	/**
	 * Initialize Validate on Contact Form.
	 * 
	 * @returns Void
	 */
	VALIDATE.validateContactForm = function() {
		$('#contact-message-contact-us-form').bootstrapValidator({
			message: 'This value is not valid',
			fields: {
				'field_email_address[0][value]': {
					validators: {
						emailAddress: {
							message: 'Please enter a valid email address'
						}
					}
				},
			},
			group: '.js-form-item'
		});
	}
	
	/**
	 * Initialize Validate on Contact Form.
	 * 
	 * @returns Void
	 */
	VALIDATE.validateApplyMembershipForm = function() {
		var $input = $('#edit-field-work-phone-number-0-value')
			
		$input.intlTelInput({
			autoPlaceholder: false,
			preferredCountries: ['us'],
			initialCountry: "us",
		});
				
		$('#contact-message-apply-for-membership-form').bootstrapValidator({
			message: 'This value is not valid',
			fields: {
				'field_primary_email_address[0][value]': {
					validators: {
						emailAddress: {
							message: 'Please enter a valid email address'
						}
					}
				},
				'field_membership_class': {
					validators: {
						callback: {
                            message: 'Please select a value',
                            callback: function(value, validator, $field) {
                                return (value !== '_none') ? true : false;
                            }
                        }
					}
				},
				'field_applicant_type': {
					validators: {
						callback: {
                            message: 'Please select a value',
                            callback: function(value, validator, $field) {
                                return (value !== '_none') ? true : false;
                            }
                        }
					}
				},
				'field_statement_of_eligibility[value]': {
					validators: {
						notEmpty: {
                            message: 'Checkbox must be checked'
                        }
					}
				},
				'field_work_phone_number[0][value]': {
					validators: {
						callback: {
                            message: 'The phone number is not valid',
                            callback: function(value, validator, $field) {
                                return value === '' || $field.intlTelInput('isValidNumber');
                            }
                        }
					}
				},
			},
			group: '.js-form-item'
		});
	}
	
	$(document).ready(function(){
		
	});
	
	$(window).load(function() {
		VALIDATE.validateContactForm();
		VALIDATE.validateApplyMembershipForm();
    });
})(jQuery);