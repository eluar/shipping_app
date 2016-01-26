window.Shippings = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/envios",

    initialize: function () {
        this.validators = {};

        this.validators.fecha_hora_solicitud = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Fecha de solicitud</b> est&aacute; vacio"};
        };

        this.validators.clientes_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Cliente</b>"};
        };

        this.validators.contactos_id = function (value) {
            return (value!=null && value.length > 0) ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Contacto</b>"};
        };
        
        this.validators.fecha_hora_servicio = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Fecha de servicio</b> est&aacute; vacio"};
        };
        
        this.validators.vehiculos_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Vehiculo</b>"};
        };
        
        this.validators.choferes_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Chofer</b>"};
        };
        
        this.validators.statuses_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Estado del envío</b>"};
        };
    },

    validateItem: function (key) {
        return (this.validators[key]) ? this.validators[key](this.get(key)) : {isValid: true};
    },

    // TODO: Implement Backbone's standard validate() method instead.
    validateAll: function () {
        
        console.log('entro en Validate');

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
        numero_servicio: "",
        fecha_hora_solicitud: null,
        clientes_id:null,
        clientes: [],
        segmento_id:null,
        segmento: [],
        subsegmento_id:null,
        subsegmento: [],
        contactos_id:null,
        contactos: [],
        ubicaciones_origen_id:null,
        ubicaciones_origen: [],
        ubicaciones_destino_id:null,
        ubicaciones_destino: [],
        fecha_hora_servicio: null,
        folio:"",
        vehiculos_id:null,
        vehiculos: [],
        choferes_id:null,
        choferes: [],
        observaciones: "",
        statuses_id: null,
        statuses : []
    }
});

window.ShippingsCollection = Backbone.Collection.extend({ 
    initialize: function(models, options) {
        this.page = options.page;
    },
    model: Shippings,
    url: function() {
        return BACKENDHOST+'/envios/page/' + this.page;
    }
});

window.TotalShippings = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/envios/total",
    defaults: { total: 1 }
});
