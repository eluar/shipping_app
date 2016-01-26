window.Drivers = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/choferes",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El nombre es requerido"};
        };

        this.validators.apellidos = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "Falta la descripción"};
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
        apellidos: "",
        nombre_apellidos: "",
        telefono: "",
        activo: "1"
    }
});

window.DriversCollection = Backbone.Collection.extend({
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
        }else{
            this.page = null;
        }
    },
    model: Drivers,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/choferes/page/' + this.page;
        } else {
            return BACKENDHOST+'/choferes';
        }
    }
});
    url: BACKENDHOST+"/choferes",

window.TotalDrivers = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/choferes/total",
    defaults: {
        total: 1    }
});
