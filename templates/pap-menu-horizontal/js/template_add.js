/*Cookies*/
!function(e){var n=!1;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var o=window.Cookies,t=window.Cookies=e();t.noConflict=function(){return window.Cookies=o,t}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var o=arguments[e];for(var t in o)n[t]=o[t]}return n}return function n(o){function t(n,r,i){var c;if("undefined"!=typeof document){if(arguments.length>1){if("number"==typeof(i=e({path:"/"},t.defaults,i)).expires){var a=new Date;a.setMilliseconds(a.getMilliseconds()+864e5*i.expires),i.expires=a}i.expires=i.expires?i.expires.toUTCString():"";try{c=JSON.stringify(r),/^[\{\[]/.test(c)&&(r=c)}catch(e){}r=o.write?o.write(r,n):encodeURIComponent(String(r)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=(n=(n=encodeURIComponent(String(n))).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent)).replace(/[\(\)]/g,escape);var s="";for(var f in i)i[f]&&(s+="; "+f,!0!==i[f]&&(s+="="+i[f]));return document.cookie=n+"="+r+s}n||(c={});for(var p=document.cookie?document.cookie.split("; "):[],d=/(%[0-9A-Z]{2})+/g,u=0;u<p.length;u++){var l=p[u].split("="),C=l.slice(1).join("=");this.json||'"'!==C.charAt(0)||(C=C.slice(1,-1));try{var g=l[0].replace(d,decodeURIComponent);if(C=o.read?o.read(C,g):o(C,g)||C.replace(d,decodeURIComponent),this.json)try{C=JSON.parse(C)}catch(e){}if(n===g){c=C;break}n||(c[g]=C)}catch(e){}}return c}}return t.set=t,t.get=function(e){return t.call(t,e)},t.getJSON=function(){return t.apply({json:!0},[].slice.call(arguments))},t.defaults={},t.remove=function(n,o){t(n,"",e(o,{expires:-1}))},t.withConverter=n,t}(function(){})});

/*Funciones personalizadas*/
jQuery(function($) {
	"use strict";

	$('[data-toggle="tooltip"]').tooltip(); 
	toggleSidebar();

	$(window)
		.resize(function() {
			var windowsize = $(window).width();
			if (windowsize > 1024 || windowsize < 768) {
				if (Cookies.get("sidebar")!="expanded") {
					Cookies.set("sidebar", "expanded");
					toggleSidebar();
				}
			} else {
				if (Cookies.get("sidebar")!="collapse") {
					Cookies.set("sidebar", "collapse");
					toggleSidebar();
				}
			}

		})

	$(document)
		.on('click', ".sidebar-toggle-expanded", function() {
			Cookies.set("sidebar", "collapse");
			toggleSidebar();
		})
		
		.on('click', '.sidebar-toggle-collapsed', function () {		
			Cookies.set("sidebar", "expanded");
			toggleSidebar();	
		})

		.scroll(function() {
			if ($(document).scrollTop()<=300) {
				$("#back-top").hide("fast");
			} else {
				$("#back-top").show("fast");
			}
		})

		.on('change', 'input.decimal', function() {
			$(this).val($(this).val().replace(/,/g, '.'));
		})
		
		function toggleSidebar() {
			if (Cookies.get("sidebar")=="collapse") {
				$("#sidebar").addClass("sidebar-collapse");
				$("#sidebar").addClass("span1");
				$("#sidebar").removeClass("span2");
				$(".sidebar-toggle-collapsed").removeClass("hidden");
				$(".sidebar-toggle-expanded").addClass("hidden");
				$(".menuitem").addClass("hidden");
				$("#content").removeClass("span10");
				$("#content").addClass("span11");
				$("#content").addClass("content-sidebar");
				$(".dbreadcrumbs").addClass("dbreadcrumbs-collapse");
			} else if (Cookies.get("sidebar")=="expanded") {
				$("#sidebar").removeClass("sidebar-collapse");
				$("#sidebar").removeClass("span1");
				$("#sidebar").addClass("span2");
				$(".sidebar-toggle-expanded").removeClass("hidden");
				$(".sidebar-toggle-collapsed").addClass("hidden");
				$(".menuitem").removeClass("hidden");
				$("#content").addClass("span10");
				$("#content").removeClass("span11");
				$("#content").removeClass("content-sidebar");
				$(".dbreadcrumbs").removeClass("dbreadcrumbs-collapse");
			}
		}

});