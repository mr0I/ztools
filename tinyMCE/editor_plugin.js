(function() {

    tinymce.create('tinymce.plugins.aparat_shortcode', {

        init : function(ed, url){
            ed.addButton('aparat_shortcode', {
                title : 'افزودن ویدئوی آپارات',
                onclick : function() {
                 var aparat = prompt("شناسه ویدئوی آپارات را درج کنید :\n\nبه‌عنوان مثال شناسه ویدئوی http://www.aparat.com/v/iybdS عبارت است از : iybdS",'');
                 if( aparat ){
                        //ed.selection.setContent('[aparat id="'+ ed.selection.getContent() +'"]');
                        ed.selection.setContent('[zaparat id="'+ aparat +'"]');
                    }
                },
                image: url + "/aparat.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'ZAparat Shrotcut',
                author : 'Zeus',
                authorurl : 'https://sisoog.com/user/zeus',
                infourl : 'https://sisoog.com/',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('aparat_shortcode', tinymce.plugins.aparat_shortcode);

})();
