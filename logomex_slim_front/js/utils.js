var BACKENDHOST = 'http://localhost/logomex/logomex_slim';

window.utils = {

    // Asynchronously load templates located in separate .html files
    loadTemplate: function(views, callback) {

        var deferreds = [];

        $.each(views, function(index, view) {
            //console.log(index, view);
            if (window[view]) {
                deferreds.push($.get('tpl/' + view + '.html', function(data) {
                    //console.log('view data:', data);
                    window[view].prototype.template = _.template(data);
                    //console.log(view, window[view]);
                }));
            } else {
                alert(view + " not found");
            }
        });

        $.when.apply(null, deferreds).done(callback);
    },

    uploadFile: function (file, callbackSuccess) {
        var self = this;
        var data = new FormData();
        data.append('file', file);
        $.ajax({
            url: 'api/upload.php',
            type: 'POST',
            data: data,
            processData: false,
            cache: false,
            contentType: false
        })
        .done(function () {
            console.log(file.name + " uploaded successfully");
            callbackSuccess();
        })
        .fail(function () {
            self.showAlert('Error!', 'An error occurred while uploading ' + file.name, 'alert-error');
        });
    },

    displayValidationErrors: function (messages) {
        for (var key in messages) {
            if (messages.hasOwnProperty(key)) {
                this.addValidationError(key, messages[key]);
            }
        }
        this.showAlert('Aviso!', 'Arregle los errores de validación e intente de nuevo', 'alert-warning');
    },

    addValidationError: function (field, message) {
        var controlGroup = $('#' + field).parent().parent();
        controlGroup.addClass('error');
        $('.help-inline', controlGroup).html(message);
    },

    removeValidationError: function (field) {
        var controlGroup = $('#' + field).parent().parent();
        controlGroup.removeClass('error');
        $('.help-inline', controlGroup).html('');
    },

    showAlert: function(title, text, klass) {
        $('.alert').removeClass("alert-error alert-warning alert-success alert-info");
        $('.alert').addClass(klass);
        $('.alert').html('<strong>' + title + '</strong> ' + text);
        $('.alert').show();
    },

    hideAlert: function() {
        $('.alert').hide();
    },
    
    changeAddButton: function(link, label) {
        $('#main-new-link').attr('href', link);
        $('#main-new-label').html(label);
    },
            
    getHash: function() {
        var location = window.location.hash,
            hash = "envios";
        if (location !== "#" && location !== " " && location !== "") {
            console.log(location);
            var arLocation = location.split("#"),
                arHash = arLocation[1].split("/");
            hash = arHash[0];
        }
        return hash;
    }

};
