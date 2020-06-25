# Umc_Crud Magento 2 module

## Purpose:

This module is intended for Magento 2 developers, in order to reduce the boilerplate code when creating a CRUD extension.

## Compatibility

 - 2.4.0  
 - 2.3.5  
 - 2.3.4  
 - probably works for versions before 2.3.4, but it's not guaranteed.  
## What it does:
It provides a set of classes and interfaces that can be configured, composed or prerenced in order to avoid writing the same code over and over again.  

Example: 

(Almost) every `Save` controller for an entity does the following:

 - retrieves the data from POST.
 - may or may not transform the data received via POST
 - creates a new entity or retrieves a requested entity from the database
 - assigns the data to the entity from the point above
 - persists the entity
 - redirects "somewhere" with a success or an error message.
 
And the only variables here are 
 - the entity being added / modified
 - the way the data is processed before attaching it to the entity
 
This module provides a general admin `Save` controller that has as dependencies a set of other classes / interfaces that have only one of the responsibilities above
 - an Entity manager responsible for retrieving the data from db or instantiating a new entity
 - a data processor (interface) that processes the data
 - an entity config class will contain the details about the entity being processed.
 - side objects: a data persistor (which is basically the session) to save the data submitted in case there is an error and you need to redirect back to the form with the previously submitted data prefilled.   

All of these can be configured via `di.xml` for each entity you want to manage.  

This module also adds a few more code generators (similar to the core ones for factory or proxy for example) that will autogenerate repository classes and a few others.

# Target audience

 - this module is intended for experienced Magento 2 developers that are tired of writing the same thing over and over again. 
 - this is not intended for junior developers.
 - In order to use this you have to have good knowledge of ...  
   - how a magento CRUD module works
   - what is a <a href="https://devdocs.magento.com/guides/v2.3/extension-dev-guide/build/di-xml-file.html">virtual type</a>
   - how DI works in Magento 

## Advantages in using this module
 - less code to write, which means less code to test and less code that can malfunction
 - your copy/paste analyzer will stop complaining you have classes that look the same.
 - decrease development time. (hopefully)
 - you will have a standard way of writing all your CRUD modules (no matter if it's good or bad, at least it is consistent)
 - This covers most of the cases you will encounter in your development process. If one of your cases is not covered by this module you can choose not to extend or compose the classes in this module and use your own.
 
 
## Disadvantages of using this module
 - there is a lot more configuration you need to write (YEAH....xmls).  
 - makes debugging a little harder.
 - adds a new abstractization layer ... or 7. Just kidding. it's 1.
 - you add a new dependency to your project and all your CRUD module will depend on this module.
 
 # Installation:
   - Via composer (recommended)  
    - `composer require "umc/module-crud=*"`
   - Manual install  
    - download a copy from `https://github.com/UltimateModuleCreator/umc-crud` and all the files in `app/code/Umc/Crud`.
    
  After installation 
   - run `php bin/magento setup:upgrade [--keep-generated]`
   - check if this file exists `app/etc/crud/di.xml`. If it does not exist, run the command `bin/magento umc:crud:deploy`. If you get an error you can copy it from `vendor/umc/module-crud/etc/crud/di.xml` to `app/etc/crud/di.xml`.
  
# Documentation
  
For more details about how this extension should work, visit https://github.com/UltimateModuleCreator/umc-crud/wiki

# Donations

If you really like this and get the hang of it and it saves you a lot of development time, consider <a href="https://www.paypal.me/MariusStrajeru/10">a small (or large - I won't mind) donation via PayPal</a>
