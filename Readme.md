# Arillo\MetaTags

[![Latest Stable Version](https://poser.pugx.org/arillo/silverstripe-metatags/v/stable?format=flat)](https://packagist.org/packages/arillo/silverstripe-metatags)
&nbsp;
[![Total Downloads](https://poser.pugx.org/arillo/silverstripe-metatags/downloads?format=flat)](https://packagist.org/packages/arillo/silverstripe-metatags)

Better metatags for your SilverStripe Projects.

The module will add a `$MetaImage` to each page and a fallback `$MetaImage` to the `$SiteConfig` that will be used if no image is defined on a particular page (to avoid this behavior do not fill in the `$SiteConfig.MetaImage` in the CMS). It will automatically generate [OpenGraph](http://ogp.me/) and [Twitter](https://dev.twitter.com/cards/getting-started) meta tags based on pages `$MetaDescription`.

### Requirements

SilverStripe CMS ^4.0

For a SilverStripe 3.x compatible version of this module, please see the [1 branch, or 0.x release line](https://github.com/arillo/silverstripe-metatags/tree/1.x).

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
Arillo\MetaTags\MetaTagsExtension:
  titles_by_pagetype:
    Default: <% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> / $SiteConfig.Title # Define the default <title> tag pattern. (Defaults to $Title)
    HomePage: <% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %>                    # Exception for the HomePage page-type
    ProductsPage: $Item.Title           # Pattern for a DataObject
```

Include the template in your `<head>`:

```html
<head>
  $MetaTagsX
</head>
```

Be sure **not** to include `$MetaTags` in your `<head>`, the module includes it in the default template. To customize the rendering of the metatags copy the template `MetaTagsX.ss` to your project theme.

Why the X? Because `$MetaTags` was taken by the SilverStripe default metatags =).

### Recommended

Move the `MetaDescription` field inside the Meta Tab:

```php
<?php
use Arillo\MetaTags\MetaTagsExtension;
class Page extends SiteTree
{
    public function getCMSFields() {
        // move meta fields
        return MetaTagsExtension::prepare_cms_fields(parent::getCMSFields());
    }
}
```
