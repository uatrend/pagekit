!function(t){var e={};function n(r){if(e[r])return e[r].exports;var a=e[r]={i:r,l:!1,exports:{}};return t[r].call(a.exports,a,a.exports,n),a.l=!0,a.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var a in t)n.d(r,a,function(e){return t[e]}.bind(null,a));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=50)}({0:function(t,e,n){"use strict";function r(t,e,n,r,a,i,o,s){var d,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=n,l._compiled=!0),r&&(l.functional=!0),i&&(l._scopeId="data-v-"+i),o?(d=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),a&&a.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},l._ssrRegister=d):a&&(d=s?function(){a.call(this,this.$root.$options.shadowRoot)}:a),d)if(l.functional){l._injectStyles=d;var c=l.render;l.render=function(t,e){return d.call(e),c(t,e)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,d):[d]}return{exports:t,options:l}}n.d(e,"a",function(){return r})},22:function(t,e,n){"use strict";n.r(e);var r=n(23),a=n.n(r);for(var i in r)"default"!==i&&function(t){n.d(e,t,function(){return r[t]})}(i);e.default=a.a},23:function(t,e){t.exports={name:"widget-text",section:{label:"Settings"},inject:["$validator"],props:["widget","config","form"],created:function(){this.$options.components["template-settings"]=this.$parent.$options.components["template-settings"]}},window.Widgets.components["system-text--settings"]=t.exports},30:function(t,e,n){"use strict";var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"pk-grid-large pk-width-sidebar-large",attrs:{"uk-grid":""}},[n("div",{staticClass:"pk-width-content uk-form-stacked"},[n("div",{staticClass:"uk-margin"},[n("label",{staticClass:"uk-form-label",attrs:{for:"form-title"}},[t._v(t._s(t._f("trans")("Title")))]),t._v(" "),n("div",{staticClass:"uk-form-controls"},[n("input",{directives:[{name:"model",rawName:"v-model",value:t.widget.title,expression:"widget.title"},{name:"validate",rawName:"v-validate",value:"required",expression:"'required'"}],staticClass:"uk-width-1-1 uk-form-large uk-input",attrs:{type:"text",name:"title",placeholder:t._f("trans")("Enter Title")},domProps:{value:t.widget.title},on:{input:function(e){e.target.composing||t.$set(t.widget,"title",e.target.value)}}}),t._v(" "),n("div",{directives:[{name:"show",rawName:"v-show",value:t.errors.first("title"),expression:"errors.first('title')"}],staticClass:"uk-text-meta uk-text-danger"},[t._v("\n                    "+t._s(t._f("trans")("Title cannot be blank."))+"\n                ")])])]),t._v(" "),n("div",{staticClass:"uk-margin"},[n("label",{staticClass:"uk-form-label",attrs:{for:"form-title"}},[t._v(t._s(t._f("trans")("Content")))]),t._v(" "),n("v-editor",{attrs:{options:{markdown:t.widget.data.markdown}},model:{value:t.widget.data.content,callback:function(e){t.$set(t.widget.data,"content",e)},expression:"widget.data.content"}}),t._v(" "),n("p",{staticClass:"uk-margin-small-top"},[n("label",[n("input",{directives:[{name:"model",rawName:"v-model",value:t.widget.data.markdown,expression:"widget.data.markdown"}],staticClass:"uk-checkbox",attrs:{type:"checkbox"},domProps:{checked:Array.isArray(t.widget.data.markdown)?t._i(t.widget.data.markdown,null)>-1:t.widget.data.markdown},on:{change:function(e){var n=t.widget.data.markdown,r=e.target,a=!!r.checked;if(Array.isArray(n)){var i=t._i(n,null);r.checked?i<0&&t.$set(t.widget.data,"markdown",n.concat([null])):i>-1&&t.$set(t.widget.data,"markdown",n.slice(0,i).concat(n.slice(i+1)))}else t.$set(t.widget.data,"markdown",a)}}}),n("span",{staticClass:"uk-margin-small-left"},[t._v(t._s(t._f("trans")("Enable Markdown")))])])])],1)]),t._v(" "),n("div",{staticClass:"pk-width-sidebar"},[n("template-settings",{tag:"component",attrs:{widget:t.widget,config:t.config,form:t.form},on:{"update:widget":function(e){t.widget=e},"update:config":function(e){t.config=e}}})],1)])},a=[];n.d(e,"a",function(){return r}),n.d(e,"b",function(){return a})},50:function(t,e,n){"use strict";n.r(e);var r=n(30),a=n(22);for(var i in a)"default"!==i&&function(t){n.d(e,t,function(){return a[t]})}(i);var o=n(0),s=Object(o.a)(a.default,r.a,r.b,!1,null,null,null);e.default=s.exports}});