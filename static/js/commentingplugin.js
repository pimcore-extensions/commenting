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

    addCommentsTab: function(object, type) {
        var commenttab = new pimcore.plugin.commenting.comments(object, type);
        object.tab.items.items[1].insert(object.tab.items.items[1].items.length, commenttab.getLayout());
        object.tab.items.items[1].doLayout();
        pimcore.layout.refresh();
    },

    postOpenObject: function(object, type) {
        this.addCommentsTab(object, type);
    },

    postOpenDocument: function(object, type) {
        this.addCommentsTab(object, type);
    },

    postOpenAsset: function(object, type) {
        this.addCommentsTab(object, type);
    }

});

new pimcore.plugin.commenting();
