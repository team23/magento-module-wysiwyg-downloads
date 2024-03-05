# Team23 WYSIWYG Downloads

<div align="center">
  <img src="https://img.shields.io/badge/Magento-2.4-brightgreen.svg?logo=magento&longCache=true" alt="Supported Magento Versions" />
</div>

This module makes it possible to upload different files inside WYSIWYG editor.

## Table of contents

- [Installation](#installation)
- [How to use](#how-to-use)
- [Important note for multi-store setup](#important-note-for-multi-store-setup)

## Installation

Installation is done via `composer`

```shell
composer require team23/module-wysiwyg-downloads
bin/magento setup:upgrade
```

If you are on production mode, please also regenerate static files and dependency injection.

## How to use

The following file types are available by default:

- Word (`doc`, `docx`, `docm`)
- PDF (`pdf`)
- SVG (`svg`)
- Compressed Folder (`zip`)

It is possible to add extra file types to the allowed list in the configuration. Therefore, go to `Stores > 
Configuration > General > Content Management > WYSIWYG Options > Extra Allowed File Extensions in WYSIWYG Editor`.

To add a file via WYSIWYG, open up any cms page or block with text content. Select part of the text which is used as a 
download link. You can also add the download link to an image. Click on the Insert/Edit Link button and Browse icon near
the URL input field. Select the folder in which you want to upload the file. Click Browse files button and select the 
files you want to upload. Select one uploaded file and click on the Insert File button.

### New media gallery

Open `Content > Media Gallery` there you should be able to upload PDF files and select them. In any page builder element
with new media gallery. The UI stays the same. You can upload / select / delete PDF files too.

## Important note for multi-store setup

While uploading a file via WYSIWYG editor, the backend URL is typically used as base for the link  creation process.
This might lead to incorrect URLs within a multistore store view (whenever the admin url differs from frontend urls). 
This can be fixed by manually changing the generated URL. The generation process might change in a future release, but
for now you have to use the workaround.
