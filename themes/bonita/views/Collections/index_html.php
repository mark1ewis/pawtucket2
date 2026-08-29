<?php
	$o_collections_config = $this->getVar("collections_config");
	$va_access_values = $this->getVar("access_values");

	$va_find_params = array('preferred_labels' => array('is_preferred' => 1));
	if ($vs_type = $o_collections_config->get("landing_page_collection_type")) {
		$t_list = new ca_lists();
		if ($vn_type_id = $t_list->getItemIDFromList("collection_types", $vs_type)) {
			$va_find_params['type_id'] = $vn_type_id;
		}
	}
	$vs_sort = ($o_collections_config->get("landing_page_sort")) ? $o_collections_config->get("landing_page_sort") : "ca_collections.preferred_labels.name";
	$qr_collections = ca_collections::find($va_find_params, array('returnAs' => 'searchResult', 'checkAccess' => $va_access_values, 'sort' => $vs_sort));

	if ($qr_collections) {
		$o_result_context = new ResultContext($this->request, "ca_collections", "collections");
		$o_result_context->setAsLastFind();
		$o_result_context->setResultList($qr_collections->getPrimaryKeyValues(1000));
		$o_result_context->saveContext();
	}
?>
	<div class="row">
		<div class='col-md-12 col-lg-12 collectionsList'>
			<h1><?php print $this->getVar("section_name"); ?></h1>
			<p><?php print $o_collections_config->get("collections_intro_text"); ?></p>
<?php	
	$vn_i = 0;
	if($qr_collections && $qr_collections->numHits()) {
		while($qr_collections->nextHit()) {
			if ( $vn_i == 0) { print "<div class='row'>"; } 
			print "<div class='col-sm-6'><div class='collectionTile'><div class='title'>".caDetailLink($this->request, $qr_collections->get("ca_collections.preferred_labels"), "", "ca_collections",  $qr_collections->get("ca_collections.collection_id"))."</div>";	
			if (($o_collections_config->get("description_template")) && ($vs_scope = $qr_collections->getWithTemplate($o_collections_config->get("description_template")))) {
				print "<div>".$vs_scope."</div>";
			}
			print "</div></div>";
			$vn_i++;
			if ($vn_i == 2) {
				print "</div><!-- end row -->\n";
				$vn_i = 0;
			}
		}
		if (($vn_i < 2) && ($vn_i != 0) ) {
			print "</div><!-- end row -->\n";
		}
	} else {
		print _t('No collections available');
	}
?>
		</div>
	</div>
