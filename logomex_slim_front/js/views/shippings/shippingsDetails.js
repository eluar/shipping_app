window.ShippingsView = Backbone.View.extend({

    initialize: function () {
        var _shippings = this;
        this.clients = new ClientsCollection([],{jn:true});
        this.statuses = new StatusesCollection();
        this.locations = new LocationsCollection([],{jn:true});
        this.vehicles = new VehiclesCollection([],{jn:true});
        this.drivers = new DriversCollection([],{jn:true});

        _shippings.statuses.fetch({
            success: function() {
                _shippings.formatDateTimes();
                _shippings.render();
                _shippings.renderAllDropLists();
                _shippings.setDateTimeFields();
                //$(".combobox").combobox();
            },
            error: function(e) {
                console.log('error initializing shippings\' Statuses', e);
            }
        });
    },

    events: {
        "change"        : "change",
        "click .save"   : "beforeSave",
        "click .delete" : "deleteShippings",
        "change .cascaded" : "changeCascade",
        //"drop #picture" : "dropHandler"
    },

    /**
    * @abstract Render method will add the PRELOADED data from the back-end to the templates
    */
    render: function () {
        var _shippings = this;
        //console.log('shippings', this.model.toJSON());
        //console.log('statuses', this.statuses.toJSON());
        
        var params = {
          //clients: this.clients.toJSON(), 
          statuses: this.statuses.toJSON(),
          shipping: this.model.toJSON()
        };
        $(_shippings.el).html(_shippings.template(params));

        return this;
        
    },
            
    /*
    * @abstract Adding a format for DateTime inputs in the template form
    */
    formatDateTimes: function() {
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

    /*
    * @abstract Adding the DateTime Picker widget to the dateTime inputs
    */
    setDateTimeFields: function() {
        $('.form_datetime').datetimepicker({
            language: 'es',
            format: "d MM yyyy - HH:ii",
            autoclose: true,
            todayBtn: true,
           // startDate: moment().format(format),
            minuteStep: 10,
            todayHighlight: true
        });
    },

    /*
    * @abstract This function will carry all ASYNCHRONOUS data from the back-end to the selects inputs
    */
    renderAllDropLists: function() {
        var _shippings = this;

        $.when(this.clients.fetch())
        .done(function () {
            _shippings.addOptionsInDropList('#clientes_id', 'clientes_id', _shippings.clients.toJSON(), 'nombre');
            if (_shippings.model.get('id')!==null) {
                _shippings.changeCascade($('#clientes_id'));
                $("#clientes_id").combobox();
            }
        });

        $.when(this.locations.fetch())
        .done(function () {
            _shippings.addOptionsInDropList('#ubicaciones_origen_id', 
                                            'ubicaciones_origen_id', 
                                            _shippings.locations.toJSON(), 
                                            'nombre');
            $("#ubicaciones_origen_id").combobox();

            _shippings.addOptionsInDropList('#ubicaciones_destino_id', 
                                            'ubicaciones_destino_id', 
                                            _shippings.locations.toJSON(), 
                                            'nombre');
            $("#ubicaciones_destino_id").combobox();
        });

        $.when(this.vehicles.fetch())
        .done(function () {
            _shippings.addOptionsInDropList('#vehiculos_id', 
                                            'vehiculos_id', 
                                            _shippings.vehicles.toJSON(), 
                                            'economico');
            $("#vehiculos_id").combobox();
        });

        $.when(this.drivers.fetch())
        .done(function () {
            _shippings.addOptionsInDropList('#choferes_id', 
                                            'choferes_id', 
                                            _shippings.drivers.toJSON(), 
                                            'nombre_apellidos');
            $("#choferes_id").combobox();
        });


    },

    changeCascade: function(el) {
        var defaultElement = $(el.target).attr('id');
        var element = (typeof defaultElement !== 'undefined') ? defaultElement : el.attr('id'), 
            cascadedId = $(el.target).val();

        switch (element) {
            case "clientes_id":
                console.info('entered in clientes')
                this.getSegmentsByClientId(cascadedId);
                this.getSubSegmentsBySegmentId(null);
                this.getContactsByClientId(cascadedId);
            break;
            case "segmento_id":
                this.getSubSegmentsBySegmentId(cascadedId);
            break;
            default:
                console.log('element:', el);
        }

    },

    getSegmentsByClientId: function(clientId) {
        _shippings = this;
        var segments = new SegmentsCollection([],{jn:true,ic:clientId});
        //console.log(segments);
        segments.fetch({
            success: function () {
                _shippings.addOptionsInDropList('#segmento_id', 'segmento_id', segments.toJSON(), 'nombre');
                if (_shippings.model.get('id')!==null) {
                    //console.log('sub lelo:', $('#segmento_id'));
                    _shippings.changeCascade($('#segmento_id'));
                }
            },
            error: function (e) {
                console.log('Error getting Segments By Client ID:'+clientId, e);
            }
        });
    },

    getSubSegmentsBySegmentId: function(segmentId) {
        _shippings = this;
        if (segmentId !== null) {
            var subSegments = new SubSegmentsCollection([],{jn:true,is:segmentId});
            //console.log(subSegments);
            subSegments.fetch({
                success: function () {
                    _shippings.addOptionsInDropList('#subsegmento_id', 'subsegmento_id', subSegments.toJSON(), 'nombre');
                },
                error: function (e) {
                    console.log('Error getting Segments By Client ID:'+segmentId, e);
                }
            });
        } else {
            this.addOptionsInDropList('#subsegmento_id', 'subsegmento_id', [], 'nombre');
        }
    },

    getContactsByClientId: function(clientId) {
        console.info('entered in contacts with clientId:', clientId);
        try {
            _shippings = this;
            var contacts = new ContactsCollection([],{jn:true,ic:clientId});
            contacts.fetch({
                success: function () {
                    _shippings.addOptionsInDropList('#contactos_id', 'contactos_id', contacts.toJSON(), 'nombre');
                    $('#contactos_id').combobox();
                },
                error: function (e) {
                    console.log('Error getting Contacts By Client ID:'+clientId, e);
                }
            });
        } catch (e) {
            console.error('I dont\'t know what happened', e);
        }
            
    },
    
    /**
    * @function addOptionsInDropList is a render of <options/> tags based on a json array list
    **/
    addOptionsInDropList: function(target, id, list, tField) {
        var el, isSeleceted = true, _shippings = this;

        $(target + ' option[selected="selected"]').each(
            function() {
                $(this).removeAttr('selected');
            }
        );

        el = $('<option/>').attr('selected', isSeleceted);//.text("Elija uno");
        $(target).html(el);
        for (var i = 0; i <= list.length -1; i++) {
            isSeleceted = (list[i].id === this.model.get(id)) ? true : false;
            el = $('<option/>').attr('value', list[i].id).attr('selected', isSeleceted).text(list[i][tField]);
            $(target).append(el);
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

        // Run validation rule (if any) on changed item
        var check = this.model.validateItem(target.id);
        if (check.isValid === false) {
            utils.addValidationError(target.id, check.message);
        } else {
            utils.removeValidationError(target.id);
        }
    },

    beforeSave: function () {
        var self = this;
        var check = this.model.validateAll();
        if (check.isValid === false) {
            utils.displayValidationErrors(check.messages);
            return false;
        } else {
            this.saveShippings();
        }
        return false;
    },

    saveShippings: function () {
        var self = this;
        var x=moment(this.model.get('fecha_hora_servicio'), "D MMMM YYYY - HH:mm").format('YYYY-MM-DD HH:mm:ss');
        this.model.set({'fecha_hora_servicio':x});
        var y=moment(this.model.get('fecha_hora_solicitud'), "D MMMM YYYY - HH:mm").format('YYYY-MM-DD HH:mm:ss');
        this.model.set({'fecha_hora_solicitud':y});
        this.model.save(null, {
            success: function (model) {
                //self.render();
                app.navigate('envios/' + model.id, false);
                utils.showAlert('Success!', 'Shippings saved successfully', 'alert-success');
            },
            error: function () {
                utils.showAlert('Error', 'An error occurred while trying to save this item', 'alert-error');
            }
        });
    },

    deleteShippings: function () {
        this.model.destroy({
            success: function () {
                alert('Envio fue borrado con exito');
                window.history.back();
            }
        });
        return false;
    }

});