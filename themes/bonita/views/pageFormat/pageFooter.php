<?php
/* ----------------------------------------------------------------------
 * views/pageFormat/pageFooter.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2015-2025 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
?>
		<div style="clear:both; height:1px;"><!-- empty --></div>
		</div><!-- end pageArea --></div><!-- end main --></div><!-- end col --></div><!-- end row --></div><!-- end container -->
		<footer id="footer" role="contentinfo">
			<ul class="list-inline pull-right social">
				<li><a href="https://www.facebook.com/BonitaMuseumandCulturalCenter/" target="_blank" rel="noopener noreferrer" title="Facebook"><i class="fa fa-facebook-square" aria-label="<?php print _t("Facebook"); ?>"></i></a></li>
				<li><a href="https://www.instagram.com/visit_bmcc/" target="_blank" rel="noopener noreferrer" title="Instagram"><i class="fa fa-instagram" aria-label="<?php print _t("Instagram"); ?>"></i></a></li>
			</ul>
			<div>
				Bonita Historical Society &bull; 4355 Bonita Road, Bonita, CA 91902 &bull; (619) 267-5141
			</div>
			<ul class="list-inline">
				<?= ((CookieOptionsManager::cookieManagerEnabled()) ? "<li>".caNavLink($this->request, _t("Manage Cookies"), "", "", "Cookies", "manage")."</li>" : ""); ?>
				<li><?= caNavLink($this->request, _t("About"), "", "", "About", "Index"); ?></li>
				<li><?= caNavLink($this->request, _t("Collections"), "", "", "Collections", "index"); ?></li>
				<!-- Contact link hidden from footer. To re-enable, uncomment the line below:
				<li><?= caNavLink($this->request, _t("Contact"), "", "", "Contact", "Form"); ?></li>
				-->
				<li><?= caNavLink($this->request, _t("Take Down Policy"), "", "", "About", "takedown"); ?></li>
				<li><a href="https://bonitahistoricalsociety.org" target="_blank" rel="noopener noreferrer">Bonita Museum &amp; Cultural Center</a></li>
			</ul>
			<div><small>&copy; <?= date('Y'); ?> Bonita Historical Society. Powered by <a href="https://www.collectiveaccess.org" target="_blank" rel="noopener noreferrer">CollectiveAccess</a>.</small></div>
		</footer><!-- end footer -->
		<?= TooltipManager::getLoadHTML(); ?>
		<div id="caMediaPanel" role="complementary"> 
			<div id="caMediaPanelContentArea">
			
			</div>
		</div>
		<script type="text/javascript">
			/*
				Set up the "caMediaPanel" panel that will be triggered by links in object detail
				Note that the actual <div>'s implementing the panel are located here in views/pageFormat/pageFooter.php
			*/
			var caMediaPanel;
			jQuery(document).ready(function() {
				if (caUI.initPanel) {
					caMediaPanel = caUI.initPanel({ 
						panelID: 'caMediaPanel',										/* DOM ID of the <div> enclosing the panel */
						panelContentID: 'caMediaPanelContentArea',		/* DOM ID of the content area <div> in the panel */
						onCloseCallback: function(data) {
							if(data && data.url) {
								window.location = data.url;
							}
						},
						exposeBackgroundColor: '#FFFFFF',						/* color (in hex notation) of background masking out page content; include the leading '#' in the color spec */
						exposeBackgroundOpacity: 0.7,							/* opacity of background color masking out page content; 1.0 is opaque */
						panelTransitionSpeed: 400, 									/* time it takes the panel to fade in/out in milliseconds */
						allowMobileSafariZooming: true,
						mobileSafariViewportTagID: '_msafari_viewport',
						closeButtonSelector: '.close'					/* anything with the CSS classname "close" will trigger the panel to close */
					});
				}
			});
			/*(function(e,d,b){var a=0;var f=null;var c={x:0,y:0};e("[data-toggle]").closest("li").on("mouseenter",function(g){if(f){f.removeClass("open")}d.clearTimeout(a);f=e(this);a=d.setTimeout(function(){f.addClass("open")},b)}).on("mousemove",function(g){if(Math.abs(c.x-g.ScreenX)>4||Math.abs(c.y-g.ScreenY)>4){c.x=g.ScreenX;c.y=g.ScreenY;return}if(f.hasClass("open")){return}d.clearTimeout(a);a=d.setTimeout(function(){f.addClass("open")},b)}).on("mouseleave",function(g){d.clearTimeout(a);f=e(this);a=d.setTimeout(function(){f.removeClass("open")},b)})})(jQuery,window,200);*/
		</script>
		<?= $this->render("Cookies/banner_html.php"); ?>
	</body>
</html>
