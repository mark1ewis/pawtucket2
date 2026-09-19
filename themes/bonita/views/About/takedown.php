<?php
	MetaTagManager::setWindowTitle($this->request->config->get("app_display_name").": "._t("Take Down Policy"));
?>

	<div class="row">
		<div class="col-sm-12">
			<H1><?php print _t("Take Down Policy"); ?></H1>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-10 col-lg-8">
			<p><?php print _t("These digitized collections are accessible for purposes of education and research. We’ve indicated what we know about the copyright status of materials. Due to the nature of archival collections, we are not always able to identify this information. We are eager to hear from any rights owners, so that we may obtain accurate information. Upon request, we’ll remove material from public view while we address any rights issue brought to our attention."); ?></p>
			<p><?php print _t("We take steps to not display information that poses privacy risks to individuals, organizations, and other entities. Despite these efforts, private information may inadvertently be included in digital collections. In such cases, living individuals whose private information is exposed are welcome to submit a takedown request."); ?></p>
		</div>
	</div>
