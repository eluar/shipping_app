// Tell jQuery to watch for any 401 or 403 errors and handle them appropriately
$.ajaxSetup({
    statusCode: {
        401: function(){
            // Redirec the to the login page.
            window.location.replace('#login');
         
        },
        403: function() {
            // 403 -- Access denied
            window.location.replace('#denied');
        }
    }
});

var AppRouter = Backbone.Router.extend({

    routes: {
        "login" : "login",
        "logout" : "logOut",
        ""                  : "shippingsList",
        "about"             : "about",
        "clientes"         : "clientList",
        "clientes/page/:page" : "clientList",
        "clientes/add"      : "clientAdd",
        "clientes/:id"     : "clientDetails",
        "contactos"         : "contactsList",
        "contactos/page/:page" : "contactsList",
        "contactos/add"     : "contactsAdd",
        "contactos/:id"     : "contactsDetails",
        "segmentos"         : "segmentsList",
        "segmentos/page/:page"  : "segmentsList",
        "segmentos/add"     : "segmentAdd",
        "segmentos/:id"     : "segmentDetails",
        "envios"         : "shippingsList",
        "envios/page/:page" : "shippingsList",
        "envios/add"     : "shippingsAdd",
        "envios/:id"     : "shippingsDetails",
    },

    initialize: function () {
        this.headerView = new HeaderView();
        $('.header').html(this.headerView.el);
    },
            
    initial: function() {        
        $("#content").html('');
        utils.changeAddButton('#envios/add', 'Envio');
    },

    list: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var shippingList = new ShippingCollection();
        shippingList.fetch({
            success: function(){
                $("#content").html(new ShippingListView({
                    model: shippingList, page: p
                }).el
            );}
        });
        this.headerView.selectMenuItem('home-menu');
    },

    about: function () {
        if (!this.aboutView) {
            this.aboutView = new AboutView();
        }
        $('#content').html(this.aboutView.el);
        this.headerView.selectMenuItem('about-menu');
    },
            
    //shippings
    shippingsList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var shippingsList = new ShippingsCollection([],{page:p});
        var totalShippings = new TotalShippings();
        var t=1;
        totalShippings.fetch({
            success: function(x){
                t=x.get("total");
                shippingsList.fetch({
                    success: function(){
                        $("#content").html(new ShippingsListView({
                            model: shippingsList, page: p, totalPages: t
                        }).el
                        );
                        utils.changeAddButton('#envios/add', 'Envio');
                    }
                });
            }
        });        
        this.headerView.selectMenuItem('home-menu');
    },
    
    shippingsDetails: function (id) {
        var shipping = new Shippings({id: id});
        shipping.fetch({
            success: function(){
                //console.log(shipping)
                $("#content").html(new ShippingsView({model: shipping}).el);
            },
            error: function(r) {
                //alert('error');
                console.log('r:', r);
                utils.showAlert('Error', 'No se encontr&oacute; registro con id: '+shipping.id, 'alert-error');
            }
        });
        this.headerView.selectMenuItem();
    },
    
    shippingsAdd: function() {
        var shipping = new Shippings();
        $('#content').html(new ShippingsView({model: shipping}).el);
        this.headerView.selectMenuItem('add-menu');
    },    
    //end shippings
            
    clientList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var clientList = new ClientsCollection([],{page:p});
        var totalCli = new TotalClients();
        var t=1;
        totalCli.fetch({
            success: function(x){
                t=x.get("total");
                clientList.fetch({
                    success: function(){
                        console.log('Lista de Clientes');
                        $("#content").html(new ClientListView({
                            model: clientList, page: p, totalPages: t
                        }).el
                        );
                        utils.changeAddButton('#clientes/add', 'Cliente');
                    }
                });
            }
        });
        
        this.headerView.selectMenuItem('home-menu');
    },
    
    clientDetails: function (id) {
        var client = new Clients({id: id});
        client.fetch({
            success: function(){
                console.log(client)
                $("#content").html(new ClientView({model: client}).el);
            },
            error: function(r) {
                //alert('error');
                console.log('r:', r);
                utils.showAlert('Error', 'No se encontr&oacute; registro con id: '+client.id, 'alert-error');
            }
        });
        this.headerView.selectMenuItem();
    },
    
    clientAdd: function() {
        var client = new Clients();
        $('#content').html(new ClientView({model: client}).el);
        this.headerView.selectMenuItem('add-menu');
    },
            
    contactsList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var contactsList = new ContactsCollection([],{page:p});
        var totalCon = new TotalContacts();
        var t=1;
        totalCon.fetch({
            success: function(x){
                t=x.get("total");
                contactsList.fetch({
                    success: function(){
                        //console.log(clientList);
                        $("#content").html(new ContactsListView({
                            model: contactsList, page: p, totalPages: t
                        }).el
                        );
                        utils.changeAddButton('#contactos/add', 'Contacto');
                    }
                });
            }
        });        
        this.headerView.selectMenuItem('home-menu');
    },
    
    contactsDetails: function (id) {
        var contact = new Contacts({id: id});
        contact.fetch({
            success: function(){
                console.log(contact)
                $("#content").html(new ContactsView({model: contact}).el);
            },
            error: function(r) {
                //alert('error');
                console.log('r:', r);
                utils.showAlert('Error', 'No se encontr&oacute; registro con id: '+contact.id, 'alert-error');
            }
        });
        this.headerView.selectMenuItem();
    },
    
    contactsAdd: function() {
        var contacts = new Contacts();
        $('#content').html(new ContactsView({model: contacts}).el);
        this.headerView.selectMenuItem('add-menu');
    },

    segmentsList: function(page) {
        var p = page ? parseInt(page, 10) : 1;
        var segmentsList = new SegmentsCollection([],{page:p});
        var totalCon = new TotalSegments();
        var t=1;
        totalCon.fetch({
            success: function(x){
                t=x.get("total");
                segmentsList.fetch({
                    success: function(){
                        //console.log(clientList);
                        $("#content").html(new SegmentsListView({
                            model: segmentsList, page: p, totalPages: t
                        }).el
                        );
                        utils.changeAddButton('#segmentos/add', 'Segmento');
                    }
                });
            }
        });        
        this.headerView.selectMenuItem('home-menu');
    },
    
    segmentDetails: function (id) {
        var segment = new Segments({id: id});
        segment.fetch({
            success: function(){
                console.log(segment)
                $("#content").html(new SegmentsView({model: segment}).el);
            },
            error: function(r) {
                //alert('error');
                console.log('r:', r);
                utils.showAlert('Error', 'No se encontr&oacute; registro con id: '+segment.id, 'alert-error');
            }
        });
        this.headerView.selectMenuItem();
    },
    
    segmentAdd: function() {
        var segment = new Segments();
        $('#content').html(new SegmentsView({model: segment}).el);
        this.headerView.selectMenuItem('add-menu');
    },

    login: function() {
        $('#content').html(new LoginView().render().el);
    },

    logOut: function() {     
        $.ajax({
            url:BACKENDHOST+"/logout",
            type:'GET',
            dataType:"json",
            success:function (data) {
                console.log(["LogOut request details: ", data]);
                if(data.error) {  // If there is an error, show the error messages
                    console.log('error loginout', data);
                    $('.alert-error').text(data.error.text).show();
                }
                else { // If not, send them back to the home page
                    Backbone.history.navigate("/#login");
                }
            }
        });
    }

});

var arrTemplates = ['HeaderView', 'AboutView', 
                    'ClientListItemView','ClientView', //clients
                    'ContactsListItemView', 'ContactsView',
                    'SegmentsListItemView', 'SegmentsView',
                    'ContactsListItemView', 'ContactsView', //contacts
                    'ShippingsListItemView', 'ShippingsView', //Shippings
                    'LoginView'
                   ];

utils.loadTemplate(arrTemplates, function() {
    app = new AppRouter();
    Backbone.history.start();
});
