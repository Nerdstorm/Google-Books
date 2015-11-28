<?php

namespace tests\Nerdstorm\GoogleBooks\Service;

use Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapper;

class AnnotationMapperTest extends \PHPUnit_Framework_TestCase
{
    protected $book_volume = '
        {
           "kind": "books#volume",
           "id": "2kjxBQAAQBAJ",
           "etag": "yythSs+ukDQ",
           "selfLink": "https://www.googleapis.com/books/v1/volumes/2kjxBQAAQBAJ",
           "volumeInfo": {
            "title": "Systems Analysis and Design",
            "authors": [
             "Alan Dennis",
             "Barbara Haley Wixom",
             "Roberta M. Roth"
            ],
            "publisher": "John Wiley & Sons",
            "publishedDate": "2014-11-11",
            "description": "The 6th Edition of Systems Analysis and Design continues to offer a hands-on approach to SAD
                while focusing on the core set of skills that all analysts must possess. Building on their experience as
                professional systems analysts and award-winning teachers, authors Dennis, Wixom, and Roth capture the
                experience of developing and analyzing systems in a way that students can understand and apply.
                With Systems Analysis and Design, 6th Edition, students will leave the course with experience that is a
                rich foundation for further work as a systems analyst.",
            "industryIdentifiers": [
             {
              "type": "ISBN_13",
              "identifier": "9781118897843"
             },
             {
              "type": "ISBN_10",
              "identifier": "1118897846"
             }
            ],
            "readingModes": {
             "text": false,
             "image": true
            },
            "pageCount": 448,
            "printType": "BOOK",
            "categories": [
             "Computers"
            ],
            "averageRating": 4.5,
            "ratingsCount": 3,
            "maturityRating": "NOT_MATURE",
            "allowAnonLogging": false,
            "contentVersion": "0.2.0.0.preview.1",
            "imageLinks": {
             "smallThumbnail": "http://books.google.com.au/books/content?id=2kjxBQAAQBAJ&printsec=frontcover&img=1&zoom=5&edge=curl&source=gbs_api",
             "thumbnail": "http://books.google.com.au/books/content?id=2kjxBQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api"
            },
            "language": "en",
            "previewLink": "http://books.google.com.au/books?id=2kjxBQAAQBAJ&printsec=frontcover&dq=systems+analysis+and+design&hl=&cd=1&source=gbs_api",
            "infoLink": "http://books.google.com.au/books?id=2kjxBQAAQBAJ&dq=systems+analysis+and+design&hl=&source=gbs_api",
            "canonicalVolumeLink": "http://books.google.com.au/books/about/Systems_Analysis_and_Design.html?hl=&id=2kjxBQAAQBAJ"
           },
           "saleInfo": {
            "country": "AU",
            "saleability": "NOT_FOR_SALE",
            "isEbook": false
           },
           "accessInfo": {
            "country": "AU",
            "viewability": "PARTIAL",
            "embeddable": true,
            "publicDomain": false,
            "textToSpeechPermission": "ALLOWED",
            "epub": {
             "isAvailable": false
            },
            "pdf": {
             "isAvailable": false
            },
            "webReaderLink": "http://books.google.com.au/books/reader?id=2kjxBQAAQBAJ&hl=&printsec=frontcover&output=reader&source=gbs_api",
            "accessViewStatus": "SAMPLE",
            "quoteSharingAllowed": false
           }
        }
    ';

    public function setup()
    {
        /** @var AnnotationMapper mapper */
        $this->mapper = new AnnotationMapper();
    }

    public function testVolumeEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        $object = $this->mapper->resolveEntity($json_obj['kind']);
        unset($json_obj['kind']);

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($json_obj as $key => $value) {
            $actual = call_user_func([$volume, 'get' . ucfirst($key)]);

            if (is_array($json_obj[$key])) {
                continue;
            }

            $this->assertEquals($json_obj[$key], $actual);
        }
    }

    public function testVolumeInfoEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        $object = $this->mapper->resolveEntity($json_obj['kind']);

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($json_obj['volumeInfo'] as $key => $value) {
            $actual = call_user_func([$volume->getVolumeInfo(), 'get' . ucfirst($key)]);

            if (is_array($json_obj['volumeInfo'][$key])) {
                continue;
            }

            $this->assertEquals($json_obj['volumeInfo'][$key], $actual);
        }
    }
}