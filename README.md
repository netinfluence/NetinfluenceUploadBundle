# Netinfluence QuickerUploadBundle

User and developer friendly file upload.

Features:

 - [ ] a nice AJAX-powered async upload 
 - [ ] **totally integrated with Symfony2 forms** and transparent to use!
 - [ ] of course files are properly validated
 - [ ] multiple files upload too
 - [ ] files are uploaded to any storage of your choice (local filesystem, Amazon...) using [Gaufrette](https://github.com/KnpLabs/KnpGaufretteBundle)
 - [ ] files are stored in a sandbox first and are moved only when the form is finally valid
 - [ ] when coming back to the form, files can be removed
 - [ ] thumbnails when needed are generated using [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)
 - [ ] very easily overridable and customizable. You can even not use AJAX or handle upload files by yourself.
 
## Internals: File upload workflow

The whole bundle workflow can be summarised as this:

 * user is presented with a javascript upload input
 * when picking one or many files, those are sent to the server via AJAX
 * files are validated and then stored in the `sandbox` filesystem
 * a token is sent back and placed in the form
 * when the form is finally submitted and valid, you will receive the corresponding Symfony2 `file` objects
 * you can now handle those yourself 
