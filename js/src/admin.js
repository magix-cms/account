var account = (function($, window, document, undefined){
    'use strict';
    /**
     * Initialise the display of notice message
     * @param {html} m - message to display.
     * @param {int|boolean} [timeout=false] - Time before hiding the message.
     * @param {string|boolean} [sub=false] - Sub-controller name to select the container for the message.
     */
    function initAlert(m,timeout,sub) {
        sub = typeof sub !== 'undefined' ? sub : false;
        timeout = typeof timeout !== 'undefined' ? timeout : false;
        if(sub) $.jmRequest.notifier = { cssClass : '.mc-message-'+sub };
        $.jmRequest.initbox(m,{ display:true });
        if(timeout) window.setTimeout(function () { $('.mc-message .alert').alert('close'); }, timeout);
    }
    /**
     * Assign the correct success handler depending of the validation class attached to the form
     * @param {string} f - id of the form.
     * @param {string} controller - The name of the script to be called by te form.
     */
    function subsFormDelete(f,controller){
        var options = {
            handler: "submit",
            url: $(f).attr('action'),
            method: 'post',
            form: $(f),
            resetForm: false,
            success: function (d) {
                if(d.debug !== undefined && d.debug !== '') {
                    initAlert(d.debug);
                }
                else if(d.notify !== undefined && d.notify !== '') {
                    initAlert(d.notify,4000);
                }
            }
        };
        options.resetForm = true;
        //controller = sub?sub:controller.substr(1,(controller.indexOf('.')-1));
        //controller = sub?sub:controller;

        options.success = function (d) {
            $('#delete_modal_subs').modal('hide');
            //$.jmRequest.notifier.cssClass = '.mc-message-'+controller;
            $.jmRequest.notifier = {
                cssClass : '.mc-message-'+controller
            };
            initAlert(d.notify,4000);
            if(d.status && d.result) {
                if(typeof d.result.id === 'string' || typeof d.result.id === 'number') {
                    let ids = 0;
                    if(typeof d.result.id === 'string') {
                        ids = d.result.id.split(',');
                    }
                    else if(typeof d.result.id === 'number') {
                        ids = [d.result.id];
                    }
                    let nbr = 0;
                    let table = $('#table-'+controller);
                    let container = null;

                    for(var i = 0;i < ids.length; i++) {
                        container = $('#'+controller+'_' + ids[i]).parent();
                        $('#'+controller+'_' + ids[i]).next('.collapse').remove();
                        $('#'+controller+'_' + ids[i]).remove();
                        if(table.is("table")) {
                            nbr = table.find('tbody').find('tr').length;
                        }
                        else if(table.is("ul") && !nbr) {
                            nbr = table.children('li').length;
                        }
                    }

                    container.trigger('change');

                    if(table.is("table") && !nbr) {
                        table.addClass('hide').next('.no-entry').removeClass('hide');
                    }
                    else if(table.is("ul") && !nbr) {
                        table.next('.no-entry').removeClass('hide');
                    }
                    $('.nbr-'+controller).text(nbr);
                }
                else {
                    console.log(d.result);
                }
            }
        };
        $.jmRequest(options);
    }
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
        },
        runEdit: function(){
            $('#delete_form_subs').validate({
                ignore: [],
                onsubmit: true,
                event: 'submit',
                submitHandler: function(f,e) {
                    e.preventDefault();
                    subsFormDelete(f,'subs');
                    return false;
                }
            });

            $('.extend_modal_subs').each(function(){
                $(this).off().on('click',function(e){
                    e.preventDefault();
                    var modal = $(this).data('target'),
                        controller = $(this).data('controller'),
                        //sub = $(this).data('sub') ? $(this).data('sub') : false,
                        id = $(this).data('id_subs') ? $(this).data('id_subs') : false;

                    if(modal && id && controller) {
                        $(modal+' input[type="hidden"]').val(id);
                        $(modal).modal('show');
                    } else {
                        $('#error_modal').modal('show');
                    }
                });
            });
        }
    };
})(jQuery, window, document);