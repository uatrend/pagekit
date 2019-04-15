<table width="100%" cellspacing="0" cellpadding="0" border="0">
  <tr bgcolor="#ffffff">
    <td width="50%">
      <img src="https://cloud.githubusercontent.com/assets/1716665/14317675/ba034b8c-fc09-11e5-81ed-f10f37d86ea5.png" width="100%" title="hover text">
    </td>
    <td width="50%">
      <img src="https://user-images.githubusercontent.com/24713453/54700434-d4245e00-4b54-11e9-83d8-cfb00087dc2d.png" width="100%" alt="accessibility text">
    </td>
  </tr>
</table>

# Pagekit

[![Discord](https://img.shields.io/badge/chat-on%20discord-7289da.svg)](https://discord.gg/e7Kw47E)

[Homepage](http://pagekit.com) - Official home page

Attention! This is a custom updated build Pagekit CMS (for developers). Javascript frameworks updated, Symfony Foundation Framework updated, PHP core remains the same (with a little changes).

This build includes an updated CMS, blog extension, theme-one and custom admin panel. The installation procedure is the same as in the official [documentation](http://pagekit.com/docs) .

Everything (at first glance, maybe more testing is needed) including the admin panel, installing and updating themes and extensions works as usual. Be attentive before installing extensions that do not support the latest versions Webpack, Vue and UIkit. Need official changes in Marketplace for checking dependencies versions.

Default UIkit2 editor was removed. Default editors in this build - TinyMCE and Codemirror.

The process of developing and updating extensions does not require large changes. The algorithm is the same as in the official documentation (see code). Debug bar exstension updated too.

Build based on Pagekit CMS 1.0.16

---

#### Updated

Javascript Frameworks: Vue, UIkit, Lodash. Removed all jQuery dependencies (No jQuery).

Vue components: Vue-resource

PHP Frameworks: Symfony Foundation Framework, Composer

Building environment: Webpack, Babel, Gulp, style and script loaders.

Javascript libraries: Tinymce, Codemirror

Javascript code of all components (the code is very close to the original for understanding)

---

#### Added

Vue components: vue-event-manager, vue-nestable, vee-validate, vue2-filters.

UIkit components (based on UIkit2): autocomplete, pagination

Javascript libraries: Flatpickr (for input-date component).

---

Thanks to Yootheme and developers!

Feel free to ask any questions - I will answer as much as possible.

