"use strict";

// Class definition
var KTSignupGeneral = function() {
    // Elements
    var form;
    var submitButton;
    var validator;
    var passwordMeter;

    // Handle form
    var handleForm  = function(e) {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        validator = FormValidation.formValidation(
			form,
			{
				fields: {
					'first-name': {
						validators: {
							notEmpty: {
								message: 'Ingrese Nombre'
							}
						}
                    },
                    'last-name': {
						validators: {
							notEmpty: {
								message: 'Ingrese Apellido'
							}
						}
					},
					'email': {
                        validators: {
							notEmpty: {
								message: 'Ingrese E-mail'
							},
                            emailAddress: {
								message: 'Email ingresado es Incorrecto'
							}
						}
					},
                    'password': {
                        validators: {
                            notEmpty: {
                                message: 'Ingrese Password'
                            },
                            callback: {
                                message: 'Ingrese Password Correcto',
                                callback: function(input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm-password': {
                        validators: {
                            notEmpty: {
                                message: 'Se requiere la confirmación de la contraseña.'
                            },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="password"]').value;
                                },
                                message: 'La contraseña y su confirmación no son iguales.'
                            }
                        }
                    },
                    'toc': {
                        validators: {
                            notEmpty: {
                                message: 'Usted debe Aceptar terminos y condiciones'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger({
                        event: {
                            password: false
                        }  
                    }),
					bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
				}
			}
		);

        // Handle form submit
        // submitButton.addEventListener('click', function (e) {
        //     e.preventDefault();

        //     validator.revalidateField('password');

        //     validator.validate().then(function(status) {
		//         if (status == 'Valid') {
        //             // Show loading indication
        //             submitButton.setAttribute('data-kt-indicator', 'on');

        //             // Disable button to avoid multiple click 
        //             submitButton.disabled = true;

        //             // Simulate ajax request
        //             setTimeout(function() {
        //                 // Hide loading indication
        //                 submitButton.removeAttribute('data-kt-indicator');

        //                 // Enable button
        //                 submitButton.disabled = false;

        //                 // Show message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        //                 Swal.fire({
        //                     text: "You have successfully reset your password!",
        //                     icon: "success",
        //                     buttonsStyling: false,
        //                     confirmButtonText: "Ok, got it!",
        //                     customClass: {
        //                         confirmButton: "btn btn-primary"
        //                     }
        //                 }).then(function (result) {
        //                     if (result.isConfirmed) { 
        //                         form.reset();  // reset form                    
        //                         passwordMeter.reset();  // reset password meter
        //                         //form.submit();
        //                     }
        //                 });
        //             }, 1500);   						
        //         } else {
        //             // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        //             Swal.fire({
        //                 text: "Sorry, looks like there are some errors detected, please try again.",
        //                 icon: "error",
        //                 buttonsStyling: false,
        //                 confirmButtonText: "Ok, got it!",
        //                 customClass: {
        //                     confirmButton: "btn btn-primary"
        //                 }
        //             });
        //         }
		//     });
        // });

        // Handle password input
        form.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length > 0) {
                validator.updateFieldStatus('password', 'NotValidated');
            }
        });
    }

    // Password input validation
    var validatePassword = function() {
        return  (passwordMeter.getScore() === 100);
    }

    // Public functions
    return {
        // Initialization
        init: function() {
            // Elements
            form = document.querySelector('#kt_sign_up_form');
            submitButton = document.querySelector('#kt_sign_up_submit');
            passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

            handleForm ();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function() {
    KTSignupGeneral.init();
});
