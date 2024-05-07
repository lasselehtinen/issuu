[![Latest Version](https://img.shields.io/packagist/v/lasselehtinen/issuu.svg)](https://github.com/lasselehtinen/issuu/releases)
![Latest build](https://github.com/lasselehtinen/issuu/actions/workflows/run-tests.yml/badge.svg)

# Issuu - API client for PHP

## Notes

The version 3.0 is for the Issuu API v2. Old API was deprecated on May 1st, 2024.

## Installation
You can install this package via composer using this command:
```shell
composer require lasselehtinen/issuu
```
## Supported functionality
# Drafts
| Endpoint                               | Supported |
|----------------------------------------|-----------|
| List drafts                            | Yes       |
| Create a new Draft                     | Yes       |
| Delete a Draft by slug                 | Yes       |
| Update a Draft by slug                 | Yes       |
| Upload a document for a Draft by slug  | Yes       |
| Publish a Draft by slug                | Yes       |

# Publications
| Endpoint                                 | Supported |
|------------------------------------------|-----------|
| List Publications                        | Yes       |
| Get Publication by slug                  | Yes       |
| Delete Publication by slug               | Yes       |
| Get Publication assets by slug           | No        |
| Get Publication Fullscreen share by slug | No        |
| Get Publication Reader Share URL by slug | No        |
| Get Publication QRCode share by slug     | No        |
| Get Publication Embed code by slug       | No        |

# Stacks
| Endpoint                              | Supported |
|---------------------------------------|-----------|
| List Stacks                           | Yes       |
| Create a new Stack                    | No        |
| Get Stack data by ID                  | No        |
| Delete a Stack by ID                  | No        |
| Update Stack data by ID               | No        |
| Get Stack Items slug                  | No        |
| Add Stack Item by slug to stack       | No        |
| Delete Stack Item by slug from stack  | No        |

# Stats
| Endpoint                              | Supported |
|---------------------------------------|-----------|
| Get Stats                             | No        |

# User
| Endpoint                              | Supported |
|---------------------------------------|-----------|
| Get User Profile                      | No        |
| Get User Features                     | No        |

## Usage
### Creating a client
First create a new instance with your API key and secret:
```php
use lasselehtinen\Issuu\Issuu;

$issuu = new Issuu('apiKey');
$drafts = new Drafts($issuu);

$body = [
    'confirmCopyright' => true,
    'fileUrl' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
    'info' => [
        'file' => 0,
        'access' => 'PUBLIC',
        'title' => 'Example title',
        'description' => 'Description',
        'preview' => false,
        'type' => 'editorial',
        'showDetectedLinks' => false,
        'downloadable' => false,
        'originalPublishDate' => '1970-01-01T00:00:00.000Z',
    ],
];

$createDraft = $drafts->create($body);
$drafts->publishDraftBySlug($createDraft->slug);

// Try few times until the file is converted
for ($i=0; $i < 10; $i++) {
    $draft = $drafts->getDraftBySlug($createDraft->slug);
    $conversionStatus =  $draft->fileInfo->conversionStatus;
    
    if ($conversionStatus === 'DONE') {
        break;
    }

    sleep(2);
}

$publishDraftBySlug = $drafts->publishDraftBySlug($createDraft->slug, ['desiredName' => 'foobar']);

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
