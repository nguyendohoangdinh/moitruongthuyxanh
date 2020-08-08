<?php

$vendorDir = dirname(__DIR__);
$rootDir = dirname(dirname(__DIR__));

return array (
  'craftcms/ckeditor' => 
  array (
    'class' => 'craft\\ckeditor\\Plugin',
    'basePath' => $vendorDir . '/craftcms/ckeditor/src',
    'handle' => 'ckeditor',
    'aliases' => 
    array (
      '@craft/ckeditor' => $vendorDir . '/craftcms/ckeditor/src',
    ),
    'name' => 'CKEditor',
    'version' => '1.0.0-beta.2',
    'description' => 'Edit rich text content in Craft CMS using CKEditor.',
    'developer' => 'Pixel & Tonic',
    'developerUrl' => 'https://pixelandtonic.com/',
    'changelogUrl' => 'https://raw.githubusercontent.com/craftcms/ckeditor/master/CHANGELOG.md',
    'downloadUrl' => 'https://github.com/craftcms/ckeditor/archive/master.zip',
  ),
  'craftcms/redactor' => 
  array (
    'class' => 'craft\\redactor\\Plugin',
    'basePath' => $vendorDir . '/craftcms/redactor/src',
    'handle' => 'redactor',
    'aliases' => 
    array (
      '@craft/redactor' => $vendorDir . '/craftcms/redactor/src',
    ),
    'name' => 'Redactor',
    'version' => '2.4.0',
    'description' => 'Edit rich text content in Craft CMS using Redactor by Imperavi.',
    'developer' => 'Pixel & Tonic',
    'developerUrl' => 'https://pixelandtonic.com/',
    'documentationUrl' => 'https://github.com/craftcms/redactor/blob/v2/README.md',
  ),
  'verbb/icon-picker' => 
  array (
    'class' => 'verbb\\iconpicker\\IconPicker',
    'basePath' => $vendorDir . '/verbb/icon-picker/src',
    'handle' => 'icon-picker',
    'aliases' => 
    array (
      '@verbb/iconpicker' => $vendorDir . '/verbb/icon-picker/src',
    ),
    'name' => 'Icon Picker',
    'version' => '1.1.1',
    'description' => 'Provide content editors a field to pick an icon from a public directory.',
    'developer' => 'Verbb',
    'developerUrl' => 'https://verbb.io',
    'changelogUrl' => 'https://raw.githubusercontent.com/verbb/icon-picker/craft-3/CHANGELOG.md',
  ),
  'verbb/super-table' => 
  array (
    'class' => 'verbb\\supertable\\SuperTable',
    'basePath' => $vendorDir . '/verbb/super-table/src',
    'handle' => 'super-table',
    'aliases' => 
    array (
      '@verbb/supertable' => $vendorDir . '/verbb/super-table/src',
    ),
    'name' => 'Super Table',
    'version' => '2.3.1',
    'description' => 'Super-charge your Craft workflow with Super Table. Use it to group fields together or build complex Matrix-in-Matrix solutions.',
    'developer' => 'Verbb',
    'developerUrl' => 'https://verbb.io',
    'documentationUrl' => 'https://github.com/verbb/super-table',
    'changelogUrl' => 'https://raw.githubusercontent.com/verbb/super-table/craft-3/CHANGELOG.md',
  ),
  'ether/simplemap' => 
  array (
    'class' => 'ether\\simplemap\\SimpleMap',
    'basePath' => $vendorDir . '/ether/simplemap/src',
    'handle' => 'simplemap',
    'aliases' => 
    array (
      '@ether/simplemap' => $vendorDir . '/ether/simplemap/src',
    ),
    'name' => 'Maps',
    'version' => '3.8.3',
    'schemaVersion' => '3.4.2',
    'description' => 'A beautifully simple Map field type for Craft CMS 3',
    'developer' => 'Ether Creative',
    'developerUrl' => 'https://ethercreative.co.uk',
    'developerEmail' => 'help@ethercreative.co.uk',
    'documentationUrl' => 'https://docs.ethercreative.co.uk/maps',
  ),
  'barrelstrength/sprout-forms' => 
  array (
    'class' => 'barrelstrength\\sproutforms\\SproutForms',
    'basePath' => $vendorDir . '/barrelstrength/sprout-forms/src',
    'handle' => 'sprout-forms',
    'aliases' => 
    array (
      '@barrelstrength/sproutforms' => $vendorDir . '/barrelstrength/sprout-forms/src',
    ),
    'name' => 'Sprout Forms',
    'version' => '3.9.0.1',
    'description' => 'Simple, beautiful forms. 100% control.',
    'developer' => 'Barrel Strength',
    'developerUrl' => 'https://www.barrelstrengthdesign.com/',
    'developerEmail' => 'sprout@barrelstrengthdesign.com',
    'documentationUrl' => 'https://sprout.barrelstrengthdesign.com/docs/forms',
  ),
  'acoustep/craft-easymde' => 
  array (
    'class' => 'Acoustep\\CraftEasyMDE\\Plugin',
    'basePath' => $vendorDir . '/acoustep/craft-easymde/src',
    'handle' => 'easymde-markdown-editor',
    'aliases' => 
    array (
      '@Acoustep/CraftEasyMDE' => $vendorDir . '/acoustep/craft-easymde/src',
    ),
    'name' => 'Craft CMS Easy MDE Markdown Editor',
    'version' => '0.1.7',
    'description' => 'Markdown field that uses EasyMDE Editor',
    'developer' => 'Mitch Stanley',
    'developerUrl' => 'https://mitchstanley.co.uk',
    'developerEmail' => 'hello@mitchartemis.dev',
    'documentationUrl' => 'https://github.com/acoustep/craft-easymde/blob/master/README.md',
  ),
);
