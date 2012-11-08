pimcore.registerNS("pimcore.plugin.commenting.comments");

pimcore.plugin.commenting.comments = Class.create({

    initialize: function(object, type) {
        this.object = object;
        this.type = type;
    },

    load: function () {
    },

    getLayout: function () {


        if (this.layout == null) {

            this.layout = new Ext.Panel({
                title: t('comments'),
                border: false,
                layout: "fit",
                iconCls: "commenting_plugin_icon_tab_comments",
                items: [this.getRowEditor()]
            });
        }
        return this.layout;

    },

    getValues : function () {

        var values = [];

        for (var i = 0; i < this.store.getCount(); i++) {
            values.push(this.store.getAt(i).data);
        }
        return values;
    },


    getRowEditor: function () {

        var proxy = new Ext.data.HttpProxy({
            url: '/plugin/Commenting/admin/comments'
        });
        var readerFields = [
            {name: 'c_id'},
            {name: 'c_shorttext'},
            {name: 'c_text'},
            {name: 'c_user'},
            {name:'c_created'}
        ];
        var reader = new Ext.data.JsonReader({
            totalProperty: 'total',
            successProperty: 'success',
            root: 'comments',
            idProperty: 'c_id'
        }, readerFields);

        var writer = new Ext.data.JsonWriter();

        this.store = new Ext.data.Store({
            id: 'comments_store_' + this.object.id,
            restful: false,
            proxy: proxy,
            reader: reader,
            writer: writer,
            listeners: {
                write : function(store, action, result, response, rs) {
                }
            },
            baseParams:{objectid:this.object.id, type:this.type}
        });
        this.store.load();

        var expander = new Ext.ux.grid.RowExpander({
            tpl : new Ext.Template(
                    '<p>{c_text}</p>'
            )
        });

        this.commentgrid = new Ext.grid.GridPanel({
            store: this.store,
            autoScroll: true,
            cm: new Ext.grid.ColumnModel({
                defaults: {
                    width: 20,
                    sortable: true
                },
                columns: [
                    expander,
                    {id:'c_id',header: t('id'), width: 5, dataIndex: 'c_id'},
                    {header: t('comment'), width: 40, dataIndex: 'c_shorttext'},
                    {header: t('user'), dataIndex: 'c_user'},
                    {header: t("created"),  sortable: true, dataIndex: 'c_created', renderer: function(d) {
                        var date = new Date(d * 1000);
                        return date.format("Y-m-d H:i:s");
                    }},
                ]
            }),
            viewConfig: {
                forceFit:true
            },
            width: 600,
            height: 300,
            plugins: expander,
            collapsible: true,
            animCollapse: false,
            tbar: [
                {
                    text: t('delete'),
                    handler: this.onCommentDelete.bind(this),
                    iconCls: "pimcore_icon_delete"
                },
                '-',
                {
                    text: t('reload'),
                    handler: function () {
                        this.store.reload();
                    }.bind(this),
                    iconCls: "pimcore_icon_reload"
                }
            ]
        });

        return this.commentgrid;
    },

    onCommentDelete: function () {
        var rec = this.commentgrid.getSelectionModel().getSelected();
        if (!rec) {
            return false;
        }
        this.commentgrid.store.remove(rec);
    }

});