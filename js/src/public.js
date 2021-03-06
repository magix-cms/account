var account = (function($, window, document, undefined){
    'use strict';

    return {
        login: function (url,iso) {
            $('#pw_email_ac').rules('add',{
                remote: {
                    url: url+'/'+iso+'/account/rstpwd/',
                    type: 'get',
                    data: {
                        v_email: function() {
                            return $( "#pw_email_ac" ).val();
                        }
                    }
                },
                messages: {
                    remote: $.validator.messages.emailNotExist
                }
            });
        },
        signup: function (url,iso) {
            $('#email_ac').rules('add',{
                remote: {
                    url: url+'/'+iso+'/account/signup/',
                    type: 'get',
                    data: {
                        v_email: function() {
                            return $( "#email_ac" ).val();
                        }
                    }
                },
                messages: {
                    remote: $.validator.messages.emailExist
                }
            });
        },
        config: function (url,iso,hash) {
            $('#email_ac').rules('add',{
                remote: {
                    url: url+'/'+iso+'/account/'+hash+'/config/',
                    type: 'get',
                    data: {
                        v_email: function() {
                            return $( "#email_ac" ).val();
                        }
                    }
                },
                messages: {
                    remote: $.validator.messages.emailExist
                }
            });
        }
    };
})(jQuery, window, document);