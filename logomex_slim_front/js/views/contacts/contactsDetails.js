window.ContactsView = Backbone.View.extend({

    initialize: function () {
        var self = this;
        this.clients = new ClientsCollection();
        this.clients.fetch({
            success: function() {
                self.render();
            },
            error: function(e) {
                console.log('error initializing contacts', e);
            }
        });
        //this.render();
    },

    render: function () {
        var self = this;
        console.log('contacts', this.model.toJSON());
        
        var params = {
          clients: this.clients.toJSON(), 
          contact: this.model.toJSON()
        };
        $(self.el).html(self.template(params));
        
        
        /***
        this.getAll(function(resp){
            if(resp)
                $(self.el).html(self.template(resp));
            return this;
        });
        **/
        
        
        //$(this.el).html(this.template(this.model.toJSON()));
        
    },
            
    getAll: function(callback) {
        var self = this;
        this.getClients(function(response){
            if (response){
                callback({
                    clients: self.clients.toJSON(),
                    contact: self.model.toJSON()
                });
            } else {
                callback(false);
            }
        });
    },
    
    getClients: function(callback) {
        var self = this;
        this.clients.fetch({
            success: function(){
                console.log(self.clients.toJSON());
                console.log(self.model.toJSON());
                callback(true);
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
            this.saveContacts();
        }
        return false;
    },

    saveContacts: function () {
        var self = this;
        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('contactos/' + model.id, false);
                utils.showAlert('Success!', 'Contacts saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },

    deleteContacts: function () {
        this.model.destroy({
            success: function () {
                alert('Contacts deleted successfully');
                window.history.back();
            }
        });
        return false;
    }

});