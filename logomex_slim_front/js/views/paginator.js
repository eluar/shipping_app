window.Paginator = Backbone.View.extend({

    //className: "col-sm-12 col-md-12 col-lg-12",

    initialize:function () {
        this.model.bind("reset", this.render, this);
        this.render();
    },

    render:function () {
        var items = this.model.models;
        var len = items.length;
        if (this.options.totalPages!=null){
            len = this.options.totalPages;
        }
        //Position (page count): it must be the same here than in every view using this plugin
        var pageCount = Math.ceil(len / 8);

        //$(this.el).html('<ul />');
        $(this.el).html('<ul class="pagination"></ul>');

        for (var i=0; i < pageCount; i++) {
           $('ul', this.el).append("<li" + ((i + 1) === this.options.page ? " class='active'" : "") + "><a href='#"+utils.getHash()+"/page/"+(i+1)+"'>" + (i+1) + "</a></li>");
        }

        return this;
    }
});