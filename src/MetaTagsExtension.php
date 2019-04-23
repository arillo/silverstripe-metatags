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
    const TAB_NAME = 'Root.Meta';

    private static
        $has_one = [
            'MetaImage' => Image::class,
        ],

        $owns = [
            'MetaImage',
        ]
    ;

    public static function prepare_cms_fields(
        FieldList $fields,
        string $tab = self::TAB_NAME
    ) {
        if ($medaDesc = $fields->dataFieldByName('MetaDescription'))
        {
            $fields->removeByName('Metadata');
            $fields->removeByName('MetaDescription');
            $fields->addFieldToTab($tab, $medaDesc);
        }

        return $fields;
    }

    public function updateCMSFields(FieldList $fields)
    {
        $upload = UploadField::create('MetaImage', _t(__CLASS__ .'.MetaImage', 'Meta image'));
        $upload
            ->getValidator()
            ->setAllowedExtensions(['jpeg','jpg','png'])
        ;

        $upload->setFolderName('MetaImages');
        $fields->addFieldToTab(self::TAB_NAME, $upload);
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
