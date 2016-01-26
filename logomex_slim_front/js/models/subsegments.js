window.SubSegments = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/subsegmentos",

    initialize: function () {
        this.validators = {};

        this.validators.nombre = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El Campo <b>Nombre</b> est&aacute; vacio"};
        };

        this.validators.segmento_id = function (value) {
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
        segmento_id: null,
        segmento : [],
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
    model: Segments,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/segmentos/page/' + this.page;
         } else {
            if (this.jn!=true){             
                return BACKENDHOST+'/segmentos';
            }else{
                if (this.ic!=null){
                    return BACKENDHOST+'/segmentos/justnames/'+this.ic;
                }else{
                    return BACKENDHOST+'/segmentos/justnames/';
                }
            }
        }
    }
});


window.SubSegmentsCollection = Backbone.Collection.extend({ 
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
            this.jn = options.jn;
            this.is = options.is;
        }
        else{
            this.page = null;
            this.page=null;
            this.jn=null;
            this.is=null;
        }
    },
    model: SubSegments,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/subsegmentos/page/' + this.page;
        } else {
            if (this.jn!=true){             
                return BACKENDHOST+'/subsegmentos';
            }else{
                if (this.is!=null){
                    return BACKENDHOST+'/subsegmentos/justnames/'+this.is;
                }else{
                    return BACKENDHOST+'/subsegmentos/justnames/';
                }
            }
        }
    }
});

window.TotalSubSegments = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/subsegmentos/total/",
    defaults: {
        total: 1    }
});