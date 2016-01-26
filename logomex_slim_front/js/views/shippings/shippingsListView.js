window.ShippingsListView = Backbone.View.extend({

    events: {
         "window resize" : "render"
    },

    initialize: function () {
        this.render();
        //$(window).on("resize",this.renderIng());
    },

    renderIng: function() {
        //app.navigate('envios/', false);
        console.log('size:', this.getScreenSize());
    },
    /*
    getScreenSize: function () {
        var width = $(window).width();
        //console.info('width',width);

        switch (true) {
            case (width >= 1024):
                return 'large';
            break;
            case (width >= 768):
                return 'mid';
            break;
            default:
                return 'small'
        }
    },*/

    render: function () {
        var _shippings = this;
        var shippings = this.model.models;
        var len = this.options.totalPages;//shippings.length;
        //Position: it must be the same here than in the paginator plugin
        var startPos = 0;//(this.options.page - 1) * 8;
        console.log('Tamaño del arreglo:',shippings.length);
        var endPos = Math.min(startPos + 8, shippings.length);

        $(this.el).html('<div class="thumbnails col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>');

        for (var i = startPos; i < endPos; i++) {
            var shippingsListItemView = new ShippingsListItemView({model: shippings[i]}).render().el;
            //console.log(contactsListItemView);
            $('.thumbnails', this.el).append(shippingsListItemView);
        }
        $(this.el).append("<br/><br/><br/><br/><br/><br/><br/><br/>");
        $(this.el).append(new Paginator({model: this.model, page: this.options.page, totalPages: len}).render().el);

        return this;
    },

    remove: function() {
        $(window).off("resize",this.renderIng());
        //call the superclass remove method
        Backbone.View.prototype.remove.apply(this, arguments);
    }

});

window.ShippingsListItemView = Backbone.View.extend({

    tagName: "div",

    className: "box col-xs-12 col-sm-6 col-md-4 col-lg-3",

    events: {
        "click .remove" : "deleteSingleRow",
        "change .updateStatus" : "updateSingleRow", 
        "click .changeStatus" : "changeStatus"
    },

    updateSingleRow: function (event) {
        console.log('el', event.target.selectedOptions[0].value);
        //catching the status new value from the select-box element
        var statusNew = event.target.selectedOptions[0].value;
        this.model.set('statuses_id', statusNew);
        this.model.save({
            success: function () {
                alert ('El Status del Envio ha cambiado');
                window.history.back();
            }
        });
        return false;
    },

    deleteSingleRow: function () {
        this.model.destroy({
            success: function () {
                alert('Registro de envio eliminado');
                window.history.back();
            }
        });
        return false;
    },

    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render: function () {
        console.log('shipping:', this.model.toJSON());
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },

    changeStatus: function(event){
        console.log('Evento', event);
        console.log('Evento', event.target.dataset.key);
        var status= new Status({key: event.target.dataset.key});
        var envio=this.model;
        $.when(status.fetch())
        .done(function () {
            console.log("Modelo: ", envio);
                console.log("Estatus a Enlazar: ", status);
                envio.set('statuses_id',status.id);
                envio.save({
                    success: function () {
                        alert('Envío Actualizado');
                    }
                });
        });
    }

});