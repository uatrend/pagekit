var Links=function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=4)}([function(t,e,n){"use strict";n.r(e);var r=n(1),o=n.n(r);for(var i in r)"default"!==i&&function(t){n.d(e,t,function(){return r[t]})}(i);e.default=o.a},function(t,e){window.Links=t.exports={data:function(){return{type:!1,link:""}},watch:{type:{handler:function(t){!t&&this.types.length&&(this.type=this.types[0].value)},immediate:!0}},computed:{types:function(){var t=[];return _.forIn(this.$options.components,function(e,n){e.link&&t.push({text:e.link.label,value:n})}),_.sortBy(t,"text")}},components:{}},Vue.component("panel-link",function(e){e(t.exports)})},function(t,e,n){"use strict";var r=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"uk-margin"},[n("label",{staticClass:"uk-form-label",attrs:{for:"form-style"}},[t._v(t._s(t._f("trans")("Extension")))]),t._v(" "),n("div",{staticClass:"uk-form-controls"},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.type,expression:"type"}],staticClass:"uk-width-1-1 uk-select",attrs:{id:"form-style"},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.type=e.target.multiple?n:n[0]}}},t._l(t.types,function(e,r){return n("option",{key:r,domProps:{value:e.value}},[t._v("\n                    "+t._s(t._f("trans")(e.text))+"\n                ")])}),0)])]),t._v(" "),t.type?n(t.type,{tag:"component",attrs:{link:t.link},on:{"update:link":function(e){t.link=e}}}):t._e()],1)},o=[];n.d(e,"a",function(){return r}),n.d(e,"b",function(){return o})},function(t,e,n){"use strict";function r(t,e,n,r,o,i,u,a){var s,l="function"==typeof t?t.options:t;if(e&&(l.render=e,l.staticRenderFns=n,l._compiled=!0),r&&(l.functional=!0),i&&(l._scopeId="data-v-"+i),u?(s=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(u)},l._ssrRegister=s):o&&(s=a?function(){o.call(this,this.$root.$options.shadowRoot)}:o),s)if(l.functional){l._injectStyles=s;var c=l.render;l.render=function(t,e){return s.call(e),c(t,e)}}else{var f=l.beforeCreate;l.beforeCreate=f?[].concat(f,s):[s]}return{exports:t,options:l}}n.d(e,"a",function(){return r})},function(t,e,n){"use strict";n.r(e);var r=n(2),o=n(0);for(var i in o)"default"!==i&&function(t){n.d(e,t,function(){return o[t]})}(i);var u=n(3),a=Object(u.a)(o.default,r.a,r.b,!1,null,null,null);e.default=a.exports}]);