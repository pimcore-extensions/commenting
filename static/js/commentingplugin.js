pimcore.registerNS("pimcore.plugin.commenting");

pimcore.plugin.commenting = Class.create(pimcore.plugin.admin, {

    getClassName: function () {
        return "pimcore.plugin.commenting";
    },

    initialize: function() {
        pimcore.plugin.broker.registerPlugin(this);
    },


    uninstall: function() {
        //TODO: hide all commenting tabs
    },

    postOpenObject: function(object, type) {

        var items = object.tab.items.items[1].items;
        var commenttab = new pimcore.plugin.commenting.comments(object, type);
        object.tab.items.items[1].insert(object.tab.items.items[1].items.length, commenttab.getLayout());
        object.tab.items.items[1].doLayout();
        pimcore.layout.refresh();

    },
    postOpenDocument: function(object, type) {

        var items = object.tab.items.items[1].items;
        var commenttab = new pimcore.plugin.commenting.comments(object, type);
        object.tab.items.items[1].insert(object.tab.items.items[1].items.length, commenttab.getLayout());
        object.tab.items.items[1].doLayout();
        pimcore.layout.refresh();

    },
    postOpenAsset: function(object, type) {

        var items = object.tab.items.items[1].items;
        var commenttab = new pimcore.plugin.commenting.comments(object, type);
        object.tab.items.items[1].insert(object.tab.items.items[1].items.length, commenttab.getLayout());
        object.tab.items.items[1].doLayout();
        pimcore.layout.refresh();

    }

});

new pimcore.plugin.commenting();
