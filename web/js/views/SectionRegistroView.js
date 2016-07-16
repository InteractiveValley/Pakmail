define([
    'jquery', 
    'underscore',
    'backbone.validation',
    'models/UsuarioModel',
    'text!templates/SectionRegistroView.tpl'
],
    function ($, _, Backbone, UsuarioModel, SectionRegistroViewTemplate ) {
        var SectionRegistroView = Backbone.View.extend({
            tagName: 'section',
            className: 'container',
            templateRegistro:       _.template( SectionRegistroViewTemplate ),
            initialize: function() {
                console.log('inicializando sectionregistroview');
                this.id = 'registro';
                var self = this;
                if(!app.user.isLoggedIn()){
                    this.model = new UsuarioModel();
                }else{
                    this.model = app.user;
                    this.model.fetch({success: function(data){
                        self.renderRegistro();
                    }});
                }
                Backbone.Validation.bind(this);
                this.statusGuardar = false;
            },
            events:{
                "change"                : "change",
                "click #btnGuardar"     : "guardarRegistro",
                "click #btnSiguiente"   : "siguiente"
            },
            render: function(){
                this.renderRegistro();
                return this;
            },
            siguiente: function(e){
                debugger;
                e.preventDefault();
                if(this.statusGuardar){
                    this.guardar('envio');
                }else if(app.user.isLoggedIn()){
                    app.routers.router.navigate('envio',{trigger: true});
                }else{
                    return this.model.isValid(true);
                }
            },
            guardarRegistro: function(e){
                e.preventDefault();
                if(this.statusGuardar){
                    this.guardar();
                }else{
                    alert("No hay cambios que guardar");
                }
            },
            guardar: function(irA){
                var self = this;
                irA = irA || "";
                var isNew = !app.user.isLoggedIn();
                app.views.appView.$el.find('#division-principal').html("").addClass('cargando');
                if(this.model.isValid(true)){
                    this.model.save({}, {
                        success: function (model, response, options) {
                            console.log("The model has been saved to the server");
                            if(isNew){
                                debugger;
                                app.user.set(model);
                                app.user.fetch({success: function(data){
                                  app.routers.router.navigate('envio',{trigger: true});
                                }});
                            }else{
                                if(irA == "envio"){
                                    app.routers.router.navigate('envio',{trigger: true});
                                }else{
                                    app.views.appView.$el.find('#division-principal').html(app.views.registro.el);
                                    self.renderRegistro();
                                }
                            }
                        },
                        error: function (model, xhr, options) {
                            alert("Hay un error al grabar el registro");
                            app.views.appView.$el.find('#division-principal').html(app.views.registro.el);
                            self.renderRegistro();
                        }
                    });
                }else{
                    alert('Favor de revisar los datos del formulario');
                    app.views.appView.$el.find('#division-principal').html(app.views.registro.el);
                    self.renderRegistro();
                }
            },
            renderRegistro: function(){
                var usuarioData     = this.model.toJSON();
                app.views.appView.$el.find('#division-principal').removeClass('cargando');
                this.$el.html(this.templateRegistro({usuario: usuarioData}));
                return this;
            },
            change: function (event) {
                debugger;
                // Apply the change to the model
                var target = event.target;
                var change = {};
                var arreglo = target.name.split("_");
                change[arreglo[1]] = target.value;
                this.model.set(change);
                if(this.model.isValid(arreglo[1])){
                    this.valid(arreglo[0],arreglo[1]);
                }else{
                    var errorMessage = this.model.preValidate(arreglo[1], target.value);
                    this.invalid(arreglo[0],arreglo[1], errorMessage);
                }
                this.statusGuardar = true;
            },
            valid: function(modelo, attr) {
                debugger;
                var $el = this.$el.find('[name=' + modelo+"_"+attr + ']'), 
                    $group = $el.closest('.form-group');

                $group.removeClass('has-error');
                $group.find('.help-block').html('').addClass('hidden');
            },
            invalid: function(modelo, attr, error) {
                debugger;
                var $el = this.$el.find('[name=' + modelo+"_"+attr + ']'), 
                    $group = $el.closest('.form-group');

                $group.addClass('has-error');
                $group.find('.help-block').html(error).removeClass('hidden');
            },
            destroy_view: function () {
                // COMPLETELY UNBIND THE VIEW 
                this.undelegateEvents();
                this.$el.removeData().unbind();
                // Remove view from DOM 
                this.remove();
                Backbone.View.prototype.remove.call(this);
            }
        });
        return SectionRegistroView;
});