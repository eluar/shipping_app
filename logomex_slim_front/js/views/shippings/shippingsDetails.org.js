window.ShippingsView = Backbone.View.extend({
    
    DOMTarget: null,
    buscandoSegmentos: false,
    buscandoContactos: false,
    buscandoSubsegmentos: false,
    
    initialize: function () {
        this.DOMTarget = null;
        this.clientsColl = new ClientsCollection([],{jn:true});
        this.segmentsColl = new SegmentsCollection([],{jn:true});
        this.subSegmentsColl = new SubSegmentsCollection([],{jn:true});
        this.contactsColl = new ContactsCollection([],{jn:true});
        this.locationsColl = new LocationsCollection([],{jn:true});
        this.vehiclesColl = new VehiclesCollection([],{jn:true});
        this.driversColl = new DriversCollection([],{jn:true});
        this.statusesColl = new StatusesCollection();
        this.render();
        var _thisView=this;
        $.when(this.segmentsColl.fetch())
          .done(function () {
            _thisView.initializeSegments();
          });
        $.when(this.contactsColl.fetch())
            .done(function(){
                _thisView.initializeContacts();
            });
        $.when(this.subSegmentsColl.fetch())
            .done(function () {
                _thisView.initializeSubsegments();
            });
    },

    initializeSegments: function(){
        this.buscandoSegmentos=false;
    },

    initializeSubsegments: function(){
        this.buscandoSubsegmentos=false;
    },

    initializeContacts: function() {
        this.initializeContacts=false;
    },

    render: function () {
        var self = this,
            params = {};
        this.clientsColl.fetch();
        //this.segmentsColl.fetch();
        //this.subSegmentsColl.fetch();
        //this.contactsColl.fetch();
        this.locationsColl.fetch();
        this.vehiclesColl.fetch();
        this.driversColl.fetch();
        this.getStatuses(function(statuses) {
           if (statuses) {
               params = {
                 shipping: self.model.toJSON(),
                 statuses: statuses
               };
               $(self.el).html(self.template(params));
               return this;
           } else {
               console.log('an error ocurred', statuses);
               return this;
           }
        });

        $('.form_datetime').datetimepicker({
            language: 'es',
            //format: "dd MM yyyy - hh:ii",
            autoclose: true,
            todayBtn: true,
           // startDate: moment().format(format),
            minuteStep: 10,
            todayHighlight: true
        });

        var x=moment().format('D MMMM YYYY - HH:mm');
        if (this.model.get('fecha_hora_solicitud')==null){
            this.model.set({'fecha_hora_solicitud':x});
        } else {
            var y=moment(this.model.get('fecha_hora_solicitud'), "YYYY-MM-DD HH:mm:ss").format('D MMMM YYYY - HH:mm');
            this.model.set({'fecha_hora_solicitud':y});
        }
        if (this.model.get('fecha_hora_servicio')==null){
            this.model.set({'fecha_hora_servicio':x});
        } else {
            var z=moment(this.model.get('fecha_hora_servicio'), "YYYY-MM-DD HH:mm:ss").format('D MMMM YYYY - HH:mm');
            this.model.set({'fecha_hora_servicio':z});
        }
        
    },

    events: {
        "change"        : "change",
        "click .save"   : "beforeSave",
        "click .delete" : "deleteShippings",
        "focus #clientes_nombre, #segmento_nombre, #subsegmento_nombre, #contactos_nombre, #ubicaciones_origen_nombre, #ubicaciones_destino_nombre, #vehiculos_economico, #choferes_nombre" : "invokeAutocomplete",
        "keydown #clientes_nombre, #subsegmento_nombre, #ubicaciones_origen_nombre, #ubicaciones_destino_nombre, #vehiculos_economico, #choferes_nombre" : "invokefetch",
        "focus #fecha_hora_servicio" : "invokeDateTime",
        "click #fecha_hora_servicio" : "invokeDateTime",
        "keydown #segmento_nombre" : "getSegments",
        "keydown #contactos_nombre" : "getContacts",
        "keydown #subsegmento_nombre" : "getSubsegments",
        "focusout #clientes_nombre, #segmento_nombre" : "blur"
    },
    
    /**    
    invokeDateTime : function (e) {
        $('#'+($(e.target).attr('id'))).datetimepicker({
            lang: 'es',
            formatDate: 'd-m-Y H:i',
            onSelectTime: function(){
                $('#'+($(e.target).attr('id'))).change();
            }
        });
    },
    **/

    invokeDateTime : function (e) {                     
        $('#'+($(e.target).attr('id'))).datetimepicker({
            language: 'es',
            format: "dd MM yyyy - hh:ii",
            autoclose: true,
            todayBtn: true,
            //startDate: moment().format(format),
            minuteStep: 10,
            todayHighlight: true
        });        
    },
    
    invokefetch : function(e) {        
        if (this[(this.DOMTarget.data('collection')) + 'Coll'].length==0){
        	this[(this.DOMTarget.data('collection')) + 'Coll'].fetch();
        }
        //this.clientsColl.fetch(); //sample for clients
        $("#"+(this.DOMTarget.attr('id'))).unbind( "keydown", this.invokefetch);
    },
    
    invokeAutocomplete: function (e) {
         this.DOMTarget = $(e.target);
         var self = this,
             attr = this.getAttribute(this.DOMTarget.data('collection'));
         $("#"+(self.DOMTarget.attr('id'))).autocomplete({
           collection: self[(self.DOMTarget.data('collection')) + 'Coll'],
           attr: attr,
           noCase: true,
           targetElement: this.DOMTarget,
           onselect: self.autocompleteSelect,
           ul_class: 'autocomplete shadow',
           ul_css: {'z-index':1234},
           max_results: 15
        });
    },
            
    autocompleteSelect: function(model, element) {
         $('#'+element.attr('id')).val(model.get(this.attr));
         $('#'+element.data('target')).attr('value',model.get('id'));
         $('#'+element.data('target')).change();
         if (element.data('target')=='clientes_id'){
        	 if (parseInt(model.get('id'))>0){
        		 $('#segmento_nombre').removeAttr('disabled');
        		 $('#contactos_nombre').removeAttr('disabled');  
                 $('#segmento_nombre').keydown();
                 $('#contactos_nombre').keydown();       
        	 }
         }
         if (element.data('target')=='segmento_id'){
            console.log('Id del Modelo: ',model.get('id'));
            if (parseInt(model.get('id'))>0){
                $('#subsegmento_nombre').removeAttr('disabled');
                $('#subsegmento_nombre').keydown();
            }
         }
    },
            
    change: function (event) {
        // Remove any existing alert message
        utils.hideAlert();

        // Apply the change to the model
        var target = event.target;
        var change = {};
        change[target.name] = target.value;        
        this.model.set(change);
        if (event.target.name=='clientes_nombre'){
            if (parseInt(this.model.get('clientes_id'))>0)
            {
                $('#segmento_nombre').keydown();
                $('#contactos_nombre').keydown();
            }
        }
        // Run validation rule (if any) on changed item
        var check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },

    blur: function(e){
        if (e.target.name=='segmento_nombre'){
            var n=$('#'+e.target.name).val();
            if (n.length>0){
                var modelTarget=this.segmentsColl.findWhere({nombre:n.toUpperCase()});
                if (modelTarget!=null){
                    this.model.set({segemento_id:modelTarget.get('id')});
                    this.model.set({segmento:modelTarget});
                    $('#'+e.target.name).val(modelTarget.get('nombre'));
                    $('#'+this.DOMTarget.data('target')).attr('value',modelTarget.get('id'));
                    $('#'+this.DOMTarget.data('target')).change();
                    $('#subsegmento_nombre').keydown();
                }else{
                    limpiaSegmento(this);    
                }
            } else {
                limpiaSegmento(this);
            }
        }

        function limpiaSegmento(t){
            t.model.set({subsegmento_id: null});
            t.model.set({segmento_id:null});
            $('#subsegmento_nombre').keydown();
            $('#segmento_nombre').attr('disabled','disabled');
            $('#subsegmento_nombre').attr('disabled','disabled');
            t.subSegmentsColl.reset();        
        }

        if (e.target.name=='clientes_nombre'){
            //if (this.model.get('clientes_id')==null){
                var n=$('#'+e.target.name).val();
                if (n.length>0){
                    var modelTarget=this.clientsColl.findWhere({nombre:n.toUpperCase()});
                    if (modelTarget!=null){
                        this.model.set({clientes_id:modelTarget.get('id')});
                        this.model.set({clientes_nombre:modelTarget.get('nombre')});
                        this.model.set({clientes:modelTarget});
                        $('#'+ e.target.name).val(modelTarget.get('nombre'));
                        $('#'+this.DOMTarget.data('target')).attr('value',modelTarget.get('id'));
                        $('#'+this.DOMTarget.data('target')).change(); 
                        $('#segmento_nombre').keydown();
                        $('#contactos_nombre').keydown();
                        $('#segmento_nombre').removeAttr('disabled');
                        $('#contactos_nombre').removeAttr('disabled');
                    } else {
                        limpiaNombre(this);
                    }
                } else {
                    limpiaNombre(this);
                }
            //}
        }

        function limpiaNombre(t){
            $('#'+ e.target.name).val('');
            $('#segmento_nombre').val('');
            $('#contactos_nombre').val('');
            $('#subsegmento_nombre').val('');
            $('#segmento_nombre').attr('disabled','disabled');
            $('#contactos_nombre').attr('disabled','disabled');
            $('#subsegmento_nombre').attr('disabled','disabled');
            t.model.set({clientes: null});
            t.model.set({clientes_id: null});
            t.model.set({clientes_nombre: null});
            t.model.set({contactos: null});
            t.model.set({contactos_id: null});
            t.model.set({segmento: null});
            t.model.set({segmento_id: null});
            t.model.set({subsegmento: null});
            t.model.set({subsegmento_id: null});
            t.segmentsColl.reset();
            t.subSegmentsColl.reset();
            t.contactsColl.reset();            
            //t.segmentsColl.length=0;
            //t.subSegmentsColl.length=0;
            //t.contactsColl.length=0;
            console.log('Longitud de Segmentos: ',t.segmentsColl.length);
            console.log('Modelo: ', t);
        }

        /*if (e.target.name=='clientes_nombre' && this.model.get('clientes_nombre')==''){
            $('#'+this.DOMTarget.data('target')).attr('value',null);
            $('#'+this.DOMTarget.data('target')).change();
        } */       
    },

    beforeSave: function () {
        console.log('entro en beforeSave');
        var self = this;
        console.log('this.model', this.model);
        var check = this.model.validateAll();
        console.log('checking', check);
        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        } else {
            var x=moment(this.model.get('fecha_hora_solicitud'),'D MMMM YYYY - HH:mm').format("YYYY-MM-DD HH:mm:ss");
            this.model.set({fecha_hora_solicitud:x});
            x=moment(this.model.get('fecha_hora_servicio'),'D MMMM YYYY - HH:mm').format("YYYY-MM-DD HH:mm:ss");
            this.model.set({fecha_hora_servicio:x});
            this.saveShippings();
        }
        return false;
    },

    saveShippings: function () {
        var self = this;
        this.model.save(null, {
            success: function (model) {
                self.render();
                app.navigate('envios/' + model.id, false);
                utils.showAlert('Success!', 'Shippings saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to delete this item', 'alert-error');
            }
        });
    },

    deleteShippings: function () {
        this.model.destroy({
            success: function () {
                alert('Shippings deleted successfully');
                window.history.back();
            }
        });
        return false;
    },


    /**
    * getAttribute Method is created to get a different filter
    * for those tables which got no "nombre" field on it
    **/
    getAttribute: function(collection) {
        switch(collection) {
            case "vehicles":
                return "economico";
                break;
            case "drivers":
                return "nombre_apellidos";
                break;
            default:
                return "nombre";
        }
    },
            
    getStatuses: function(callback) {
        var self = this;
        this.statusesColl.fetch({
            success: function() {
                callback(self.statusesColl.toJSON());
            },
            error: function(e) {
                callback(false);
            }
        });
    },
    
    getClients: function(callback) {
        var self = this;
        var clientList = new ClientsCollection();
        clientList.fetch({
            success: function(){
                var params = {
                    clients: clientList.toJSON(),
                    contact: self.model.toJSON()
                };
                callback(params);
            },
            error: function(e){
                callback(false);
            }
        });
    },

    getSegments: function(callback) {
        console.log('Entro a getSegments con longitud: ',this.segmentsColl.length);
        console.log('Valor de buscandoSegmentos:',this.buscandoSegmentos);
        console.log('Entraré: ',this.segmentsColl.length==0 && !this.buscandoSegmentos);
        if (this.segmentsColl.length==0 && !this.buscandoSegmentos){
            this.buscandoSegmentos=true;
            var c_id=this.model.get('clientes_id');
            this.segmentsColl = new SegmentsCollection([],{jn:true,ic:c_id});
            this.segmentsColl.fetch({
                sucess: function(){
                    $('#segmento_nombre').unbind('keydown',this.getSegments);
                    $('#segmento_nombre').invokeAutocomplete();
                }
                ,
                error: function(e){
                    callback(false);
                }
            });
        }
    },

    getSubsegments: function(callback) {
        if (this.subSegmentsColl.length==0 && !this.buscandoSubsegmentos){
            this.buscandoSubsegmentos=true;
            var s_id=this.model.get('segmento_id');
            this.subSegmentsColl = new SubSegmentsCollection([],{jn:true,is:s_id});
            this.subSegmentsColl.fetch({
                sucess: function(){                    
                    $('#subsegmento_nombre').unbind('keydown',this.getSubsegments);
                    $('#subsegmento_nombre').invokeAutocomplete();                    
                },
                error: function(e){
                    callback(false);
                }
            });
        }
    },

    getContacts: function(callback) {
        if (this.contactsColl.length==0 && !this.buscandoContactos){
            this.buscandoContactos=true;
            var c_id=this.model.get('clientes_id');
            this.contactsColl = new ContactsCollection([],{jn:true,ic:c_id});
            this.contactsColl.fetch({
                sucess: function(){                    
                    $('#contactos_nombre').unbind('keydown',this.getContacts);
                    $('#contactos_nombre').invokeAutocomplete();                    
                },
                error: function(e){
                    callback(false);
                }
            });
        }
    }
});