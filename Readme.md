# arillo\metatags

[![Latest Stable Version](https://poser.pugx.org/arillo/silverstripe-metatags/v/stable?format=flat)](https://packagist.org/packages/arillo/silverstripe-metatags)
&nbsp;
[![Total Downloads](https://poser.pugx.org/arillo/silverstripe-metatags/downloads?format=flat)](https://packagist.org/packages/arillo/silverstripe-metatags)

Better metatags for your SilverStripe Projects.

The module will add a `$MetaImage` to each page and a fallback `$MetaImage` to the `$SiteConfig` that will be used if no image is defined on a particular page (to avoid this behavior do not fill in the `$SiteConfig.MetaImage` in the CMS). It will automatically generate [OpenGraph](http://ogp.me/) and [Twitter](https://dev.twitter.com/cards/getting-started) meta tags based on pages `$MetaDescription`.

## Usage

Install with composer:

```bash
composer require arillo/silverstripe-metatags
```

or clone the repo:

```bash
git clone git@github.com:arillo/silverstripe-metatags.git
```

Configure the extension:

```yml
MetaTagsExtension:
  titles_by_pagetype:
    Default: $Title / $SiteConfig.Title # Define the default <title> tag pattern. (Defaults to $Title)
    HomePage: $Title                    # Exception for the HomePage page-type
    ProductsPage: $Item.Title           # Pattern for a DataObject
```

Include the template in your `<head>`:

```html
<head>
  <% include $MetaTagsX %>
</head>
```

Be sure **not** to include `$MetaTags` in your `<head>`, the module includes it in the default template. To customize the rendering of the metatags copy the template `MetaTagsX.ss` to your project theme.

Why the X? Because `$MetaTags` was taken by the SilverStripe default metatags =).

### Recommended

Move the `MetaDescription` field inside the Meta Tab:

```php
<?php
class Page extends SiteTree
{
    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $medaDesc = $fields->dataFieldByName('MetaDescription');
        $fields->removeByName('Metadata');
        $fields->removeByName('MetaDescription');
        $fields->addFieldToTab('Root.Meta', $medaDesc);

        return $fields;
    }
}
```
