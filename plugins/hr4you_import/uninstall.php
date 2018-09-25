<?php
$sql = rex_sql::factory();

$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() . 'd2u_jobs_jobs` DROP COLUMN `hr4you_job_id`;');
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() . 'd2u_jobs_jobs` DROP COLUMN `hr4you_url_application_form`;');
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() . 'd2u_jobs_jobs_lang` DROP COLUMN `hr4you_lead_in`;');
$sql->setQuery('ALTER TABLE `'. rex::getTablePrefix() . 'd2u_jobs_categories` DROP COLUMN `hr4you_category_id`;');

// Delete language replacements
d2u_jobs_lang_helper::factory()->uninstall();

if (!rex_config::has('d2u_jobs', 'hr4you_autoimport')) {
	rex_config::remove('d2u_jobs', 'hr4you_autoimport');
}
if (!rex_config::has('d2u_jobs', 'hr4you_default_category')) {
	rex_config::remove('d2u_jobs', 'hr4you_default_category');
}
if (!rex_config::has('d2u_jobs', 'hr4you_default_lang')) {
	rex_config::remove('d2u_jobs', 'hr4you_default_lang');
}
if (!rex_config::has('d2u_jobs', 'hr4you_headline_tag')) {
	rex_config::remove('d2u_jobs', 'hr4you_headline_tag');
}
if (!rex_config::has('d2u_jobs', 'hr4you_media_category')) {
	rex_config::remove('d2u_jobs', 'hr4you_media_category');
}
if (!rex_config::has('d2u_jobs', 'hr4you_xml_url')) {
	rex_config::remove('d2u_jobs', 'hr4you_xml_url');
}