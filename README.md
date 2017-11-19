[![Latest Version](https://img.shields.io/github/release/lasselehtinen/issuu.svg?style=flat-square)](https://github.com/lasselehtinen/issuu/releases)
[![Build Status](https://img.shields.io/travis/lasselehtinen/issuu/master.svg?style=flat-square)](https://travis-ci.org/lasselehtinen/issuu)
[![Quality Score](https://img.shields.io/scrutinizer/g/lasselehtinen/issuu.svg?style=flat-square)](https://scrutinizer-ci.com/g/lasselehtinen/issuu)
[![StyleCI](https://styleci.io/repos/111231767/shield)](https://styleci.io/repos/111231767)

# Issuu - API client for PHP
## Installation
You can install this package via composer using this command:
```shell
composer require lasselehtinen/issuu
```

## Usage
### Creating a client
First create a new instance with your API key and secret:
```php
use lasselehtinen\Issuu\Issuu;

$issuu = new Issuu('apikey', 'apisecret');
```

### Bookmarks
```php
use lasselehtinen\Issuu\Bookmarks;

$bookmarks = new Bookmarks($issuu);

// Available methods - See the methods DocBlock documentation for information about all available parameters
$bookmarksAdd = $bookmarks->add('publination', '081024182109-9280632f2866416d97634cdccc66715d');
$bookmarksList = $bookmarks->list();
$bookmarksDelete = $bookmarks->delete('11b27cd5-ecdc-4c39-b818-8f3c8eca443c');
```

### Documents
```php
use lasselehtinen\Issuu\Documents;

$documents = new Documents($issuu);

// Available methods - See the methods DocBlock documentation for information about all available parameters
$documentsUpload = $documents->upload('/path/to/local/file.pdf');
$documentsUrlUpload = $documents->urlUpload('http://www.example.com/sample.pdf');
$documentsList = $documents->list();
$documentsUpdate = $documents->update('racing', 'Rally cars');
$documentsDelete = $documents->delete('racing');
```

### Folders
```php
use lasselehtinen\Issuu\Folders;

$folders = new Folders($issuu);

// Available methods - See the methods DocBlock documentation for information about all available parameters
$foldersAdd = $folders->add('Cool stuff');
$foldersList = $folders->list();
$foldersUpdate = $folders->update('4c3ba964-60c3-4349-94d0-ff86db2d47c9', 'New folder name');
$foldersDelete = $folders->delete('4c3ba964-60c3-4349-94d0-ff86db2d47c9');
```

## Contributing

Pull requests are welcome. 
### Pull Requests

- Use **[PSR-2 Coding Standard.](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** 
- **Add tests!** Your patch won't be accepted if it doesn't have tests.
- **Document any change in behaviour.** Make sure the `README.md` and any other relevant documentation are kept up-to-date.
- **Send coherent history.** Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Running Tests

```bash
$ phpunit
```

## Issues

If you have problems or suggestions, please [open a new issue in GitHub](https://github.com/lasselehtinen/issuu/issues). 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
