<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td width="50%">
      <img src="https://cloud.githubusercontent.com/assets/1716665/14317675/ba034b8c-fc09-11e5-81ed-f10f37d86ea5.png" width="100%" title="hover text">
    </td>
    <td width="50%">
      <img src="https://user-images.githubusercontent.com/24713453/63487166-a062b100-c4c3-11e9-817e-8a11c730472c.png" width="100%" alt="accessibility text">
    </td>
  </tr>
</table>

# Pagekit

[![Discord](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/e7Kw47E)

[Homepage](http://pagekit.com) - Official home page.

This is a custom updated build Pagekit CMS (for developers).

Build includes:
- Pagekit CMS 1.0.17
- Blog extension
- Theme One
- Custom Admin Panel

The installation procedure is the same as in the official [documentation](http://pagekit.com/docs) .

Marketplace functionality like install, update and remove works the same like in original version. Before enabling extensions - they need to be updated for compatibility.

---

### Major changes:

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<thead>
<tr>
    <th colspan="2">Updated</th>
    <th colspan="2">Added</th>
</tr>
</thead>
<tbody align="center" width="100%">
  <tr>
    <td width="50%">
        Vue
    </td>
    <td>
        2.6.10
    </td>
    <td width="50%">
        Vue-event-manager
    </td>
    <td width="20%">
        2.1.2
    </td>
  </tr>
  <tr>
    <td>
        Vue-Resource
    </td>
    <td>
        1.5.1
    </td>
    <td>
        Vee-validate
    </td>
    <td>
        2.2.13
    </td>
  </tr>
  <tr>
    <td>
        UIkit
    </td>
    <td>
        3.1.7
    </td>
    <td>
        Vue-nestable
    </td>
    <td>
        2.4.3
    </td>
  </tr>
  <tr>
    <td>
        Lodash
    </td>
    <td>
        4.17.15
    </td>
    <td>
        Flatpickr
    </td>
    <td>
        4.6.1
    </td>
  </tr>
  <tr>
    <td>
        Webpack
    </td>
    <td>
        4.39.2
    </td>
    <td>
        Tinymce
    </td>
    <td>
        5.0.13
    </td>
  </tr>
  <tr>
    <td>
        Gulp
    </td>
    <td>
        4.0.2
    </td>
    <td width="50%" colspan="2">
    </td>
  </tr>
  <tr>
    <td>
        Symfony Framework
    </td>
    <td>
        4.2
    </td>
    <td width="50%" colspan="2">
    </td>
  </tr>
  <tr>
    <td>
        Composer
    </td>
    <td>
        1.8
    </td>
    <td width="50%" colspan="2">
    </td>
  </tr>
  <tr>
    <td>
        Codemirror
    </td>
    <td>
        5.48.2
    </td>
    <td width="50%" colspan="2">
    </td>
  </tr>
  </tbody>
</table>

**Updated all core javascript components** for compability with new dependencies.
**Removed jQuery.**

Ported components from UIkit 2  - **autocomplete, pagination, htmleditor** with markedjs.
Location: ``` app/system/modules/theme/js/components```

## Install from source

```
npm install
```

## NPM scripts

Webpack watch:
```
npm run watch
```
Webpack build (minified):
```
npm run build
```
Linting with eslint:
```
npm run lint
```
## Gulp

Compile and watch theme LESS:
```
gulp watch
```

## Editor Settings

Added the ability to select an editor in the settings: HTML Editor, Tinymce or Codemirror.
Moved all editor component dependencies to: ``` app/system/modules/editor/app/assets```.
Added split mode for Tinymce.

## Admin Theme

Theme is fully compatible with **UIkit 3**.
Changed default admin theme - script, layout and colors. Added side and top menus with dropdowns.
For individual markup of each page added class page in the body tag generated via php.

Example, class for dashboard page look like:
```html
<body class=“dashboard”>
```

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

## Translation

Improved russian translation.

## Fixed Bugs

Getting News Feed in WidgetFeed, selection in InputFilter component and etc.

---

Thanks to Yootheme and developers!
Feel free to ask any questions - I will answer as much as possible.
