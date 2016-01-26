window.SegmentsView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        var self = this;
        console.log('segments', this.model.toJSON());
        this.getClients(function(resp){
            if(resp)
                $(self.el).html(self.template(resp));
            return this;
        });
        //$(this.el).html(this.template(this.model.toJSON()));
        
    },
    
    getClients: function(callback) {
        var self = this;
        var clientList = new ClientsCollection();
        clientList.fetch({
            success: function(){
                console.log(clientList.toJSON());
                console.log(self.model.toJSON());
                var params = {
                    clients: clientList.toJSON(),
                    segments: self.model.toJSON()
                };
                callback(params);
            },
            error: function(e){
                console.log('an error ocurred', e);
                callback(false);
            }
        });
    },

    events: {
        "change"        : "change",
        "click .save"   : "beforeSave",
        "click .delete" : "deleteContacts",
        "drop #picture" : "dropHandler"
    },

    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target;
        var change = {};
        change[target.name] = target.value;
        this.model.set(change);

        // Run validation rule (if any) on changed item
        var check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },

    beforeSave: function () {
        var self = this;
        var check = this.model.validateAll();
        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        } else {
            this.saveSegment();
        }
        return false;
    },

    saveSegment: function () {
        var self = this;
        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('sgementos/' + model.id, false);
                utils.showAlert('Éxito!', 'Contacto guardado con éxito', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'Ha ocurrido un error mientras se guardaba el registro', 'alert-error');
            }
        });
    },

    deleteSegment: function () {
        this.model.destroy({
            success: function () {
                alert('Segmento borrado');
                window.history.back();
            }
        });
        return false;
    }

});