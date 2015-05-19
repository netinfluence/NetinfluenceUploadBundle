# Final notes

## Cleaning regularly the sandbox

It can happen that temporary files are never moved to the final filesystem: for example, in case the user never submits its form.
So to prevent having some dead files, you will want to clear the sandbox from time to time.

The bundle provides a Symfony2 command to do exactly that:
`php app/console netinf:upload:clear`
By default, files from today (as user may still be editing its form) and yesterday are kept.

You can change that:
```
php app/console netinf:upload:clear --grace=1 # clear files but those of today
php app/console netinf:upload:clear --grace=0 # clear all files
```

## Security

This bundle expose a few routes for uploading/deleting files.
If your form is within a private admin, you may want to restrict access to those routes too: 
```yml
# app/config/security.yml
security:
    access_control:
        - { path: ^/upload/, role: IS_AUTHENTICATED_REMEMBERED }
```
