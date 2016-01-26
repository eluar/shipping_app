window.Contacts = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/contactos",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Nombre</b> est&aacute; vacio"};
        };

        this.validators.clientes_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Cliente</b>"};
        };

        this.validators.email = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Email</b> est&aacute; vacio"};
        };
    },

    validateItem: function (key) {
        return (this.validators[key]) ? this.validators[key](this.get(key)) : {isValid: true};
    },

    // TODO: Implement Backbone's standard validate() method instead.
    validateAll: function () {

        var messages = {};

        for (var key in this.validators) {
            if(this.validators.hasOwnProperty(key)) {
                var check = this.validators[key](this.get(key));
                if (check.isValid === false) {
                    messages[key] = check.message;
                    console.log(check.message);
                }
            }
        }

        return _.size(messages) > 0 ? {isValid: false, messages: messages} : {isValid: true};
    },

    defaults: {
        id: null,
        nombre: "",
        clientes_id: null,
        clientes : [],
        telefono: "",
        extension: "",
        celular: "",
        email: "",
        comentarios: "",
        activo: "1"
    }
});

window.ContactsCollection = Backbone.Collection.extend({ 
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
            this.jn = options.jn;
            this.ic = options.ic;
        }else{
            this.page = null;
            this.jn = null;
            this.ic = null;
        }
    },
    model: Contacts,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/contactos/page/' + this.page;
        } else {
            if (this.jn!=true){             
                return BACKENDHOST+'/contactos';
            }else{
                if (this.ic!=null){
                    return BACKENDHOST+'/contactos/justnames/'+this.ic;
                }else{
                    return BACKENDHOST+'/contactos/justnames/';
                }
            }
        }
    }
});

window.TotalContacts = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/contactos/total",
    defaults: {
        total: 1    }
});
