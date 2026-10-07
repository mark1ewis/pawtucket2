<?php
/* ----------------------------------------------------------------------
 * themes/bonita/views/Details/ca_collections_default_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2022 Whirl-i-Gig
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
 
	$t_item = $this->getVar("item");
	$va_comments = $this->getVar("comments");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");
	$vn_pdf_enabled = 		$this->getVar("pdfEnabled");
	
	# --- get collections configuration
	$o_collections_config = caGetCollectionsConfig();
	$vb_show_hierarchy_viewer = true;
	if($o_collections_config->get("do_not_display_collection_browser")){
		$vb_show_hierarchy_viewer = false;	
	}
	# --- get the collection hierarchy parent to use for exportin finding aid
	$vn_top_level_collection_id = array_shift($t_item->get('ca_collections.hierarchy.collection_id', array("returnWithStructure" => true)));

	# --- get representations attached directly to this collection
	$va_all_reps = $t_item->findRepresentations(array("checkAccess" => caGetUserAccessValues($this->request), "version" => "icon"));
	$va_doc_reps = array();
	$va_img_reps = array();
	if (is_array($va_all_reps)) {
		foreach ($va_all_reps as $va_rep) {
			$vs_mimetype = $va_rep['mimetype'] ?? '';
			$vs_media_class = caGetMediaClass($vs_mimetype);
			if ($vs_media_class === 'document' || $vs_mimetype === 'application/pdf') {
				$va_doc_reps[] = $va_rep;
			} elseif ($vs_media_class === 'image') {
				$va_img_reps[] = $va_rep;
			}
		}
	}
?>
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
		<div class="container">
			<div class="row">
				<div class='col-md-12 col-lg-12'>
					<H1>{{{^ca_collections.preferred_labels.name}}}</H1>
					<H2>{{{^ca_collections.type_id}}}{{{<ifdef code="ca_collections.idno">, ^ca_collections.idno</ifdef>}}}</H2>
					{{{<ifdef code="ca_collections.parent_id"><div class="unit">Part of: <unit relativeTo="ca_collections.hierarchy" delimiter=" &gt; "><l>^ca_collections.preferred_labels.name</l></unit></div></ifdef>}}}
<?php					
					if ($vn_pdf_enabled) {
						print "<div class='exportCollection'><span class='glyphicon glyphicon-file' aria-label='"._t("Download")."'></span> ".caDetailLink($this->request, "Download as PDF", "", "ca_collections",  $vn_top_level_collection_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_collections_summary'))."</div>";
					}
?>
				</div><!-- end col -->
			</div><!-- end row -->
			<div class="row">
				<div class='col-sm-12'>
<?php
			if ($vb_show_hierarchy_viewer) {	
?>
				<div id="collectionHierarchy"><?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?></div>
				<script>
					$(document).ready(function(){
						$('#collectionHierarchy').load("<?php print caNavUrl($this->request, '', 'Collections', 'collectionHierarchy', array('collection_id' => $t_item->get('collection_id'))); ?>"); 
					})
				</script>
<?php				
			}									
?>				
				</div><!-- end col -->
			</div><!-- end row -->
			<div class="row">			
				<div class='col-md-6 col-lg-6'>
					{{{<ifdef code="ca_collections.description"><div class="unit"><label>About</label><span class="trimText">^ca_collections.description</span></div></ifdef>}}}
					{{{<ifcount code="ca_objects" min="1" max="1"><div class='unit'><unit relativeTo="ca_objects" delimiter=" "><l>^ca_object_representations.media.large</l><div class='caption'>Related Object: <l>^ca_objects.preferred_labels.name</l></div></unit></div></ifcount>}}}
<?php
				# Comment and Share Tools
				if ($vn_comments_enabled | $vn_share_enabled) {
						
					print '<div id="detailTools">';
					if ($vn_comments_enabled) {
?>				
						<div class="detailTool"><a href='#' onclick='jQuery("#detailComments").slideToggle(); return false;'><span class="glyphicon glyphicon-comment" aria-label="<?php print _t("Comments and tags"); ?>"></span>Comments (<?php print sizeof($va_comments); ?>)</a></div><!-- end detailTool -->
						<div id='detailComments'><?php print $this->getVar("itemComments");?></div><!-- end itemComments -->
<?php				
					}
					if ($vn_share_enabled) {
						print '<div class="detailTool"><span class="glyphicon glyphicon-share-alt" aria-label="'._t("Share").'"></span>'.$this->getVar("shareLink").'</div><!-- end detailTool -->';
					}
					print '</div><!-- end detailTools -->';
				}				
?>
					
				</div><!-- end col -->
				<div class='col-md-6 col-lg-6'>
<?php
					# Visual media representation viewer (if collection has image representations)
					if (sizeof($va_img_reps) > 0) {
?>
					<div class="unit visualMediaViewer">
						<?= caRepresentationViewer($this->request, $t_item, $t_item, array(
							'display' => 'detail',
							'showAnnotations' => false,
							'representationViewerShowOnlyMediaTypes' => array('image/*'),
							'primaryOnly' => false,
							'dontShowPlaceholder' => true,
							'checkAccess' => caGetUserAccessValues($this->request)
						)); ?>
<?php
						if (sizeof($va_img_reps) > 1) {
							print caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_item, array(
								'returnAs' => 'bsCols',
								'linkTo' => 'basic',
								'bsColClasses' => 'smallpadding col-sm-3 col-md-3 col-xs-4',
								'showOnlyMediaTypes' => array('image/*')
							));
						}
?>
					</div>
<?php
					}

					# Attached Documents (if collection has document / PDF representations)
					if (sizeof($va_doc_reps) > 0) {
?>
					<div class="unit attachedDocuments">
						<label><?= _t('Attached Documents'); ?></label>
<?php
						foreach ($va_doc_reps as $va_doc) {
							$vn_rep_id = (int)$va_doc['representation_id'];
							$vs_filename = $va_doc['original_filename'] ?: ($va_doc['label'] && $va_doc['label'] !== '[BLANK]' ? $va_doc['label'] : _t('Document %1', $vn_rep_id));
							
							$vs_view_url = $va_doc['urls']['original'] ?? '';
							if (!$vs_view_url && $vn_rep_id) {
								$t_rep = new ca_object_representations($vn_rep_id);
								$vs_view_url = $t_rep->getMediaUrl('media', 'original');
							}
							
							$vs_download_url = caNavUrl($this->request, '', 'Detail', 'DownloadRepresentation', array(
								'context' => 'collections',
								'representation_id' => $vn_rep_id,
								'id' => $t_item->getPrimaryKey(),
								'download' => 1,
								'version' => 'original'
							));
							
							$vn_bytes = $va_doc['info']['original']['PROPERTIES']['filesize'] ?? null;
							$vs_filesize = ($vn_bytes && $vn_bytes > 0) ? caFormatFileSize($vn_bytes) : '';
							$vn_pages = (int)($va_doc['info']['original']['PROPERTIES']['pages'] ?? 0);
							$va_file_meta = array();
							if ($vs_filesize) { $va_file_meta[] = $vs_filesize; }
							if ($vn_pages > 1) { $va_file_meta[] = $vn_pages . ' ' . _t('pages'); }
							$vs_meta_text = sizeof($va_file_meta) ? ' <span class="text-muted small">(' . join(', ', $va_file_meta) . ')</span>' : '';
?>
						<p style="margin-bottom: 8px;">
							<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
							<a href="<?= $vs_view_url; ?>" target="_blank" rel="noopener noreferrer" title="<?= _t('Open %1 in PDF viewer', htmlspecialchars($vs_filename)); ?>">
								<strong><?= htmlspecialchars($vs_filename); ?></strong>
							</a>
							<?= $vs_meta_text; ?>
							<a href="<?= $vs_download_url; ?>" class="small text-muted" style="margin-left: 8px;" title="<?= _t('Download %1', htmlspecialchars($vs_filename)); ?>">
								<span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> <?= _t('Download'); ?>
							</a>
						</p>
<?php
						}
?>
					</div>
<?php
					}

					if ($vs_entities = $t_item->getWithTemplate('<unit relativeTo="ca_entities" delimiter=""><div class="unit"><label>^relationship_typename</label><l>^ca_entities.preferred_labels</l></div></unit>')) {
						print $vs_entities;
					}
?>
					
					{{{<ifcount code="ca_collections.related" min="1"><div class="unit">
						<ifcount code="ca_collections.related" min="1" max="1"><label>Related collection</label></ifcount>
						<ifcount code="ca_collections.related" min="2"><label>Related collections</label></ifcount>
						<unit relativeTo="ca_collections.related" delimiter="<br/>"><l>^ca_collections.preferred_labels.name</l> (^relationship_typename)</unit>
					</div></ifcount>}}}
					
					{{{<ifcount code="ca_occurrences" min="1"><div class="unit">
						<ifcount code="ca_occurrences" min="1" max="1"><label>Related occurrence</label></ifcount>
						<ifcount code="ca_occurrences" min="2"><label>Related occurrences</label></ifcount>
						<unit relativeTo="ca_occurrences" delimiter="<br/>"><l>^ca_occurrences.preferred_labels.name</l> (^relationship_typename)</unit>
					</div></ifcount>}}}
					
					{{{<ifcount code="ca_places" min="1"><div class="unit">
						<ifcount code="ca_places" min="1" max="1"><label>Related place</label></ifcount>
						<ifcount code="ca_places" min="2"><label>Related places</label></ifcount>
						<unit relativeTo="ca_places" delimiter="<br/>"><l>^ca_places.preferred_labels.name</l> (^relationship_typename)</unit>
					</div></ifcount>}}}					
				</div><!-- end col -->
			</div><!-- end row -->
{{{<ifcount code="ca_objects" min="2">
			<div class="row">
				<div id="browseResultsContainer">
					<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>
				</div><!-- end browseResultsContainer -->
			</div><!-- end row -->
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery("#browseResultsContainer").load("<?php print caNavUrl($this->request, '', 'Search', 'objects', array('search' => 'collection_id:^ca_collections.collection_id'), array('dontURLEncodeParameters' => true)); ?>", function() {
						jQuery('#browseResultsContainer').jscroll({
							autoTrigger: true,
							loadingHtml: '<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>',
							padding: 20,
							nextSelector: 'a.jscroll-next'
						});
					});
					
					
				});
			</script>
</ifcount>}}}
		</div><!-- end container -->
	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->

<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 120
		});
	});
</script>
