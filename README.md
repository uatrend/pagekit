Updated some Pagekit extensions: [Pagekit News](https://github.com/cssailing/pagekit-news),
[Pagekit Search](https://github.com/cssailing/pagekit-search), [Pagekit Highlight](https://github.com/cssailing/pagekit-highlight). AS IS. Need testing. Thanks to [cssailing](https://github.com/cssailing)!

<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td width="45%">
      <img src="https://cloud.githubusercontent.com/assets/1716665/14317675/ba034b8c-fc09-11e5-81ed-f10f37d86ea5.png" width="100%" title="hover text">
    </td>
    <td width="55%">
      <img src="https://user-images.githubusercontent.com/24713453/64491316-d56f5180-d280-11e9-91b7-68d309489ffe.png" width="100%" alt="accessibility text">
    </td>
  </tr>
</table>

# Pagekit

[![Discord](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/e7Kw47E)

[Homepage](http://pagekit.com) - Official home page.

This is an updated build Pagekit CMS (for developers).

Build includes:

- Pagekit CMS 1.0.18
- Blog extension
- Theme One

Download the latest [build](https://github.com/uatrend/pagekit/releases) or [install from source](#install) before installation.  
The installation procedure is the same as in the official [documentation](https://pagekit.com/docs/getting-started/installation).

Marketplace functionality like install, update and remove works the same like in original version.
**Note!** Before enabling extensions, update them for compatibility. Debug mode and debug panel work the same as in the main version.

------

### Major changes:

- **Required PHP Version - 7.2 or higher(7.4+).**
- **[Updated all project dependencies](https://github.com/uatrend/pagekit/blob/develop/package.json).**
- **Updated all core javascript components**.
- **Removed jQuery**.

Several bugs that are present in the original assembly have been fixed, some styles have been changed for ease of use. The mobile version has remained the same with minor changes.

## <a name="install"></a>Install from source

You can [install Node dependencies, build the front-end components](#node) and run [scripts](#scripts) via [yarn](https://yarnpkg.com/) or [NPM](https://npmjs.org/).

Clone Repository

```
$ git clone git@github.com:uatrend/pagekit.git project-folder
$ cd project-folder
```

Install PHP dependencies

```
$ composer install
```

<a name="node"></a>Install Node dependencies

```
$ yarn install
$ npm install
```

## <a name="scripts"></a>Scripts

Webpack watch:

```
$ yarn watch
$ npm run watch
```

Webpack build (minified):

```
$ yarn build
$ npm run build
```

Linting with eslint:

```
$ yarn lint
$ npm run lint
```

## Gulp tasks

Compile LESS:

```
$ gulp compile
```

Compile and watch LESS:

```
$ gulp watch
```

CLDR locale data for internationalization:

```
$ gulp cldr
```

## Admin Theme

Theme is fully compatible with **UIkit 3**.
Changed default admin theme - script, layout and colors. Added side and top menus with dropdowns.
For individual markup of each page added class page in the body tag generated via php.

Example, class for dashboard page look like:

```html
<body class=“dashboard”>
```

## Editor Settings

Added the ability to select an editor in the settings: HTML Editor, Tinymce or Codemirror.
Moved all editor component dependencies to: ``` app/system/modules/editor/app/assets```.
Added split mode for Tinymce.

## Theme Plugin

(added to core ```/app/system/app/lib/theme.js```)

Ability to programmatically configure the buttons, dropdown lists, pagination and search form in the top menu for each component used (see code).

Example: dashboard - ```index.js```

```javascript
name: 'dashboard',

mixins: [Theme.Mixins.Helper],

...

theme: {
    hiddenHtmlElements: '#dashboard > div:first-child > div:last-child',
    elements() {
        var vm = this;
        return {
            addwidget: {
                scope: 'topmenu-left',
                type: 'dropdown',
                caption: 'Add Widget',
                class: 'uk-button uk-button-text',
                icon: { attrs: { 'uk-icon': 'triangle-down' }},
                dropdown: { options: () => 'mode: click' },
                items: () => vm.getTypes().map((type) => {
                    let props = {
                        on: {click: () => vm.add(type)},
                        caption: type.label,
                        class: 'uk-dropdown-close'
                    }
                    return {...type, ...props}
                }),
            }
        }
    }
}
```

Adding side menu items through PHP - ```$view->$data()```

```php
'view.data' => function ($event, $data) use ($app) {
    if (!$app->isAdmin()) {
        return;
    }
    $data->add('Theme', [
        'SidebarItems' => [
            'additem' => [
                'addpost' => [
                    'caption' => 'Add Post',
                    'attrs' => [
                        'href' => $app['url']->get('admin/blog/post/edit')
                    ],
                    'priority' => 1
                ]
            ]
        ]
    ]);
}
```

------

Thanks to Yootheme and developers!  
Feel free to ask any questions - I will answer as much as possible.
