<?php

namespace Arillo\MetaTags;

use SilverStripe\Core\Extension;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Config\Config;
use SilverStripe\TemplateEngine\SSTemplateEngine;
use SilverStripe\View\ViewLayerData;
use SilverStripe\i18n\i18n;

class MetaTagsExtension extends Extension
{
    const TAB_NAME = 'Root.Meta';

    private static array $db = [
        'MetaTitle' => 'Varchar(255)',
    ];

    private static array $has_one = [
        'MetaImage' => Image::class,
    ];

    private static array $owns = ['MetaImage'];

    /**
     * Prepare CMS fields for meta tags.
     * Can be called statically for manual field setup or used internally by updateCMSFields.
     *
     * @param FieldList $fields The FieldList to modify
     * @param string $tab The tab to add fields to
     * @param object|null $owner Optional owner object for Fluent decoration
     * @return FieldList
     */
    public static function prepare_cms_fields(
        FieldList $fields,
        string $tab = self::TAB_NAME,
        $owner = null
    ) {
        // Move MetaDescription from Metadata accordion to specified tab
        if ($metaDesc = $fields->dataFieldByName('MetaDescription')) {
            $fields->removeByName('Metadata');
            $fields->removeByName('MetaDescription');

            $metaTitleField = TextField::create(
                'MetaTitle',
                _t(__CLASS__ . '.MetaTitle', 'Meta title')
            )->setDescription('Custom page title for search engines and social sharing');

            $metaDesc->setTitle(
                _t(__CLASS__ . '.MetaDescription', 'Meta description')
            );

            $fields->addFieldsToTab($tab, [
                $metaTitleField,
                $metaDesc,
            ]);

            // Apply Fluent decoration if owner is provided and has FluentExtension
            if ($owner && $owner->hasExtension('TractorCow\\Fluent\\Extension\\FluentExtension')) {
                $fluentExt = $owner->getExtensionInstance('TractorCow\\Fluent\\Extension\\FluentExtension');
                if ($fluentExt && method_exists($fluentExt, 'updateFluentCMSField')) {
                    // Remove class first to force re-decoration (in case field was moved)
                    $metaTitleField->removeExtraClass('fluent__localised-field');
                    $metaDesc->removeExtraClass('fluent__localised-field');

                    $fluentExt->updateFluentCMSField($metaTitleField);
                    $fluentExt->updateFluentCMSField($metaDesc);
                }
            }
        }

        // Add MetaImage field
        $upload = UploadField::create(
            'MetaImage',
            _t(__CLASS__ . '.MetaImage', 'Meta image')
        )->setDescription('Image for social sharing (Open Graph). Recommended: 1200x630px');
        $upload->getValidator()->setAllowedExtensions(['jpeg', 'jpg', 'png']);
        $upload->setFolderName('MetaImages');

        $fields->addFieldToTab($tab, $upload);

        return $fields;
    }

    protected function updateCMSFields(FieldList $fields)
    {
        self::prepare_cms_fields($fields, self::TAB_NAME, $this->owner);
    }

    public function MetaTagsX()
    {
        $titlesByPagetype = Config::inst()->get(
            __CLASS__,
            'titles_by_pagetype'
        );
        $titleTemplate =
            '<% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %>';

        if (isset($titlesByPagetype['Default'])) {
            $titleTemplate = $titlesByPagetype['Default'];
        }

        if (
            is_array($titlesByPagetype) &&
            array_key_exists($this->owner->ClassName, $titlesByPagetype)
        ) {
            $titleTemplate = $titlesByPagetype[$this->owner->ClassName];
        }

        $title = SSTemplateEngine::create()->renderString(
            $titleTemplate,
            new ViewLayerData($this->owner)
        );

        return $this->owner
            ->customise([
                'MetaTitle' => $title,
                'ContentLocale' => i18n::get_locale(),
            ])
            ->renderWith('MetaTagsX');
    }
}
