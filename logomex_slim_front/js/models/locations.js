window.Locations = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/ubicaciones",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El nombre es requerido"};
        };

        this.validators.descripcion = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "Falta la descripción"};
        };

        this.validators.key_tipo = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "Debe indicar el key_tipo"};
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
        key_tipo: null
    }
});

window.LocationsCollection = Backbone.Collection.extend({
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
            this.jn = options.jn;
        }else{
            this.page = null;
            this.jn = null;
        }
    },
    model: Locations,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/ubicaciones/page/' + this.page;
        } else {
        	if (this.jn!=true){        		
        		return BACKENDHOST+'/ubicaciones';
        	}else{
        		return BACKENDHOST+'/ubicaciones/justnames';
        	}
        }
    }
});
    url: BACKENDHOST+"/ubicaciones",

window.TotalLocations = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/ubicaciones/total",
    defaults: {
        total: 1    }
});
