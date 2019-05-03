/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.2
 */

(function($)
{
	$(document).ready(function()
	{
		$('*[rel=tooltip]').tooltip()

		// Turn radios into btn-group
		$('.radio.btn-group label').addClass('btn');
		$(".btn-group label:not(.active)").click(function()
		{
			var label = $(this);
			var input = $('#' + label.attr('for'));

			if (!input.prop('checked')) {
				label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
				if (input.val() == '') {
					label.addClass('active btn-primary');
				} else if (input.val() == 0) {
					label.addClass('active btn-danger');
				} else {
					label.addClass('active btn-success');
				}
				input.prop('checked', true);
				input.trigger('change');
			}
		});
		$(".btn-group input[checked=checked]").each(function()
		{
			if ($(this).val() == '') {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
			} else if ($(this).val() == 0) {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
			} else {
				$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
			}
		});
		$('#back-top').on('click', function(e) {
			e.preventDefault();
			$("html, body").animate({scrollTop: 0}, 1000);
		});
		$( ".ico1" ).click(function() {
			window.open("http://www.doctorado.us.es/inicio/preguntas-frecuentes", "_blank");
		});
		$( ".ico2" ).click(function() {
			window.open("http://admisiondoctorado.us.es/", "_blank");
		});
		$( ".ico3" ).click(function() {
			window.open("https://www.juntadeandalucia.es/salud/portaldeetica/", "_blank");
		});
		$( ".ico4" ).click(function() {
			location.href="/buzon-de-contacto";
		});
		$( ".ico5" ).click(function() {
			location.href="/cluster-de-formacion";
		});
		$('#myModal1').modal('hide');
		$('#myModal2').modal('hide');
		$('#myModal3').modal('hide');
		$('#myModal4').modal('hide');
		$('.modalframe').attr("rel", "{size: {x: 1050, y: 600},closable: true, handler:'iframe'}");
        //$('.modalframe').attr("rel", "{size: {x: " + (parseInt(screen.availWidth - 30)) + ", y: " + (parseInt(screen.availHeight - 30)) + "},closable: true, handler:'iframe'}");
		
	})
})(jQuery);