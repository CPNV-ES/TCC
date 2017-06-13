# VerifJS

This script is used to verify field easily. Also works asynchronously.(ex: with ajax on the control)<small>compatible : IE10, firefox 45+, chrome</small>

## Getting stated

- include the script on the html `<script src="verif.js" ></script>` (before fields that you want to verify)
- add a `data-verif` attribute to your inputs
```html
<input type="text" data-verif="required|text|min_l:6|max_l:20">
```
- add some css for the error message
```css
.verif_error{
  border-color: red;
}
.verif_message_error{
  color: red;
}
```

## Rules

- `required` : value != null
- `int` : value is an integer
- `double` : value is a double (or float)
- `text` : value is [a-zA-Z0-9_ ]* with space and accented characters
- `alphanumerique` : value is [a-zA-Z0-9_]* without space
- `email` : value is an email
- `phone` : value is a phone (International or CH)
- `date` : value is like 'dd.mm.yyyy'
- `time` : value is like 'HH:MM'
- `date_time` : value is like 'dd.mm.yyyy HH:MM'
- `max:<arg>` : value is <= than arg
- `min:<arg>` : value is >= than arg
- `max_l:<arg>` : value.length is <= than arg
- `min_l:<arg>` : value.length is >= than arg
- `date_past` : date is before now
- `date_futur` : date is after now
- `date_more_diff:<arg>` : difference between value and now is more or equal than arg (in month)
- `date_less_diff:<arg>` : difference between value and now is less than arg (in month)
- `ajax:<url>` : Wait for a json like `{"response":true}` for a GET request on `<url>?data=<input.value>`<br>NB : for `url`, dont use "http://...", use `/some/try.php` or `./try.php` or `try.php`

## Add a custom rule

in an other js file, type
```javascript
VERIF.addRule(
  'customRuleName',
  // arg can be null
  function(result,data,arg){ result(data.trim()==arg)},
  'A custom error message {0}',
  // not required
  function(arg){return arg+' some text'}
);
```
or
```javascript
VERIF.RULES.customRuleName={
  // arg can be null
  control:function(result,data,arg){ result(data.trim()==arg)},
  errorText:'A custom error message',
  // not required
  argToError:function(arg){return arg+' some text'}
}
```
to use :
```html
                          <!-- arg not required -->
<input type="text" data-verif="customRuleName:arg">
```

## Ignore verif onblur (only when verif group or form)

to use :
```html
<input data-verif-on-blur="false" type="text" data-verif="required|text|min_l:6|max_l:20">
```

## Verify on button click <small>and submit</small>

### For a form

some html :
```html
<form name="myForm" method="post" action="">
  <input type="text" data-verif="required|text|min_l:6|max_l:20">
  <input type="email" data-verif="required|email">
  <input id="btn_verify" type="button">
</form>
```
and some js :
```javascript
var btn=document.getElementById('btn_verify');
VERIF.onClickSubmitAfterVerifForm(btn,'myForm');
```
or
```javascript
document.querySelector('#btn_verify').addEventListener('click', function(){
  VERIF.verifForm('myForm',function(isOk){
    if(isOk) document.forms["myForm"].submit();
  });
});
```

### For a group

some html :
```html
<form name="myForm" method="post" action="">
  <input type="text" data-verif-group="groupName" data-verif="required|text|min_l:6|max_l:20">
  <input type="email" data-verif-group="groupName" data-verif="required|email">
  <input id="btn_verify" type="button">
</form>
```
and some js :
```javascript
var btn=document.getElementById('btn_verify');
VERIF.onClickSubmitAfterVerifGroup(btn,'myForm','groupName');
```
or
```javascript
document.querySelector('#btn_verify').addEventListener('click', ()=>{
  VERIF.verifGroup('groupName',function(isOk){
    if(isOk) document.forms["myForm"].submit();
  });
});
```

## Verify some elements

some html :
```html
<form name="myForm" method="post" action="">
  <input type="text" data-verif-group="groupName" data-verif="required|text|min_l:6|max_l:20">
  <input type="email" data-verif-group="groupName" data-verif="required|email">
  <input id="btn_verify" type="button">
</form>
```
some js for an array of elements :
```javascript
let elems=document.querySelectorAll('form input[data-verif]');
VERIF.verifElems(elems,function(isOk){if(isOK) console.log('OK');});
```
some js for a form :
```javascript
VERIF.verifForm('myForm',function(isOk){if(isOK) console.log('OK');});
```
some js for a group :
```javascript
VERIF.verifForm('groupName',function(isOk){if(isOK) console.log('OK');});
```

## Verify one field

some html :
```html
<input id="myField" type="text" data-verif="required|phone">
```
and some js:
```javascript
VERIF.verif(document.getElementById('myField'),function(res){if(res) console.log('OK');});
```

## simple AJAX function

`VERIF.AJAX` have some params

- `url` : I think its ok for this :)
- `callback` : it get the text response on param like `function(response){JSON.parse(response)}`
- `method` : [_not required_] by default GET and can be `GET|POST|PUT|PATCH|DELETE`
- `data` : [_not required for GET and DELETE_] can be a string (encoded for uri) or a hash

GET :
```javascript
VERIF.AJAX(
  url+'?data='+encodeURIComponent(data),
  function(cnt){res(JSON.parse(cnt).response);}
);
```

POST :
```javascript
VERIF.AJAX(
  url,
  function(cnt){res(JSON.parse(cnt).response);},
  'POST',
  {data:'data',img:'doge.jpg'}
);
```
