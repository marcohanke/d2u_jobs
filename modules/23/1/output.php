<?php
if(!function_exists('prepareText')) {
	/**
	 * Replaces common text changes.
	 * @param string $text Text in need of replacements
	 * @return string Replaced text
	 */
	function prepareText($text) {
		return str_replace("</li>", "</span></li>", str_replace("<li>", "<li><span>", str_replace("<ul>", '<ul class="bullets">', $text)));
	}
}

$urlParamKey = "";
if(rex_addon::get("url")->isAvailable()) {
	$url_data = UrlGenerator::getData();
	$urlParamKey = isset($url_data->urlParamKey) ? $url_data->urlParamKey : "";
}

$category_id = "REX_VALUE[1]";
$category = FALSE;
if($category_id > 0) {
	$category = new D2U_Jobs\Category($category_id, rex_clang::getCurrentId());
}
else {
	if(filter_input(INPUT_GET, 'job_category_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "job_category_id")) {
		$category_id = filter_input(INPUT_GET, 'job_category_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$category_id = UrlGenerator::getId();
		}
		$category = new D2U_Jobs\Category($category_id, rex_clang::getCurrentId());
	}
}

if(rex::isBackend()) {
	// Ausgabe im BACKEND	
?>
	<h1 style="font-size: 1.5em;">Stellenmarkt Ausgabe</h1>
<?php
	if($category === FALSE) {
		print "Anzuzeigende Kategorien: Alle";
	}
	else {
		print "Anzuzeigende Kategorie: ". $category->name;
	}
}
else {
	// FRONTEND Output
	$sprog = rex_addon::get("sprog");
	$tag_open = $sprog->getConfig('wildcard_open_tag');
	$tag_close = $sprog->getConfig('wildcard_close_tag');

	// Output job details
	if(filter_input(INPUT_GET, 'job_id', FILTER_VALIDATE_INT, ['options' => ['default'=> 0]]) > 0 || (rex_addon::get("url")->isAvailable() && $urlParamKey === "job_id")) {
		$job_id = filter_input(INPUT_GET, 'job_id', FILTER_VALIDATE_INT);
		if(rex_addon::get("url")->isAvailable() && UrlGenerator::getId() > 0) {
			$job_id = UrlGenerator::getId();
		}
		
		$job = new D2U_Jobs\Job($job_id, rex_clang::getCurrentId());
		print '<div class="medium-7 columns end">';
		print '<article class="box-grey with-text stimmen">';
		if($job->picture != "") {
			print '<img src="index.php?rex_media_type=640x249&rex_media_file='. $job->picture .'" alt="'. $job->name .'">';
		}
		print '<h1>'. $job->name .'</h1>';
		print '<h2>';
		if($job->city != "") {
			print $tag_open .'d2u_jobs_region'. $tag_close .' '. $job->city .' / ';
		}
		print $tag_open .'d2u_jobs_reference_number'. $tag_close .' '. $job->reference_number .'</h2>';
		if($job->hr4you_lead_in != "") {
			print '<br>';
			print $job->hr4you_lead_in;
		}
		if($job->tasks_heading != "") {
			print '<h3>'. $job->tasks_heading .'</h3>';
			print prepareText($job->tasks_text);
		}
		if($job->profile_heading != "") {
			print '<h3>'. $job->profile_heading .'</h3>';
			print prepareText($job->profile_text);
		}
		if($job->offer_heading != "") {
			print '<h3>'. $job->offer_heading .'</h3>';
			print prepareText($job->offer_text);
		}
		if($job->hr4you_url_application_form != "") {
			print '<br><br>';
			print '<p class="appendix"><a target="_blank" href="'. $job->hr4you_url_application_form .'">'. $tag_open .'d2u_jobs_hr4you_application_link'. $tag_close .'</a></p>';
		}
		else {
			print '<br><br>';
			print '<p class="appendix">'. $tag_open .'d2u_jobs_footer'. $tag_close
				.'<br><br><a href="mailto:'. rex_config::get('d2u_jobs', 'email') .'">'. rex_config::get('d2u_jobs', 'email') .'</a>'
				.'</p>';
		}
		print '</article>';
		print '</div>';
		print '<div class="sp sections-less hide-for-medium-up"></div>';
		print '<div class="medium-5 columns">';
		print '<div class="box-grey stimmen">';
		print $tag_open .'d2u_jobs_questions'. $tag_close .'<br><br>';
		print '<div class="row">';
		print '<div class="large-4 small-4 columns">';
		print '<img src="'. (strpos($job->contact->picture, "noavatar.jpg") !== FALSE ?  $job->contact->picture : 'index.php?rex_media_type=130x130&rex_media_file='. $job->contact->picture) .'" alt="'. $job->contact->name .'">';
		print '</div>';
		print '<div class="large-8 small-8 columns">';
		print '<h3>'. $job->contact->name .'</h3>';
		print $tag_open .'d2u_jobs_phone'. $tag_close .': '. $job->contact->phone .'<br>';
		print '</div>';
		print '</div>';
		print '</div>';
		print '</div>';
		print '</div>';
	}
	else {
		// Output Job list
		$jobs = D2U_Jobs\Job::getAll(rex_clang::getCurrentId(), $category_id, TRUE);

		if(count($jobs) > 0) {
			print '<div class="large-9 small-9 columns">';
			print '<h1>'. $tag_open .'d2u_jobs_vacancies'. $tag_close .' ';
			if($category !== FALSE) {
				print $category->name;
			}
			print '</h1>';
			print '</div>';
			foreach($jobs as $job) {
				print '<div class="medium-6 large-4 columns end">';
				print '<article class="box-grey with-text stimmen" data-height-watch>';
				if($job->picture != "") {
					print '<a href="'. $job->getUrl() .'">';
					print '<img src="index.php?rex_media_type=340x132&rex_media_file='. $job->picture .'" alt="'. $job->name .'">';
					print '</a>';
				}
				print '<h1>';
				print '<a href="'. $job->getUrl() .'">'. strtoupper($job->name) .'</a>';
				print '</h1>';
				print '<h2>';
				if($job->city != "") {
					print $tag_open .'d2u_jobs_region'. $tag_close .' '. $job->city .' / ';
				}
				print $tag_open .'d2u_jobs_reference_number'. $tag_close .' '. $job->reference_number .'</h2>';
				print '</article>';
				print '</div>';
			}
		}
	}
}
?>