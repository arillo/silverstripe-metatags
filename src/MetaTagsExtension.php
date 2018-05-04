<?php
namespace Arillo\MetaTags;

use SilverStripe\ORM\DataExtension;
use Silverstripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Config\Config;
use SilverStripe\View\SSViewer;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\i18n\i18n;

class MetaTagsExtension extends DataExtension
{
    private static $has_one = [
        'MetaImage' => Image::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $upload = UploadField::create('MetaImage', _t(__CLASS__ .'.MetaImage', 'MetaImage'));
        $upload
            ->getValidator()
            ->setAllowedExtensions(['jpeg','jpg','png'])
        ;

        $upload->setFolderName('MetaImages');
        $fields->addFieldToTab('Root.Meta', $upload);
        return $fields;
    }

    public function MetaTagsX()
    {
        $titlesByPagetype = Config::inst()->get(__CLASS__, 'titles_by_pagetype');
        $titleTemplate = '$Title';

        if (isset($titlesByPagetype['Default']))
        {
            $titleTemplate = $titlesByPagetype['Default'];
        }

        if (
            is_array($titlesByPagetype)
            && array_key_exists($this->owner->ClassName, $titlesByPagetype)
        ) {
            $titleTemplate = $titlesByPagetype[$this->owner->ClassName];
        }

        $template = SSViewer::fromString($titleTemplate);
        $title = $this->owner->renderWith($template);

        // $cc = new ContentController($this->owner);

        return $this->owner->customise(array(
            'MetaTitle' => $title,
            'ContentLocale' => i18n::get_locale()
        ))->renderWith('MetaTagsX');
    }

}
