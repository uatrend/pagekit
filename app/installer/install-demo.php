<?php

use Pagekit\Application as App;

$positions = ['Top A', 'Top B', 'Top C', 'Bottom A', 'Bottom B', 'Bottom C'];

App::config()->set('system/site', App::config('system/site')->merge([
    'frontpage' => 1, 'view' => ['logo' => 'storage/pagekit-logo.svg']
]));

App::db()->insert('@system_config', ['name' => 'theme-one', 'value' => '{"logo_contrast":"storage/pagekit-logo-contrast.svg","logo_offcanvas":"storage/pagekit-logo-contrast.svg","header":{"layout":"horizontal-right","fullwidth":false,"logo_center":false,"logo_padding_remove":false},"navbar":{"sticky":"2","dropbar":"","offcanvas":{"mode":"reveal","overlay":true,"flip":true},"dropbar_align":"center","dropdown_boundary":false},"_menus":{"main":"main","offcanvas":"main"},"_positions":{"hero":[1,2,3],"footer":[30],"sidebar":[28,29],"top-a":[4,5,6,7],"top-b":[8,9,10,11],"top-c":[12,13,14,15],"bottom-a":[16,17,18,19],"bottom-b":[20,21,22,23],"bottom-c":[24,25,26,27],"navbar":[],"header":[31,32,33,34,35,36,38,39,40,41,43,42]},"_widgets":{"1":{"title_hide":true,"title_size":"uk-panel-title","alignment":true,"html_class":"","panel":""},"2":{"title_hide":true,"title_size":"uk-panel-title","alignment":true,"html_class":"","panel":""},"3":{"title_hide":true,"title_size":"uk-card-title","alignment":true,"html_class":"","panel":""},"4":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"5":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"6":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"7":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"8":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"9":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"10":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"11":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"12":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"13":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"14":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"15":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"16":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"17":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"18":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"19":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"20":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"21":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"22":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"23":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"24":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"25":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"26":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"27":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"28":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"29":{"title_hide":false,"title_size":"uk-card-title","alignment":"","html_class":"","panel":""},"30":{"title_hide":true,"title_size":"uk-card-title","alignment":true,"html_class":"","panel":""},"31":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"32":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"33":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"34":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"35":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"36":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"37":{"title_hide":true,"title_size":"uk-card-title","alignment":true,"html_class":"","panel":""},"38":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"39":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"40":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"41":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"42":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"43":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""},"44":{"title_hide":false,"title_size":"uk-h3","alignment":"","html_class":"","panel":""}},"_nodes":{"1":{"title_hide":true,"title_large":false,"alignment":true,"html_class":"","content_hide":false,"sidebar_first":false,"positions":{"hero":{"height":"full","style":"uk-section-secondary","size":"uk-section-large","image":"storage/home-hero.jpg","image_position":"","effect":"","width":"","vertical_align":"middle","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":"","overlap":false,"header_transparent":true,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-c":{"style":"uk-section-default","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-c":{"style":"uk-section-default","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"main":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"uk-section-large","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":"","overlap":"","header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false}}},"2":{"title_hide":true,"title_large":false,"alignment":true,"html_class":"","content_hide":false,"sidebar_first":false,"positions":{"hero":{"height":"full","style":"uk-section-secondary","size":"uk-section-large","image":"storage/theme-one/about-hero.jpg","image_position":"","effect":"fixed","width":"","vertical_align":"middle","preserve_color":true,"overlap":false,"header_transparent":true,"header_preserve_color":true,"header_transparent_noplaceholder":true},"top-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"main":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"uk-section-large","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false}}},"3":{"title_hide":false,"title_large":false,"alignment":"","html_class":"","content_hide":false,"sidebar_first":false,"positions":{"hero":{"height":"","style":"uk-section-secondary","size":"uk-section-xlarge","image":"","image_position":"","effect":"","width":"","vertical_align":"middle","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"main":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"uk-section-xlarge","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false}}},"4":{"title_hide":false,"title_large":false,"alignment":false,"html_class":"","content_hide":false,"sidebar_first":false,"positions":{"hero":{"height":"","style":"uk-section-secondary","size":"uk-section-xlarge","image":"","image_position":"","effect":"","width":"","vertical_align":"middle","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":"","overlap":"","header_transparent":true,"header_preserve_color":false,"header_transparent_noplaceholder":true},"top-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-a":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-c":{"style":"uk-section-muted","image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"top-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"main":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"uk-section-large","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":"","overlap":"","header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false},"bottom-b":{"image":"","image_position":"","effect":"","width":"","height":"","vertical_align":"middle","style":"uk-section-default","size":"","padding_remove_top":false,"padding_remove_bottom":false,"preserve_color":false,"overlap":false,"header_transparent":false,"header_preserve_color":false,"header_transparent_noplaceholder":false}}}}}']);

//Nodes
App::db()->insert('@system_node', ['priority' => 1, 'status' => 1, 'title' => 'Home', 'slug' => 'home', 'path' => '/home', 'link' => '@page/1', 'type' => 'page', 'menu' => 'main', 'data' => "{\"defaults\":{\"id\":1}}"]);

App::db()->insert('@system_node', ['priority' => 2, 'status' => 1, 'title' => 'About', 'slug' => 'about', 'path' => '/about', 'link' => '@page/2', 'type' => 'page', 'menu' => 'main', 'data' => "{\"defaults\":{\"id\":2}}"]);

App::db()->insert('@system_node', ['priority' => 4, 'status' => 1, 'title' => 'Positions', 'slug' => 'positions', 'path' => '/positions', 'link' => '@page/3', 'type' => 'page', 'menu' => 'main', 'data' => "{\"defaults\":{\"id\":3}}"]);

App::db()->insert('@system_node', ['priority' => 3, 'status' => 1, 'title' => 'Blog', 'slug' => 'blog', 'path' => '/blog', 'link' => '@blog', 'type' => 'blog', 'menu' => 'main']);

// Hero positions
App::db()->insert('@system_widget', ['title' => 'Hello, I\'m Pagekit', 'type' => 'system/text', 'status' => 1, 'nodes' => 1, 'data' => '{"content":"<h1 class=\"uk-heading-large uk-margin-large-bottom\">Hello, I\'m Pagekit,<br class=\"uk-visible@s\"> your new favorite CMS.<\/h1>\n\n<a class=\"uk-button uk-button-default uk-button-large\" href=\"http:\/\/www.pagekit.com\">Get started<\/a>"}']);

App::db()->insert('@system_widget', ['title' => 'About Hero', 'type' => 'system/text', 'status' => 1, 'nodes' => 2, 'data' => '{"content":"<p class=\"uk-text-uppercase\">Anna Thompson</p>\n\n<h1 class=\"uk-margin-large-bottom uk-margin-remove-top\">I\'m a freelancer in graphic<br class=\"uk-hidden-small\"> design and photography.</h1>\n\n<ul class=\"uk-grid-medium uk-flex uk-flex-center\" uk-grid>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"pinterest\" ratio=\"2\"></a></li>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"twitter\" ratio=\"2\"></a></li>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"behance\" ratio=\"2\"></a></li>\n</ul>"}']);

App::db()->insert('@system_widget', ['title' => 'Positions Hero', 'type' => 'system/text', 'status' => 1, 'nodes' => 3, 'data' => '{"content":"<div class=\"uk-flex uk-flex-middle uk-flex-center\">\n    <span uk-icon=\"image\" ratio=\"3\" />\n</div>\n<div class=\"uk-margin\">\n    <h3><i>Some introduction content.</i></h3>\n</div>"}']);

// Modules in positions
foreach ($positions as $position) {

    for ($i = 1; $i <= 4; $i++) {
        App::db()->insert('@system_widget', ['title' => $position, 'type' => 'system/text', 'status' => 1, 'nodes' => 3, 'data' => '{"content":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."}']);
    }

}

// Modules in sidebar
for ($i = 1; $i <= 2; $i++) {
    App::db()->insert('@system_widget', ['title' => 'Sidebar', 'type' => 'system/text', 'status' => 1, 'nodes' => 3, 'data' => '{"content":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus."}']);
}

// Footer
App::db()->insert('@system_widget', ['title' => 'Footer', 'type' => 'system/text', 'status' => 1, 'data' => '{"content":"<ul class=\"uk-grid-medium uk-flex uk-flex-center\" uk-grid>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"pinterest\" ratio=\"1.5\"></a></li>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"twitter\" ratio=\"1.5\"></a></li>\n    <li><a href=\"#\" class=\"uk-icon-link\" uk-icon=\"behance\" ratio=\"1.5\"></a></li>\n</ul>\n<ul class=\"uk-subnav uk-margin uk-flex uk-flex-center\">\n    <li><a href=\"#\">Street, Country</a></li>\n    <li><a href=\"#\">(123) 456-7899</a></li>\n    <li><a href=\"#\">email@example.com</a></li>\n</ul>\n<p class=\"uk-text-small\">Powered by <a href=\"https://pagekit.com\">Pagekit</a></p>"}']);

// Pages
App::db()->insert('@system_page', [
    'title' => 'Home',
    'content' => "<div class=\"uk-width-3-4@m uk-container\">\n    <h3 class=\"uk-h1 uk-margin-large-bottom\">Uniting fresh design and clean code<br class=\"uk-visible@s\"> to create beautiful websites.</h3>\n    <p class=\"uk-width-2-3@m uk-container\">Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Nullam quis risus eget urna mollis ornare vel eu leo. Cras mattis consectetur purus sit amet fermentum. Nulla vitae elit libero, a pharetra augue. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>\n    <p class=\"uk-margin-large\"><i>For more photography check our <span class=\"uk-text-emphasis\"><a href=\"#\" class=\"uk-link-reset uk-button-text\">Instagram</a><span></i></p>\n</div>\n<div class=\"uk-margin-large-top uk-child-width-1-2@m\" uk-grid>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-margin-large\"><img data-src=\"storage/theme-one/home-01.jpg\" alt=\"home-01\" uk-img></div>\n            <h3 class=\"uk-h2\">About Us</h3>\n            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\n            <p><a href=\"#\" class=\"uk-button uk-button-text\">Read more</a></p>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-margin-large\"><img data-src=\"storage/theme-one/home-02.jpg\" alt=\"home-02\" uk-img></div>\n            <h3 class=\"uk-h2\">What's New?</h3>\n            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>\n            <p><a href=\"#\" class=\"uk-button uk-button-text\">Read more</a></p>\n        </div>\n    </div>\n</div>",
    'data' => '{"title":null}'
]);

App::db()->insert('@system_page', [
    'title' => 'About',
    'content' => "<div class=\"uk-margin-large-bottom\">\n    <h2><q>Choose someone with a passion for meaningful<br class=\"uk-hidden-small\"> and effective design to work with you.</q></h2>\n</div>\n\n<div class=\"uk-grid-medium uk-child-width-1-2@m uk-grid-match\" uk-grid>\n    <div>\n        <div class=\"uk-panel\"><p class=\"uk-dropcap uk-text-justify\">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque pena tibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla cons equat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p></div>\n    </div>\n    <div>\n        <div class=\"uk-panel\"><p class=\"uk-text-justify\">Dorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p></div>\n    </div>\n</div>\n\n<h3 class=\"uk-margin-large uk-text-center\">Latest Work</h3>\n\n<div class=\"uk-grid-small uk-child-width-1-3@m uk-grid-match\" uk-grid uk-lightbox>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-01.jpg\" alt=\"about-01\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-01.jpg\"></a>\n            </div>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-02.jpg\" alt=\"about-02\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-02.jpg\"></a>\n            </div>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-03.jpg\" alt=\"about-03\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-03.jpg\"></a>\n            </div>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-04.jpg\" alt=\"about-04\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-04.jpg\"></a>\n            </div>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-05.jpg\" alt=\"about-05\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-05.jpg\"></a>\n            </div>\n        </div>\n    </div>\n    <div>\n        <div class=\"uk-panel\">\n            <div class=\"uk-inline uk-transition-toggle\">\n                <img data-src=\"storage/theme-one/about-06.jpg\" alt=\"about-06\" uk-img>\n                <div class=\"uk-overlay-primary uk-transition-fade uk-position-cover\">\n                    <span class=\"uk-position-center\" uk-icon=\"icon: plus; ratio: 2\"></span>\n                </div>\n                <a class=\"uk-position-cover\" href=\"/storage/theme-one/about-06.jpg\"></a>\n            </div>\n        </div>\n    </div>\n</div>",
    'data' => '{"title":null}'
]);

App::db()->insert('@system_page', [
    'title' => 'Positions',
    'content' => "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>\n\n<p>Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>\n\n<blockquote>Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna.</blockquote>\n\n<p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>",
    'data' => '{"title":null}'
]);

if (App::db()->getUtility()->tableExists('@blog_post')) {

    App::db()->insert('@system_config', ['name' => 'blog', 'value' => '{"comments":{"autoclose":false,"autoclose_days":14,"blacklist":"","comments_per_page":20,"gravatar":true,"max_depth":5,"maxlinks":2,"minidle":120,"nested":true,"notifications":"always","order":"ASC","replymail":true,"require_email":true},"posts":{"posts_per_page":"4","comments_enabled":true,"markdown_enabled":true},"permalink":{"type":"{slug}","custom":"{slug}"},"feed":{"type":"rss2","limit":20}}']);

    function defaults($entry) {
        $entries = [
            'post' => [
                'user_id' => 1,
                'status' => 2,
                'date' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'content' => "<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>\n[readmore]\n<p>Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>\n\n<blockquote>Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna.</blockquote>\n\n<p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>\n\n<h2>The Idea</h2>\n\n<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus.</p>\n\n<ul>\n    <li>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</li>\n    <li>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.</li>\n</ul>\n\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>",
                'excerpt' => '',
                'comment_status' => 1,
                'data' => '{"title":null,"markdown":true}'
            ],
            'comment' => [
                'user_id' => 0,
                'url' => '',
                'ip' => '127.0.0.1',
                'created' => date('Y-m-d H:i:s'),
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                'status' => 1
            ]
        ];
        return $entries[$entry];
    };

    $posts = [
        [
            'slug' => 'let-me-introduce-pagekit',
            'title' => 'Let me introduce Pagekit',
            'data' => '{"image": {"alt":"Let me introduce Pagekit","src":"storage/theme-one/blog-07.jpg"}}'
        ],
        [
            'slug' => 'from-idea-to-great-product',
            'title' => 'From idea to great product',
            'data' => '{"image": {"alt":"From idea to great product","src":"storage/theme-one/blog-06.jpg"}}'
        ],
        [
            'slug' => 'make-beautiful-websites',
            'title' => 'Make beautiful websites',
            'data' => '{"image": {"alt":"Make beautiful websites","src":"storage/theme-one/blog-05.jpg"}}'
        ],
        [
            'slug' => 'use-pagekit-for-any-project',
            'title' => 'Use Pagekit for any project',
            'data' => '{"image": {"alt":"Use Pagekit for any project","src":"storage/theme-one/blog-04.jpg"}}'
        ],
        [
            'slug' => 'inside-pagekit',
            'title' => 'Inside Pagekit',
            'data' => '{"image": {"alt":"Inside Pagekit","src":"storage/theme-one/about-05.jpg"}}',
            'comments' => [
                [ 'author' => 'Susan', 'email' => 'susan@susanmail.org' ],
            ]
        ],
        [
            'slug' => 'get-out-of-your-comfort-zone',
            'title' => 'Get out of your comfort zone',
            'data' => '{"image": {"alt":"Get out of your comfort zone","src":"storage/theme-one/blog-02.jpg"}}',
            'comments' => [
                [ 'author' => 'Sarah', 'email' => 'sarah@sarahmail.org' ],
                [ 'author' => 'Steve', 'email' => 'steve@stevesmail.org' ]
            ]
        ],
        [
            'slug' => 'hello-pagekit',
            'title' => 'Designing for a cause',
            'data' => '{"image": {"alt":"Designing for a cause","src":"storage/theme-one/blog-01.jpg"}}',
            'comments' => [
                [ 'author' => 'Mike', 'email' => 'mike@mikesmail.org' ],
                [ 'user_id' => 1, 'author' => $user['username'], 'email' => $user['email'], 'comments' => [ [ 'author' => 'Tom', 'email' => 'tom@tomsmail.org' ], [ 'author' => 'Bill', 'email' => 'bill@billsmail.org' ] ] ],
                [ 'author' => 'Jessica', 'email' => 'jessica@jessicamail.org' ]
            ]
        ]
    ];

    $global_comment_count = 0;

    function pushComments($comments, $id, $parent_id = 0) {
        global $global_comment_count;

        $current_comment_count = 0;

        if (!App::db()->getUtility()->tableExists('@blog_comment')) {
            return;
        }

        foreach($comments as $key => $comment) {
            $child_comments = null;

            $comment = array_merge(defaults('comment'), $comment);
            $comment['parent_id'] = $parent_id;
            $comment['post_id'] = $id;

            if (isset($comment['comments'])) {
                $child_comments = $comment['comments'];
                unset($comment['comments']);
            }

            App::db()->insert('@blog_comment', $comment);
            $global_comment_count ++;
            $current_comment_count ++;

            if ($child_comments) {
                $current_comment_count +=pushComments($child_comments, $id, $global_comment_count);
            }
        }

        return $current_comment_count;
    };

    foreach($posts as $key => $post) {

        if (isset($post['comments'])) {
            $comments = $post['comments'];
            $post['comment_count'] = pushComments($comments, $key + 1);
            unset($post['comments']);
        }

        App::db()->insert('@blog_post', array_merge(defaults('post'), $post));
    };
}