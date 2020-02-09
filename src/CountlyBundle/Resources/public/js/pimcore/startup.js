pimcore.registerNS("pimcore.plugin.CountlyBundle");

pimcore.plugin.CountlyBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.CountlyBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {

        var countlyMenu = [];

        countlyMenu.push({
            text: t("funnels"),
            iconCls: "pimcore_nav_icon_reports",
        });

        var extensionManagerMenu = new Ext.Action({
            text: t("countly"),
            iconCls: 'pimcore_nav_icon_reports',
            menu: {
                cls: "pimcore_navigation_flyout",
                shadow: false,
                items: countlyMenu
            }
        });

        layoutToolbar.extensionManagerMenu.add(extensionManagerMenu);
    }
});

var CountlyBundlePlugin = new pimcore.plugin.CountlyBundle();
