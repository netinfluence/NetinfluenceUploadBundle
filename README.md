# NetinfluenceUploadBundle

User and developer friendly file upload.  
_Versions 0.x supports Symfony 2.x only, version 1.1 add support for Symfony 3.x, v1.2 supports Symfony 2 to 4.x._

Features:

 * a nice AJAX-powered async upload powered by [DropzoneJS](http://www.dropzonejs.com/)
 * **totally integrated with Symfony2 forms** and transparent to use!
 * of course files are properly validated
 * multiple files upload too
 * files are uploaded to any storage of your choice (local filesystem, Amazon...) using [Gaufrette](https://github.com/KnpLabs/KnpGaufretteBundle)
 * files are stored in a sandbox first
 * integrates with Doctrine ORM: files are moved from sandbox upon entity save
 * when coming back to the form, files can be removed
 * when needed, thumbnails are generated using [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
 * internationalized in English and French, you can easily add more
 * very easily overridable and customizable, if you don"t want any of the above
 
[![Latest Stable Version](https://poser.pugx.org/netinfluence/upload-bundle/v/stable)](https://packagist.org/packages/netinfluence/upload-bundle)
[![Build Status](https://travis-ci.org/netinfluence/NetinfluenceUploadBundle.svg?branch=master)](https://travis-ci.org/netinfluence/NetinfluenceUploadBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/38fb65d4-d690-4abf-b370-da4aaf1a4b0f/mini.png)](https://insight.sensiolabs.com/projects/38fb65d4-d690-4abf-b370-da4aaf1a4b0f)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/netinfluence/NetinfluenceUploadBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/netinfluence/NetinfluenceUploadBundle/?branch=master)

## Documentation

*Documentation for O.xx versions*

**Getting started:**

 * [Installation](Resources/doc/start/install.md)
 * [Setting up storage filesystems](Resources/doc/start/storage.md)
 * [Using the form type](Resources/doc/start/usage.md)
 * [Saving uploaded files](Resources/doc/start/saving.md)
 * [Final notes](Resources/doc/start/final_note.md)

**Cookbook:**

 * [Multiple files upload](Resources/doc/cookbook/multiple_upload.md)
 * [Adding extra fields to form](Resources/doc/cookbook/extra_field.md)
 * [Manipulating uploaded file](Resources/doc/cookbook/manipulating_file.md)
 * [Changing thumbnail size](Resources/doc/cookbook/thumbnail_size.md)

**Advanced:**

 * [Configuration reference](Resources/doc/advanced/reference.md)
 * [Some note about internals](Resources/doc/advanced/internals.md)

