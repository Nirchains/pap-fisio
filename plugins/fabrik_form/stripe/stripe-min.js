/*! Fabrik */

define(["jquery","fab/fabrik"],function(r,p){"use strict";return new Class({options:{publicKey:"",item:"",zipCode:!0,allowRememberMe:!1,email:"",name:"",panelLabel:"",useCheckout:!0,failedValidation:!1,stripeTokenId:"",stripeTokenEmail:"",stripeTokenOpts:"",billingAddress:!1,couponElement:"",renderOrder:""},initialize:function(t){var e,o,i=this;(this.options=r.extend(this.options,t),this.form=p.getBlock("form_"+this.options.formid),p.FabrikStripeForm=null,p.FabrikStripeFormSubmitting=!1,""!==this.options.productElement)?(this.productElement=i.form.formElements.get(this.options.productElement),e=this.productElement.hasSubElements()?this.productElement.getChangeEvent():this.productElement.getBlurEvent(),i.form.dispatchEvent("",this.options.productElement,e,function(t){i.getCost(t)}),""!==this.options.qtyElement?(this.qtyElement=i.form.formElements.get(this.options.qtyElement),o=this.productElement.hasSubElements()?this.productElement.getChangeEvent():this.productElement.getBlurEvent(),i.form.dispatchEvent("",this.options.qtyElement,o,function(t){i.getCost(t)}),"fabrikfield"===this.qtyElement.plugin&&i.form.dispatchEvent("",this.options.qtyElement,"input",function(t){i.getCost(t)})):this.qtyElement=!1):(this.productElement=!1,this.qtyElement=!1);if(""!==this.options.couponElement){this.couponElement=i.form.formElements.get(this.options.couponElement);var n=this.couponElement.getBlurEvent();i.form.dispatchEvent("",this.options.couponElement,n,function(t){""!==i.options.productElement?i.getCost(t):i.getCoupon(t)})}else this.couponElement=!1;if(""!==this.options.totalElement?this.totalElement=i.form.formElements.get(this.options.totalElement):this.totalElement=!1,this.options.useCheckout)this.options.failedValidation||requirejs(["https://checkout.stripe.com/checkout.js?"],function(t){i.handler=StripeCheckout.configure({key:i.options.publicKey,image:"https://stripe.com/img/documentation/checkout/marketplace.png",locale:"auto",currency:i.options.currencyCode,token:function(t,e){p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_id",value:t.id,type:"hidden"})),p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_email",value:t.email,type:"hidden"})),p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_opts",value:JSON.stringify(e),type:"hidden"})),p.FabrikStripeForm.mockSubmit()},closed:function(){p.FabrikStripeFormSubmitting=!0}})}),p.addEvent("fabrik.form.submit.start",function(t,e,o){this.options.useCheckout&&(this.options.ccOnFree||0!=this.options.amount)&&(this.options.failedValidation?(this.form.form.adopt(new Element("input",{name:"stripe_token_id",value:this.options.stripeTokenId,type:"hidden"})),this.form.form.adopt(new Element("input",{name:"stripe_token_email",value:this.options.stripeTokenEmail,type:"hidden"})),this.form.form.adopt(new Element("input",{name:"stripe_token_opts",value:this.options.stripeTokenOpts,type:"hidden"})),t.result=!0):void 0!==p.FabrikStripeForm&&!0===p.FabrikStripeFormSubmitting&&0!==r("input[name=stripe_token_id]").length||(p.FabrikStripeForm=t,this.handler.open({name:this.options.name,description:this.options.item,amount:this.options.amount,zipCode:this.options.zipCode,allowRememberMe:this.options.allowRememberMe,email:this.options.email,panelLabel:this.options.panelLabel,billingAddress:this.options.billingAddress}),e.preventDefault(),t.result=!1))}.bind(this)),window.addEventListener("popstate",function(){this.handler.close()});else if(this.options.updateCheckout){var s=this.form.form.getElement(".fabrikStripeChange");"null"!==typeOf(s)&&requirejs(["https://checkout.stripe.com/checkout.js?"],function(t){i.handler=StripeCheckout.configure({key:i.options.publicKey,image:"https://stripe.com/img/documentation/checkout/marketplace.png",locale:"auto",currency:i.options.currencyCode,token:function(t,e){p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_id",value:t.id,type:"hidden"})),p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_email",value:t.email,type:"hidden"})),p.FabrikStripeForm.form.adopt(new Element("input",{name:"stripe_token_opts",value:JSON.stringify(e),type:"hidden"})),r(".fabrikStripeLast4").text(Joomla.JText._("PLG_FORM_STRIPE_CUSTOMERS_UPDATE_CC_UPDATED"))}}),s.addEvent("click",function(t){t.preventDefault(),p.FabrikStripeForm=i.form,i.handler.open({name:i.options.name,description:i.options.item,zipCode:i.options.zipCode,allowRememberMe:i.options.allowRememberMe,email:i.options.email,panelLabel:i.options.panelLabel,billingAddress:i.options.billingAddress})}.bind(this))})}},getCoupon:function(t){p.loader.start("form_"+this.options.formid,Joomla.JText._("PLG_FORM_STRIPE_CALCULATING"));var e=this.couponElement.getValue(),o=this.options.formid,i=this;r.ajax({url:p.liveSite+"index.php",method:"post",dataType:"json",data:{option:"com_fabrik",format:"raw",task:"plugin.pluginAjax",plugin:"stripe",method:"ajax_getCoupon",amount:this.options.origAmount,g:"form",v:e,formid:o,renderOrder:this.options.renderOrder}}).always(function(){p.loader.stop("form_"+i.options.formid)}).fail(function(t,e,o){window.alert(e)}).done(function(t){i.updateForm(t)})},getCost:function(t){this.totalElement&&p.loader.start(this.options.totalElement,Joomla.JText._("PLG_FORM_STRIPE_CALCULATING"));var e=""!==this.options.productElement?this.productElement.getValue():"",o=""!==this.options.qtyElement?this.qtyElement.getValue():"",i=""!==this.options.couponElement?this.couponElement.getValue():"",n=this.options.formid,s=this;r.ajax({dataType:"json",url:p.liveSite+"index.php",method:"post",data:{option:"com_fabrik",format:"raw",task:"plugin.pluginAjax",plugin:"stripe",method:"ajax_getCost",amount:this.options.origAmount,g:"form",productId:e,qty:o,coupon:i,formid:n,renderOrder:this.options.renderOrder}}).always(function(){s.totalElement&&p.loader.stop(s.options.totalElement)}).fail(function(t,e,o){window.alert(e)}).done(function(t){s.updateForm(t)})},updateForm:function(t){this.options.amount=t.stripe_amount,r(".fabrikStripePrice").html(t.display_amount),r(".fabrikStripeItem").html(t.product_name),r(".fabrikStripeCouponText").html(t.msg),this.totalElement&&this.totalElement.update(t.display_amount)}})});