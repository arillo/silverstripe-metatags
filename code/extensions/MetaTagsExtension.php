<?php

namespace arillo\metatags;

use \DataExtension;
use \Config;
use \SSViewer;
use \ContentController;
use \UploadField;
use \FieldList;
use \i18n;

class MetaTagsExtension extends DataExtension {

	private static $has_one = array(
		'MetaImage' => 'Image'
	);

	public function updateCMSFields(FieldList $fields) {
		$upload = UploadField::create('MetaImage', _t('MetaTagsSiteConfigExtension.MetaImage', 'MetaImage'));
		$upload
		  ->getValidator()
		  ->setAllowedExtensions(
			array(
			  'jpeg','jpg','png'
			)
		);
		$upload->setAllowedMaxFileNumber(1);
		$upload->setFolderName('MetaImages');
		$fields->addFieldToTab('Root.Meta', $upload);
		return $fields;
	}

	// only used when extending SiteTree
	public function MetaTagsX() {

		$titles_by_pagetype = Config::inst()->get('MetaTagsExtension', 'titles_by_pagetype');
		$title_template = '$Title';

		if(isset($titles_by_pagetype['Default'])){
			$title_template = $titles_by_pagetype['Default'];
		}
		if(array_key_exists($this->owner->ClassName, $titles_by_pagetype)){
			$title_template = $titles_by_pagetype[$this->owner->ClassName];
		}

		$template = SSViewer::fromString($title_template);
		$title = $this->owner->customise(array(
		))->renderWith($template);

		$cc = new ContentController($this->owner);

		return $this->owner->customise(array(
			'MetaTitle' => $title,
			'ContentLocale' => i18n::get_locale()
		))->renderWith('MetaTagsX');
	}

}
