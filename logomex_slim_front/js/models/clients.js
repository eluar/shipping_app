window.Clients = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/clientes",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El nombre es requerido"};
        };

        this.validators.descripcion = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "Falta la descripción"};
        };

        this.validators.telefono = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "Debe indicar el Teléfono"};
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
                }
            }
        }

        return _.size(messages) > 0 ? {isValid: false, messages: messages} : {isValid: true};
    },

    defaults: {
        id: null,
        nombre: "",
        descripcion: "",
        telefono: "",
        activo: "1"
    }
});

window.ClientsCollection = Backbone.Collection.extend({
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
            this.jn=options.jn;
        }else{
            this.page = null;
            this.jn = null;
        }
    },
    model: Clients,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/clientes/page/' + this.page;
        } else {
        	if (this.jn!=true){        		
        		return BACKENDHOST+'/clientes';
        	}else{
        		return BACKENDHOST+'/clientes/justnames';
        	}
        }
    }
});
    url: BACKENDHOST+"/clientes",

window.TotalClients = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/clientes/total",
    defaults: {
        total: 1    }
});
