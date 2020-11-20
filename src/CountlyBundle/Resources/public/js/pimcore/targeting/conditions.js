(function() {
    'use strict';

    pimcore.settings.targeting.conditions.register(
        'countly_has_complete_funnel',
        Class.create(pimcore.settings.targeting.condition.abstract, {
            getName: function () {
                return t("countlyCompleteFunnel");
            },

            getPanel: function (panel, data) {
                var id = Ext.id();

                this.formFunnelConditions = new Ext.form.FormPanel({
                    id: id,
                    forceLayout: true,
                    style: 'margin: 10px 0 0 0',
                    bodyStyle: 'padding: 10px 30px 10px 30px; min-height:40px;',
                    tbar: pimcore.settings.targeting.conditions.getTopBar(this, id, panel, data),
                    items: [
                        {
                            xtype: 'combo',
                            fieldLabel: t('funnels'),
                            displayField: 'name',
                            valueField: '_id',
                            name: "funnels",
                            store: new Ext.data.JsonStore({
                                autoDestroy: true,
                                proxy: {
                                    type: 'ajax',
                                    url: "/saltid/countly/funnels/",
                                    reader: {
                                        type: 'json',
                                        rootProperty: 'data'
                                    }
                                },
                                fields: ["_id", "name"]
                            }),
                            triggerAction: "all",
                            mode: "local",
                            forceSelection: true,
                            queryMode: 'local',
                            autoComplete: false,
                            width: 350,
                            value: ('undefined' !== typeof data.funnels) ? data.funnels : null,
                            listeners: {
                                afterrender: function (el) {
                                    el.getStore().load();
                                    console.log(data);
                                },
                                change: function (field, value) {
                                    this.formFunnelConditions.getComponent("funnelSteps").setStore(this.getFunnelDetail(value));
                                    this.formFunnelConditions.getComponent("funnelSteps").getStore().load();
                                }.bind(this)
                            }
                        },
                        {
                            xtype: 'combo',
                            fieldLabel: t('steps'),
                            name: "steps",
                            displayField: 'step',
                            valueField: 'step',
                            itemId: "funnelSteps",
                            triggerAction: "all",
                            mode: "local",
                            forceSelection: true,
                            queryMode: 'local',
                            autoComplete: false,
                            width: 350,
                            value: ('undefined' !== typeof data.steps) ? data.steps : null,
                        },
                        {
                            xtype: 'hidden',
                            name: 'type',
                            value: 'countly_has_complete_funnel' // the identifier chosen before when registering the PHP class
                        }
                    ]
                });

                return this.formFunnelConditions;
            },

            getFunnelDetail: function (funnelId) {
                return new Ext.data.Store({
                    autoDestroy: true,
                    proxy: {
                        type: 'ajax',
                        url: "/saltid/countly/funnels/" + funnelId,
                        reader: {
                            type: 'json',
                            rootProperty: 'data.steps'
                        }
                    },
                });
            }
        })
    );

    pimcore.settings.targeting.conditions.register(
        'countly_has_not_custom_property',
        Class.create(pimcore.settings.targeting.condition.abstract, {
            getName: function () {
                return t("countlyHasNotCustomProperty");
            },

            getPanel: function (panel, data) {
                var id = Ext.id();

                this.formCustomProperty = new Ext.form.FormPanel({
                    id: id,
                    forceLayout: true,
                    style: 'margin: 10px 0 0 0',
                    bodyStyle: 'padding: 10px 30px 10px 30px; min-height:40px;',
                    tbar: pimcore.settings.targeting.conditions.getTopBar(this, id, panel, data),
                    items: [
                        {
                            name: 'attributeKey',
                            fieldLabel: 'Attribute Key',
                            xtype: 'textfield',
                            width: 500,
                            value: ('undefined' !== typeof data.attributeKey) ? data.attributeKey : null,
                        },
                        {
                            xtype: 'hidden',
                            name: 'type',
                            value: 'countly_has_not_custom_property' // the identifier chosen before when registering the PHP class
                        }
                    ]
                });

                return this.formCustomProperty;
            },
        })
    );
}());