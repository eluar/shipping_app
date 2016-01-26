window.Segments = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/segmentos",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Nombre</b> est&aacute; vacio"};
        };

        this.validators.clientes_id = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "No ha seleccionado un <b>Cliente</b>"};
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
        descripcion: ""
    }
});

window.SegmentsCollection = Backbone.Collection.extend({ 
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
    url: function() {
        if (this.page!=null){
            console.log('getting all by page');
            return BACKENDHOST+'/segmentos/page/' + this.page;
         } else {
            if (this.jn!=true || typeof this.jn == 'undefined'){             
                console.log('getting all data');
                return BACKENDHOST+'/segmentos';
            }else{
                if (this.ic!=null){
                    console.log('getting justnames by client id');
                    return BACKENDHOST+'/segmentos/justnames/'+this.ic;
                }else{
                    console.log('getting all justnames');
                    return BACKENDHOST+'/segmentos/justnames/';
                }
            }
        }
    },
    model: Segments
});

window.TotalSegments = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/segmentos/total/",
    defaults: {
        total: 1    }
});