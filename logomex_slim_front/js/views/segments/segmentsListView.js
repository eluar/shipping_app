window.SegmentsListView = Backbone.View.extend({

    initialize: function () {
        this.render();
    },

    render: function () {
        var segments = this.model.models;
        var len = this.options.totalPages;//contacts.length;
        //Position: it must be the same here than in the paginator plugin
        var startPos = 0;//(this.options.page - 1) * 8;
        var endPos = Math.min(startPos + 8, segments.length);

        $(this.el).html('<div class="thumbnails col-sm-12 col-md-12 col-lg-12"></div>');

        for (var i = startPos; i < endPos; i++) {
            var segmentsListItemView = new SegmentsListItemView({model: segments[i]}).render().el;
            //console.log(contactsListItemView);
            $('.thumbnails', this.el).append(segmentsListItemView);
        }

        $(this.el).append(new Paginator({model: this.model, page: this.options.page, totalPages: len}).render().el);

        return this;
    }
});

window.SegmentsListItemView = Backbone.View.extend({

    tagName: "div",

    className: "box col-xs-12 col-sm-6 col-md-4 col-lg-3",

    initialize: function () {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },

    render: function () {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    }

});