window.Statuses = Backbone.Model.extend({

    urlRoot: BACKENDHOST+"/statuses",

    initialize: function () {
        this.validators = {};

        this.validators.status_key = function (value) {
            return value.length > 0 ? {isValid: true} : {isValid: false, message: "El nombre es requerido"};
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
        status_key: "",
        descripcion: ""
    }
});

window.StatusesCollection = Backbone.Collection.extend({
    initialize: function(models, options) {
        if (options!=null){
            this.page = options.page;
        }else{
            this.page = null;
        }
    },
    model: Statuses,
    url: function() {
        if (this.page!=null){
            return BACKENDHOST+'/statuses/page/' + this.page;
        } else {
            return BACKENDHOST+'/statuses';
        }
    }
});
    url: BACKENDHOST+"/statuses",

window.TotalStatuses = Backbone.Model.extend({
    urlRoot: BACKENDHOST+"/statuses/total",
    defaults: {
        total: 1    }
});

window.Status = Backbone.Model.extend({
    initialize: function(options) {
        if (options!=null){
            this.status_key = options.key;
        }else{
            this.status_key = null;
        }
    },

    urlRoot: function(){        
        if (this.status_key!=''){
            return BACKENDHOST+'/statuses/key/' + this.status_key;
        } else {
            return null;
        }
    },

    defaults: {
        id: null,
        status_key: "",
        descripcion: ""
    }
})
