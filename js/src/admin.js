var account = (function($, window, document, undefined){
    'use strict';

    return {
        // -- Fonction public
        run : function(){
            $('#google_recaptcha').on('change', function() {
                if($(this).is(':checked')) {
                    $('#recaptcha').collapse('show');
                    $('#recaptchaApiKey').rules('add',{required: true});
                    $('#recaptchaSecret').rules('add',{required: true});
                }
                else {
                    $('#recaptcha').collapse('hide');
                    $('#recaptchaApiKey').rules('remove');
                    $('#recaptchaSecret').rules('remove');
                }
            });
        }
    };
})(jQuery, window, document);