<% if $MetaTitle %>
  <title>$MetaTitle</title>
<% end_if %>
$MetaTags(false)

<meta property="og:title" content="$MetaTitle">
<meta property="og:locale" content="$ContentLocale">
<meta property="og:type" content="website">
<meta property="og:url" content="$AbsoluteLink">

<meta name="twitter:title" content="$MetaTitle">
<meta name="twitter:url" content="$AbsoluteLink">

<% if $MetaImage %>
  <meta property="og:image" content="$MetaImage.Fill(1200,630).AbsoluteURL">
  <meta name="twitter:image" content="$MetaImage.Fill(1200,630).AbsoluteURL">
  <meta name="twitter:card" content="summary_large_image">
<% else_if $SiteConfig.MetaImage %>
  <% with $SiteConfig %>
    <meta property="og:image" content="$MetaImage.Fill(1200,630).AbsoluteURL">
    <meta name="twitter:image" content="$MetaImage.Fill(1200,630).AbsoluteURL">
    <meta name="twitter:card" content="summary_large_image">
  <% end_with %>
<% end_if %>

<% if $MetaDescription %>
  <meta property="og:description" content="$MetaDescription.ATT">
  <meta name="twitter:description" content="$MetaDescription.ATT">
<% end_if %>
