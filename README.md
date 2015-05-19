# NetinfluenceUploadBundle

User and developer friendly file upload.

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

Current stable version is considered **ready for production** and is currently used on several of our projects.  
For 1.0 Flysystem will be used instead of Gaufrette, so part of the API will change. LiipImagineBundle may also subsequently be removed.

## Documentation:

**Getting started:**

 * [Installation](Resources/doc/started/install.md)
 * [Setting up storage filesystems](Resources/doc/started/storage.md)
 * [Using the form type](Resources/doc/started/usage.md)
 * [Saving uploaded files](Resources/doc/started/saving.md)
 * [Final notes](Resources/doc/started/final_note.md)

**Cookbook:**

 * [Multiple files upload](Resources/doc/cookbook/multiple_upload.md)
 * [Adding extra fields to form](Resources/doc/cookbook/extra_field.md)
 * [Manipulating uploaded file](Resources/doc/cookbook/manipulating_file.md)
 * [Changing thumbnail size](Resources/doc/cookbook/thumbnail_size.md)

**Advanced:**

 * [Configuration reference](Resources/doc/advanced/reference.md)
 * [Some note about internals](Resources/doc/advanced/internals.md)

