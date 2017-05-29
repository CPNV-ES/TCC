/**
 * Created by Esteban Sotillo
 * git : https://github.com/Westixy/VerifJS
 * ES5 version
 */
 'use strict';
 if(Array.prototype.forEach==undefined) Array.prototype.forEach=function(cb){for(var i=0;i<this.length;i++){cb(this[i],i);}}
 if(NodeList.prototype.forEach==undefined) NodeList.prototype.forEach=Array.prototype.forEach;


 var VERIF = {
   version: '1.0.1',
   init: function init() {
     var elems = document.querySelectorAll('[data-verif]');
     elems.forEach(function (item) {
       if(item.getAttribute('data-verif-on-blur')!='false')
         item.addEventListener('blur', function (ev) {
           VERIF.verif(item);
         });
     });
   },
   verif: function verif(me, endCb) {
     endCb = endCb || function () {};
     var rules = me.getAttribute('data-verif').split('|');
     var data = me.value;
     var before = me.nextElementSibling;
     var parent = me.parentElement;
     var result = true;
     var resnbr = 0;

    //  Struan : Fait en JQuery voir pour changer
     $(".help-block").remove();
     $(".has-error").removeClass("has-error");

     if (data == '' && rules.indexOf('required') < 0) {
       endCb(true);
       return;
     }

     var _loop = function _loop(i) {
       var sep = rules[i].split(':');
       var rule = sep[0];
       var arg = sep.length > 1 ? sep[1] : null;

       if (typeof VERIF.RULES[rule] == 'undefined') {
         console.error('RULE "{0}" does not exits in VERIF.RULES'.replace(/\{0\}/, rule));
         return 'continue';
       }

       cbResult = function cbResult(res) {
         if (result) {
           resnbr++;
           if (!res) {
             result = false;
             if (me.className.indexOf('verif_error') < 0) me.className += ' verif_error';
             var errtxt = VERIF.RULES[rule].errorText;
             var before_build = document.createElement('div');
             before_build.className = 'verif_message_error';

             if (before == null) before = parent.insertBefore(before_build, me.nextSibling);
             if (before.className.indexOf('verif_message_error') < 0) {
               before = parent.insertBefore(before_build, me.nextSibling);
             }
             var errArg = arg;
             if (typeof VERIF.RULES[rule].argToError != 'undefined') errArg = VERIF.RULES[rule].argToError(arg);
             before.textContent = errtxt.replace(/\{0\}/, errArg);
           }
         }
         if (result && resnbr == rules.length) {
           me.className = me.className.replace('verif_error', '');
           if (before != null) {
             if (before.className.indexOf('verif_message_error') >= 0) {
               parent.removeChild(before);
             }
           }
           endCb(result);
         }
       };

       VERIF.RULES[rule].control(cbResult, data, arg);
     };

     for (var i = 0; i < rules.length; i++) {
       var cbResult;

       var _ret = _loop(i);

       if (_ret === 'continue') continue;
     }
   },
   verifElems: function verifElems(elems, after) {
     var isOk = true;
     var resnbr = 0;
     for (var i = 0; i < elems.length; i++) {
       var onOneEnd = function onOneEnd(res) {
         resnbr++;
         if (isOk && !res) {
           isOk == false;
         }
         if (resnbr == elems.length) {
           after(isOk);
         }
       };
       VERIF.verif(elems[i], onOneEnd);
     }
   },
   verifGroup: function verifGroup(groupId, after) {
     var elems = document.querySelectorAll('[data-verif-group="{0}"][data-verif]'.replace(/\{0\}/, groupId));
     VERIF.verifElems(elems, after);
   },
   verifForm: function verifForm(formName, after) {
     var elems = document.querySelectorAll('form[name="{0}"] [data-verif]'.replace(/\{0\}/, formName));
     VERIF.verifElems(elems, after);
   },
   verifContained: function verifContained(elemQuery, after) {
     var elems = document.querySelectorAll('{0} [data-verif]'.replace(/\{0\}/, elemQuery));
     VERIF.verifElems(elems, after);
   },
   onClickSubmitAfterVerifGroup: function onClickSubmitAfterVerifGroup(elem, formName, formGroup) {
     elem.addEventListener('click', function (e) {
       VERIF.verifGroup(formGroup, function (isOk) {
         if (isOk) document.forms[formName].submit();
       });
     });
   },
   onClickSubmitAfterVerifForm: function onClickSubmitAfterVerifForm(elem, formName) {
     elem.addEventListener('click', function (e) {
       VERIF.verifForm(formName, function (isOk) {
         if (isOk) document.forms[formName].submit();
       });
     });
   },
   addRule: function addRule(name, control, errorText, argToError) {
     VERIF.RULES[name] = {};
     VERIF.RULES[name].control = control;
     VERIF.RULES[name].errorText = errorText;
     if (argToError != 'undefined') VERIF.RULES[name].argToError = argToError;
   },
   AJAX: function AJAX(url, callback, method, data) {
     method = method || 'GET';
     var params = data || null;
     if (params != null) params = typeof data == 'string' ? data : Object.keys(data).map(function (k) {
       return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
     }).join('&');

     var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
     xhr.open(method, url);
     xhr.onreadystatechange = function () {
       if (xhr.readyState > 3) if (xhr.status === 200) {
         callback(xhr.responseText);
       } else {
         console.error('AJAX ERROR -> xhr status : ' + xhr.status);
       }
     };
     xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
     if (params != null) {
       xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       xhr.send(params);
     } else {
       xhr.send();
     }
     return xhr;
   },
   RULES: {
     required: {
       control: function control(res, data) {
         res(data.trim().length > 0);
       },
       errorText: 'Ce champs est requis'
     },
     alphanumerique: {
       control: function control(res, data) {
         res(/^\w+$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être alphanumerique sans espace [A-Za-z0-9_]'
     },
     text: {
       control: function control(res, data) {
         res(/^[A-zÀ-ÿ0-9\.\_ -]+$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être alphanumerique [A-z À-ÿ 0-9 ._ ]'
     },
     int: {
       control: function control(res, data) {
         res(/^\d+$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être nombre entier'
     },
     double: {
       control: function control(res, data) {
         res(/^\d+\.?\d*$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être nombre'
     },
     date: {
       control: function control(res, data) {
         res(/^\d{2}\.\d{2}\.\d{4}$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être une date [jj.mm.aaaa]'
     },
     time: {
       control: function control(res, data) {
         res(/^\d{2}:\d{2}$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être une heure [HH:MM]'
     },
     date_time: {
       control: function control(res, data) {
         res(/^\d{2}\.\d{2}\.\d{4}\ \d{2}:\d{2}$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être une date et heure [jj.mm.aaaa HH:MM]'
     },
     max_l: {
       control: function control(res, data, max) {
         res(data.trim().length <= max);
       },
       errorText: 'Ce champs dois avoir moins de {0} caractères.'
     },
     min_l: {
       control: function control(res, data, min) {
         res(data.trim().length >= min);
       },
       errorText: 'Ce champs dois avoir plus de {0} caractères.'
     },
     max: {
       control: function control(res, data, max) {
         res(data.trim() <= max);
       },
       errorText: 'Doit être inférieur ou égale à {0}'
     },
     min: {
       control: function control(res, data, min) {
         res(data.trim() >= min);
       },
       errorText: 'Doit être supérieur ou égale à {0}'
     },
     email: {
       control: function control(res, data) {
         res(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(data.trim()));
       },
       errorText: 'Veuillez entrer un email correcte'
     },
     phone: {
       control: function control(res, data) {
         res(/^(((\+|00)\d{2,3})|0)([./ -]?\d){9}$/.test(data.trim()));
       },
       errorText: 'Ce champs dois être un numéro de téléphone valide'
     },
     date_past: {
       control: function control(res, data) {
         var now = new Date();
         var date = new Date(data.trim());
         res(now.getTime() - date.getTime() > 0);
       },
       errorText: 'La date doit faire partie du passé'
     },
     date_futur: {
       control: function control(res, data) {
         var now = new Date();
         var date = new Date(data.trim());
         res(now.getTime() - date.getTime() < 0);
       },
       errorText: 'La date doit faire partie du futur'
     },
     date_more_diff: {
       control: function control(res, data, diff) {
         var now = new Date();
         var date = new Date(data.trim());
         var ddiff = new Date(Math.abs(now.getTime() - date.getTime()));
         var mdiff = (ddiff.getFullYear() - 1970) * 12 + ddiff.getMonth();
         res(mdiff >= diff);
       },
       errorText: 'La date doit avoir une différence d\'au moins {0}',
       argToError: function argToError(arg) {
         var y = Math.floor(arg / 12);
         var m = arg % 12;
         return (y > 0 ? y + (y == 1 ? ' an' : ' ans') : '') + (m > 0 ? ' et ' : '') + (m > 0 ? m + ' mois' : '');
       }
     },
     date_less_diff: {
       control: function control(res, data, diff) {
         var now = new Date();
         var date = new Date(data.trim());
         var ddiff = new Date(Math.abs(now.getTime() - date.getTime()));
         var mdiff = (ddiff.getFullYear() - 1970) * 12 + ddiff.getMonth();
         res(mdiff < diff);
       },
       errorText: 'La date doit avoir une différence inférieure à {0}',
       argToError: function argToError(arg) {
         var y = Math.floor(arg / 12);
         var m = arg % 12;
         return (y > 0 ? y + (y == 1 ? ' an' : ' ans') : '') + (m > 0 ? ' et ' : '') + (m > 0 ? m + ' mois' : '');
       }
     },
     ajax: {
       control: function control(res, data, url) {
         VERIF.AJAX(url + '?data=' + encodeURIComponent(data), function (cnt) {
           res(JSON.parse(cnt).response);
         });
       },
       errorText: 'La réponse de "{0}" à été négative'
     }
   }
 };
 window.addEventListener('load', function (e) {
   VERIF.init();
 });
