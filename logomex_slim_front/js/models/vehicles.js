window.Vehicles = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/vehiculos",

    initialize: function () {
        this.validators = {};

        this.validators.tipo_vehiculos_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Tipo de vehículo</b>"};
        };

        this.validators.economico = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>economico</b> est&aacute; vacio"};
        };
        
        this.validators.numero = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Número</b> est&aacute; vacio"};
        };
        
        this.validators.placas = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Número de Placa</b> est&aacute; vacio"};
        };
        
        this.validators.serie = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Número de Serie</b> est&aacute; vacio"};
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
        economico: "",
        tipo_vehiculos_id: null,
        tipo_vehiculos : [],
        numero: "",
        placas: "",
        activo: "1",
        nip: ""
    }
});

window.VehiclesCollection = Backbone.Collection.extend({ 
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
            this.jn = options.jn;
        }else{
            this.page = null;
            this.jn = null;
        }
    },
    model: Vehicles,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/vehiculos/page/' + this.page;
        } else {
        	if (this.jn!=true){        		
        		return BACKENDHOST+'/vehiculos';
        	}else{
        		return BACKENDHOST+'/vehiculos/justnames';
        	}
        }
    }
});

window.TotalVehicles = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/vehiculos/total",
    defaults: {
        total: 1    }
});
