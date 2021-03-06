define([
    'underscore',
    'Backbone'
],
        function (_, Backbone) {
            var ProductoModel = Backbone.Model.extend({
                urlRoot: app.root + "/modelos",
                defaults: {
                    nombre: '',
                    cantidad: 1,
                    importe: 0,
                    importe_with_format: '',
                    cantidad_with_format: '',
                    precio_with_format: '',
                    in_carrito: false,
                    visible: true,
                },
                initialize: function () {
                    this.on("change:cantidad", function (self) {
                        self.set({importe_with_format: self.getImporteFormat()});
                        self.set({cantidad_with_format: self.getCantidadFormat()});
                     });
                    this.set({precio_with_format: this.getPrecioFormat()});
                },
                getImporteFormat: function(){
                    var self = this;
                    this.set({importe: self.get('precio')*self.get('cantidad')});
                    return formatNumber.new(self.get('importe'),"$");
                },
                getPrecioFormat: function(){
                    var self = this;
                    return formatNumber.new(self.get('precio'),"$");
                },
                getCantidadFormat: function(){
                    var self = this;
                    return formatNumber.new(self.get('cantidad'),"");
                }
            });
            return ProductoModel;
        }
);
