const VERIF={
  init:()=>{
    let elems=document.querySelectorAll('[data-verif]');
    elems.forEach((item)=>{
      item.addEventListener('blur', (ev)=>{
        VERIF.verif(item);
      });
    });
  },
  verif:(me)=>{
    let rules=me.dataset.verif.split('|');
    let data=me.value;

    for(let i=0; i<rules.length ; i++){
      let sep=rules[i].split(':');
      let rule=sep[0];
      let arg=(sep.length>1)?sep[1]:null;
      let before = me.previousElementSibling;
      let parent = me.parentElement;
      if(typeof VERIF.RULES[rule]=='undefined'){
        console.error('RULE "{0}" does not exits in VERIF.RULES'.replace(/\{0\}/,rule));
        continue;
      }
      if(!VERIF.RULES[rule].control(data,arg)){
        if(me.className.indexOf('verif_error')<0)
          me.className+=' verif_error';
        let errtxt=VERIF.RULES[rule].errorText;
        let before_build=document.createElement('div');
        before_build.className='verif_message_error';

        if(before == null) before = parent.insertBefore(before_build,me);
        if(before.className.indexOf('verif_message_error')<0){
          before = parent.insertBefore(before_build,me);
        }
        before.textContent=errtxt.replace(/\{0\}/,arg);

        return false;
      }else if(me.className.indexOf('verif_error')>=0){
        me.className=me.className.replace('verif_error','')
        if(before != null){
          if(before.className.indexOf('verif_message_error')>=0){
            parent.removeChild(before);
          }
        }
      }
    }
    return true;
  },
  verifGroup:(groupId)=>{
    let isOk=true;
    let elems=document.querySelectorAll('[data-verif-group="{0}"][data-verif]'
                                        .replace(/\{0\}/,groupId));
    for(let i=0;i<elems.length;i++){
      if(!VERIF.verif(elems[i])) isOk=false;
    }

    return isOk;
  },
  verifOnCLick:(elem,formName,formGroup)=>{
    elem.addEventListener('click', (e)=>{
      if(VERIF.verifGroup(formGroup))
        document.forms[formName].submit();
    });
  },
  addRule:(name,control,errorText)=>{
    VERIF.RULES[name]={};
    VERIF.RULES[name].control=control;
    VERIF.RULES[name].errorText=errorText;
  },
  RULES:{
    required:{
      control:(data)=>{return (data.trim().length>0);},
      errorText:'Ce champs est requis'
    },
    alphanumerique:{
      control:(data)=>{return /^\w+$/.test(data.trim());},
      errorText:'Ce champs dois être alphanumerique sans espace [A-Za-z0-9_]'
    },
    text:{
      control:(data)=>{return /^[\w\ ]+$/.test(data.trim());},
      errorText:'Ce champs dois être alphanumerique [A-Za-z0-9_ ]'
    },
    int:{
      control:(data)=>{return /^\d+$/.test(data.trim());},
      errorText:'Ce champs dois être nombre entier'
    },
    double:{
      control:(data)=>{return /^\d+\.?\d*$/.test(data.trim());},
      errorText:'Ce champs dois être nombre'
    },
    date:{
      control:(data)=>{return /^\d{2}\.\d{2}\.\d{4}$/.test(data.trim());},
      errorText:'Ce champs dois être une date [jj.mm.aaaa]'
    },
    time:{
      control:(data)=>{return /^\d{2}:\d{2}$/.test(data.trim());},
      errorText:'Ce champs dois être une heure [HH:MM]'
    },
    date_time:{
      control:(data)=>{return /^\d{2}\.\d{2}\.\d{4}\ \d{2}:\d{2}$/.test(data.trim());},
      errorText:'Ce champs dois être une date et heure [jj.mm.aaaa HH:MM]'
    },
    max_l:{
      control:(data,max)=>{return (data.trim().length<=max);},
      errorText:'Ce champs dois avoir moins de {0} caractères.'
    },
    min_l:{
      control:(data,min)=>{return (data.trim().length>=min);},
      errorText:'Ce champs dois avoir plus de {0} caractères.'
    },
    max:{
      control:(data,max)=>{return (data.trim()<=max);},
      errorText:'Doit être inférieur ou égale à {0}'
    },
    min:{
      control:(data,min)=>{return (data.trim()>=min);},
      errorText:'Doit être supérieur ou égale à {0}'
    },
    email:{
      control:(data)=>{return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                              .test(data.trim());},
      errorText:'Veuillez entrer un email correcte'
    },
    phone:{
      control:(data)=>{
        return /^(((\+|00)\d{2,3})|0)([./ -]?\d){9}$/
               .test(data.trim());
      },
      errorText:'Ce champs dois être un numéro de téléphone valide'
    },
    date_past:{
      control:(data)=>{
        let ds=data.trim().split('.');
        let now=new Date();
        let date=new Date(ds[2],ds[1],ds[0]);
        return (now.getTime()-date.getTime()>0);
      },
      errorText:'La date doit faire partie du passé'
    },
    date_futur:{
      control:(data)=>{
        let ds=data.trim().split('.');
        let now=new Date();
        let date=new Date(ds[2],ds[1],ds[0]);
        return (now.getTime()-date.getTime()<0);
      },
      errorText:'La date doit faire partie du futur'
    },
    date_more_diff:{
      control:(data,diff)=>{
        let ds=data.trim().split('.');
        let now=new Date();
        let date=new Date(ds[2],ds[1],ds[0]);
        let ddiff=new Date(Math.abs(now.getTime()-date.getTime()));
        let mdiff=(ddiff.getFullYear()-1970)*12+ddiff.getMonth();
        return (mdiff>=diff);
      },
      errorText:'La date doit avoir une différence d\'au moins {0} mois'
    },
    date_less_diff:{
      control:(data,diff)=>{
        let ds=data.trim().split('.');
        let now=new Date();
        let date=new Date(ds[2],ds[1],ds[0]);
        let ddiff=new Date(Math.abs(now.getTime()-date.getTime()));
        let mdiff=(ddiff.getFullYear()-1970)*12+ddiff.getMonth();
        return (mdiff<diff);
      },
      errorText:'La date doit avoir une différence inférieure à {0} mois'
    },
  }
}
window.addEventListener('load', function(e) {
  VERIF.init()
});
